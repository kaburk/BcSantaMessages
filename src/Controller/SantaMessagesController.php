<?php
declare(strict_types=1);

namespace BcSantaMessage\Controller;

use BaserCore\Controller\BcFrontAppController;
use BcSantaMessage\Form\SantaMessageForm;
use BcSantaMessage\Service\Ai\AiClientFactory;
use BcSantaMessage\Service\SettingsService;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\TooManyRequestsException;

class SantaMessagesController extends BcFrontAppController
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

        // generate アクションのFormProtectionを無効化
        if ($this->request->getParam('action') === 'generate') {
            if ($this->components()->has('FormProtection')) {
                $this->FormProtection->setConfig('unlockedActions', ['generate']);
            }
            if ($this->components()->has('Security') && isset($this->Security)) {
                $this->Security->setConfig('unlockedActions', ['generate']);
            }
        }
    }

    public function index()
    {
        $this->set('pageTitle', 'サンタからのメッセージ');
    }

    public function generate()
    {
        $this->request->allowMethod(['post']);

        // autoRenderを無効化してJSONレスポンスを直接返す
        $this->autoRender = false;
        $this->response = $this->response->withType('application/json');

        // デバッグ情報をログに出力
        \Cake\Log\Log::debug('=== generate (front) called ===');
        \Cake\Log\Log::debug('Content-Type: ' . $this->request->getHeaderLine('Content-Type'));
        \Cake\Log\Log::debug('Accept: ' . $this->request->getHeaderLine('Accept'));
        \Cake\Log\Log::debug('Request data: ' . json_encode($this->request->getData()));

        try {
            $settings = (new SettingsService())->get();
            if (!($settings['enabled'] ?? true)) {
                throw new BadRequestException('現在この機能は停止中です。');
            }

            $rateSeconds = (int)($settings['rateLimitSeconds'] ?? 10);

            // レート制限（同一IP）
            $ip = $this->request->clientIp() ?: 'unknown';
            $key = 'bc_santa_last_' . sha1($ip);
            $last = $this->request->getSession()->read($key);
            $now = time();

            if ($rateSeconds > 0 && is_int($last) && ($now - $last) < $rateSeconds) {
                throw new TooManyRequestsException('連続送信を制限しています。少し待ってから試してください。');
            }
            $this->request->getSession()->write($key, $now);

            $form = new SantaMessageForm();
            $data = $this->request->getData();

            if (!$form->validate($data)) {
                throw new BadRequestException('入力内容を確認してください。');
            }

            $prompt = $this->buildPrompt($data);

            \Cake\Log\Log::debug('About to generate message');
            $client = AiClientFactory::create();
            $text = $client->generate($prompt);
            \Cake\Log\Log::debug('Generated text length: ' . strlen($text));

            // Save generated message
            try {
                $tbl = $this->fetchTable('BcSantaMessage.SantaMessages');
                $settingsProvider = (string)($settings['provider'] ?? null);
                $model = null;
                if ($settingsProvider === 'gemini') {
                    $model = (string)($settings['gemini']['model'] ?? null);
                } elseif ($settingsProvider === 'ollama') {
                    $model = (string)($settings['ollama']['model'] ?? null);
                }

                $entity = $tbl->newEntity([
                    'child_name' => (string)($data['child_name'] ?? ''),
                    'age' => isset($data['age']) ? (int)$data['age'] : null,
                    'good_thing' => (string)($data['good_thing'] ?? ''),
                    'gift_hint' => (string)($data['gift_hint'] ?? ''),
                    'tone' => (string)($data['tone'] ?? ''),
                    'message' => $text,
                    'provider' => $settingsProvider,
                    'model' => $model,
                    'client_ip' => $this->request->clientIp(),
                    'user_agent' => $this->request->getHeaderLine('User-Agent'),
                ]);
                $tbl->save($entity);
            } catch (\Throwable $saveError) {
                \Cake\Log\Log::error('Failed to save santa message: ' . $saveError->getMessage());
            }

            $responseData = [
                'ok' => true,
                'message' => $text,
            ];
        } catch (TooManyRequestsException $e) {
            \Cake\Log\Log::error('Rate limit error: ' . $e->getMessage());
            $responseData = [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        } catch (BadRequestException $e) {
            \Cake\Log\Log::error('Validation error: ' . $e->getMessage());
            $responseData = [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        } catch (\Cake\Http\Client\Exception\NetworkException $e) {
            \Cake\Log\Log::error('Network error: ' . $e->getMessage());
            $responseData = [
                'ok' => false,
                'message' => 'AI サーバーに接続できません。しばらくしてから再度お試しください。',
            ];
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Generation error: ' . $e->getMessage());
            $responseData = [
                'ok' => false,
                'message' => 'メッセージの生成に失敗しました。もう一度お試しください。',
            ];
        }

        \Cake\Log\Log::debug('Returning JSON response');
        $this->response = $this->response->withStringBody(json_encode($responseData));
        return $this->response;
    }

    private function buildPrompt(array $data): string
    {
        $childName = (string)($data['child_name'] ?? '');
        $age = (string)($data['age'] ?? '');
        $good = (string)($data['good_thing'] ?? '');
        $gift = (string)($data['gift_hint'] ?? '');
        $tone = (string)($data['tone'] ?? '優しい');

        return <<<PROMPT
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
    }
}
