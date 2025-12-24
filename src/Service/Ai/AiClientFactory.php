<?php
declare(strict_types=1);

namespace BcSantaMessage\Service\Ai;

use BcSantaMessage\Service\SettingsService;
use RuntimeException;

class AiClientFactory
{
    public static function create(): AiClientInterface
    {
        $settings = (new SettingsService())->get();
        $provider = $settings['provider'] ?? 'gemini';

        return match ($provider) {
            'gemini' => new GeminiClient($settings),
            'ollama' => new OllamaClient($settings),
            default => throw new RuntimeException("Unsupported provider: {$provider}"),
        };
    }
}
