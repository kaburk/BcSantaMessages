<?php
declare(strict_types=1);

namespace BcSantaMessage\Service;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class SettingsService
{
    private const CACHE_KEY = 'bc_santa_message_settings';

    public function get(): array
    {
        $cached = Cache::read(self::CACHE_KEY, 'default');
        if (is_array($cached)) return $cached;

        $tbl = TableRegistry::getTableLocator()->get('BcSantaMessage.SantaMessageSettings');
        $row = $tbl->find()->orderDesc('modified')->first();

        $fallback = (array)Configure::read('BcSantaMessage');

        if (!$row) {
            // 旧Configure形式に合わせた最低限の形に整形
            $cfg = [
                'provider' => $fallback['provider'] ?? 'gemini',
                'generation' => [
                    'maxTokens' => (int)($fallback['generation']['maxTokens'] ?? 400),
                    'temperature' => (float)($fallback['generation']['temperature'] ?? 0.9),
                ],
                'rateLimitSeconds' => 10,
                'enabled' => true,
                'gemini' => $fallback['gemini'] ?? [],
                'ollama' => $fallback['ollama'] ?? [],
            ];
            Cache::write(self::CACHE_KEY, $cfg, 'default');
            return $cfg;
        }

        $cfg = [
            'provider' => (string)$row->provider,
            'generation' => [
                'maxTokens' => (int)$row->max_tokens,
                'temperature' => (float)$row->temperature,
            ],
            'rateLimitSeconds' => (int)$row->rate_limit_seconds,
            'enabled' => (bool)$row->enabled,
            'gemini' => [
                'apiKey' => (string)$row->gemini_api_key,
                'model' => (string)$row->gemini_model,
                'endpoint' => (string)$row->gemini_endpoint,
            ],
            'ollama' => [
                'baseUrl' => (string)$row->ollama_base_url,
                'model' => (string)$row->ollama_model,
            ],
        ];

        Cache::write(self::CACHE_KEY, $cfg, 'default');
        return $cfg;
    }

    public function clearCache(): void
    {
        Cache::delete(self::CACHE_KEY, 'default');
    }
}
