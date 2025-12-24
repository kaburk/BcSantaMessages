<?php
declare(strict_types=1);

namespace BcSantaMessage\Service\Ai;

use Cake\Http\Client;
use RuntimeException;

class GeminiClient implements AiClientInterface
{
    public function __construct(private array $settings = [])
    {
    }

    public function generate(string $prompt, array $options = []): string
    {
        $cfg = $this->settings['gemini'] ?? [];
        $apiKey = (string)($cfg['apiKey'] ?? '');
        if ($apiKey === '') {
            throw new RuntimeException('Gemini APIキーが未設定です（管理画面で設定してください）');
        }

        $model = (string)($cfg['model'] ?? 'gemini-2.0-flash');
        // v1 API を使用
        $endpointBase = rtrim((string)($cfg['endpoint'] ?? 'https://generativelanguage.googleapis.com/v1/models'), '/');

        $gen = $this->settings['generation'] ??[];
        // 日本語は1文字で2-3トークン消費するため、1200トークンで400-500文字程度の文章が生成される想定
        $maxTokens = (int)($options['maxTokens'] ?? $gen['maxTokens'] ?? 1200);
        $temperature = (float)($options['temperature'] ?? $gen['temperature'] ?? 0.9);

        $url = "{$endpointBase}/{$model}:generateContent?key=" . rawurlencode($apiKey);

        $http = new Client(['timeout' => 20]);
        $payload = [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ],
            'generationConfig' => [
                'maxOutputTokens' => $maxTokens,
                'temperature' => $temperature,
                'stopSequences' => ['— サンタより'],
            ],
        ];

        $res = $http->post($url, json_encode($payload, JSON_UNESCAPED_UNICODE), [
            'type' => 'json',
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        if (!$res->isOk()) {
            throw new RuntimeException('Gemini API error: ' . $res->getStatusCode() . ' ' . $res->getStringBody());
        }

        $data = $res->getJson();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        $text = is_string($text) ? trim($text) : '';
        $finish = $data['candidates'][0]['finishReason'] ?? null;

        // トークン上限で途切れた場合や締めのフレーズが含まれない場合は一度だけ継続生成を試みる
        if (($finish === 'MAX_TOKENS' || mb_strpos($text, '— サンタより') === false)) {
            $continueTokens = max(300, (int)floor($maxTokens / 2));
            // 冒頭の再生成を避けるため、続きのみを促す
            $payload2 = [
                'contents' => [
                    ['parts' => [[
                        'text' => "以下の文章の続きのみを書いてください。冒頭の挨拶や導入は繰り返さないでください。最後は『— サンタより』で締めてください。\n\n" . $text
                    ]]],
                ],
                'generationConfig' => [
                    'maxOutputTokens' => $continueTokens,
                    'temperature' => $temperature,
                    'stopSequences' => ['— サンタより'],
                ],
            ];

            $res2 = $http->post($url, json_encode($payload2, JSON_UNESCAPED_UNICODE), [
                'type' => 'json',
                'headers' => ['Content-Type' => 'application/json'],
            ]);

            if ($res2->isOk()) {
                $data2 = $res2->getJson();
                $text2 = $data2['candidates'][0]['content']['parts'][0]['text'] ?? '';
                if (is_string($text2) && $text2 !== '') {
                    $text = trim($text . "\n" . trim($text2));
                }
            }
        }

        // 連続する重複段落を除去
        $text = preg_replace("/\r?\n/", "\n", $text ?? '');
        $paras = preg_split('/\n{2,}/', (string)$text);
        if (is_array($paras)) {
            $dedup = [];
            $prev = null;
            foreach ($paras as $p) {
                $t = trim((string)$p);
                if ($prev !== null && trim((string)$prev) === $t) {
                    continue;
                }
                $dedup[] = $p;
                $prev = $p;
            }
            $text = implode("\n\n", $dedup);
        }

        // 必要なら締めを付与
        if (mb_strpos($text, '— サンタより') === false) {
            $text = rtrim((string)$text) . "\n\n— サンタより";
        }

        if ($text === '') {
            throw new RuntimeException('Geminiからテキストが返ってきませんでした');
        }
        return $text;
    }
}
