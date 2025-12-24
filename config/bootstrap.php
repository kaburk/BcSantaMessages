<?php
declare(strict_types=1);

use Cake\Core\Configure;

Configure::write('BcSantaMessage', [
    // provider: gemini | ollama
    'provider' => env('BC_SANTA_PROVIDER', 'gemini'),

    // Gemini
    'gemini' => [
        'apiKey' => env('GEMINI_API_KEY', ''),
        'model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
        'endpoint' => env('GEMINI_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models'),
    ],

    // Ollama (local)
    'ollama' => [
        'baseUrl' => env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434'),
        'model' => env('OLLAMA_MODEL', 'llama3.1'),
    ],

    'generation' => [
        'maxTokens' => (int)env('BC_SANTA_MAX_TOKENS', '400'),
        'temperature' => (float)env('BC_SANTA_TEMPERATURE', '0.9'),
    ],
]);
