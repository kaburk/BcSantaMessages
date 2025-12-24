<?php
declare(strict_types=1);

namespace BcSantaMessage\Controller\Admin;

use BaserCore\Controller\Admin\BcAdminAppController;
use BcSantaMessage\Form\SantaMessageForm;
use BcSantaMessage\Service\Ai\AiClientFactory;
use BcSantaMessage\Service\SettingsService;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\TooManyRequestsException;

class SantaMessagesController extends BcAdminAppController
{
    /**
     * beforeFilter
     *
     * @param EventInterface $event
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // testGenerate アクションのFormProtection/Securityを緩める（管理画面AJAXテスト用）
        // ※理想は、Admin側もCSRFトークン送信を整えてunlock不要にすること
        if ($this->request->getParam('action') === 'testGenerate') {
            if ($this->components()->has('FormProtection')) {
                $this->FormProtection->setConfig('unlockedActions', ['testGenerate']);
            }
            if ($this->components()->has('Security') && isset($this->Security)) {
                $this->Security->setConfig('unlockedActions', ['testGenerate']);
            }
        }
    }

    public function index()
    {
        $tbl = $this->fetchTable('BcSantaMessage.SantaMessageSettings');
        $setting = $tbl->find()->first();

        if (!$setting) {
            $setting = $tbl->newEntity([
                'provider' => 'gemini',
                'gemini_model' => 'gemini-2.0-flash',
                'gemini_endpoint' => 'https://generativelanguage.googleapis.com/v1/models',
                'ollama_base_url' => 'http://host.docker.internal:11434',
                'ollama_model' => 'llama3',
                'max_tokens' => 1200,
                'temperature' => 0.90,
                'rate_limit_seconds' => 10,
                'enabled' => true,
            ]);
            $tbl->saveOrFail($setting);
        }

        // 既存設定を自動更新
        $needsSave = false;
        if ($setting && isset($setting->max_tokens) && (int)$setting->max_tokens < 800) {
            $setting->max_tokens = 1200;
            $needsSave = true;
            \Cake\Log\Log::info('max_tokens updated to 1200 for better Japanese generation');
        }
        if ($needsSave) {
            $tbl->saveOrFail($setting);
        }

        if ($this->request->is(['post', 'put', 'patch'])) {
            $setting = $tbl->patchEntity($setting, $this->request->getData());

            if (($setting->provider ?? '') === 'gemini' && empty($setting->gemini_api_key)) {
                $setting->setError('gemini_api_key', ['Gemini利用時はAPIキーが必要です']);
            }

            if (!$setting->hasErrors() && $tbl->save($setting)) {
                (new SettingsService())->clearCache();
                $this->BcMessage->setSuccess('設定を保存しました。');
                return $this->redirect($this->request->getRequestTarget());
            }
            $this->BcMessage->setError('保存に失敗しました。入力を確認してください。');
        }

        $this->set(compact('setting'));
    }

    /**
     * 保存済みメッセージ一覧
     */
    public function messages()
    {
        $this->request->allowMethod(['get']);
        $tbl = $this->fetchTable('BcSantaMessage.SantaMessages');
        $query = $tbl->find();

        $this->paginate = [
            'limit' => 20,
            'order' => ['created' => 'desc'],
            'sortableFields' => ['id', 'child_name', 'age', 'tone', 'provider', 'model', 'created']
        ];
        $messages = $this->paginate($query);
        $this->set(compact('messages'));
    }

    public function testGenerate()
    {
        $this->request->allowMethod(['post']);

        $this->autoRender = false;
        $this->response = $this->response->withType('application/json');

        $settings = (new SettingsService())->get();
        $rateSeconds = (int)($settings['rateLimitSeconds'] ?? 10);

        // 管理画面テスト用レート制限（同一IP）
        $ip = $this->request->clientIp() ?: 'unknown';
        $key = 'bc_santa_admin_test_last_' . sha1($ip);
        $last = $this->request->getSession()->read($key);
        $now = time();

        if ($rateSeconds > 0 && is_int($last) && ($now - $last) < $rateSeconds) {
            throw new TooManyRequestsException(
                "テスト生成は連続実行できません。{$rateSeconds}秒ほど待ってから再実行してください。"
            );
        }
        $this->request->getSession()->write($key, $now);

        $data = (array)$this->request->getData('test');

        $form = new SantaMessageForm();
        if (!$form->validate($data)) {
            throw new BadRequestException('入力内容を確認してください。（名前は必須）');
        }

        $childName = (string)($data['child_name'] ?? '');
        $age = (string)($data['age'] ?? '');
        $good = (string)($data['good_thing'] ?? '');
        $gift = (string)($data['gift_hint'] ?? '');
        $tone = (string)($data['tone'] ?? '優しい');

        $prompt = <<<PROMPT
あなたはサンタクロースです。日本語で、子ども向けに短め（250〜500文字）にメッセージを書いてください。
条件:
- 口調: {$tone}
- 宛名: {$childName}（{$age}歳）
- 「今年がんばったこと」: {$good}
- 「欲しいもののヒント」: {$gift}
- クリスマスらしい温かさ、励まし、少しのユーモア
- 個人情報（住所・学校名など）を推測して書かない
- 最後に「— サンタより」で締める

出力は本文のみ。
PROMPT;

        try {
            $client = AiClientFactory::create();
            $text = $client->generate($prompt);

            $responseData = [
                'ok' => true,
                'message' => $text,
            ];
        } catch (\Cake\Http\Client\Exception\NetworkException $e) {
            \Cake\Log\Log::error('Network error: ' . $e->getMessage());
            $responseData = [
                'ok' => false,
                'message' => 'AI サーバーに接続できません。設定を確認してください。（' . $e->getMessage() . '）',
            ];
        } catch (\Throwable $e) {
            \Cake\Log\Log::error('AI generation error: ' . $e->getMessage());
            $responseData = [
                'ok' => false,
                'message' => 'メッセージの生成に失敗しました: ' . $e->getMessage(),
            ];
        }

        $json = json_encode($responseData, JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            $json = '{"ok":false,"message":"JSONエンコードに失敗しました"}';
        }

        $this->response = $this->response->withStringBody($json);
        return $this->response;
    }

    /**
     * 詳細表示
     * @param int $id
     */
    public function view(int $id)
    {
        $this->request->allowMethod(['get']);
        $tbl = $this->fetchTable('BcSantaMessage.SantaMessages');
        $message = $tbl->find()->where(['id' => $id])->first();
        if (!$message) {
            $this->BcMessage->setError(__d('baser_core', 'データが見つかりませんでした'));
            return $this->redirect(['action' => 'messages']);
        }
        $this->set(compact('message'));
    }

    /**
     * 削除
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->request->allowMethod(['post', 'delete']);
        $tbl = $this->fetchTable('BcSantaMessage.SantaMessages');
        $entity = $tbl->find()->where(['id' => $id])->first();
        if (!$entity) {
            $this->BcMessage->setError(__d('baser_core', 'データが見つかりませんでした'));
            return $this->redirect(['action' => 'messages']);
        }
        if ($tbl->delete($entity)) {
            $this->BcMessage->setSuccess(__d('baser_core', '削除しました'));
        } else {
            $this->BcMessage->setError(__d('baser_core', '削除に失敗しました'));
        }
        return $this->redirect(['action' => 'messages']);
    }
}
