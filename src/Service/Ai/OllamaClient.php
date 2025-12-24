<?php
declare(strict_types=1);

namespace BcSantaMessage\Service\Ai;

use Cake\Http\Client;
use RuntimeException;

class OllamaClient implements AiClientInterface
{
    public function __construct(private array $settings = [])
    {
    }

    public function generate(string $prompt, array $options = []): string
    {
        $cfg = $this->settings['ollama'] ?? [];
        $baseUrl = rtrim((string)($cfg['baseUrl'] ?? 'http://127.0.0.1:11434'), '/');
        $model = (string)($cfg['model'] ?? 'llama3.1');

        $gen = $this->settings['generation'] ?? [];
        $temperature = (float)($options['temperature'] ?? $gen['temperature'] ?? 0.9);

        $http = new Client(['timeout' => 30]);

        $payload = [
            'model' => $model,
            'prompt' => $prompt,
            'stream' => false,
            'options' => [
                'temperature' => $temperature,
            ]
        ];

        $res = $http->post($baseUrl . '/api/generate', json_encode($payload, JSON_UNESCAPED_UNICODE), [
            'type' => 'json',
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        if (!$res->isOk()) {
            throw new RuntimeException('Ollama error: ' . $res->getStatusCode() . ' ' . $res->getStringBody());
        }

        $data = $res->getJson();
        $text = $data['response'] ?? '';
        $text = is_string($text) ? trim($text) : '';

        if ($text === '') {
            throw new RuntimeException('Ollamaからテキストが返ってきませんでした');
        }
        return $text;
    }
}
