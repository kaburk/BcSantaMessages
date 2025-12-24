<?php
declare(strict_types=1);

/**
 * Plugin config for baserCMS
 * - baserCMSのプラグイン一覧表示などで使われる想定のメタ情報
 * - 実際の参照キーは環境差があり得るので、BcPluginSample に合わせて調整しやすい形にしています
 */

return [
    'type' => 'Plugin',
    'title' => 'BcSantaMessage',
    'description' => '生成AIでサンタからのメッセージを自動生成するプラグイン（Gemini / Ollama対応）',
    'author' => 'Your Name',
    'url' => 'https://example.com',
    'adminLink' => '/bc-santa-message/admin/santa-message', // 管理画面設定ページ
    'installMessage' => '有効化後、マイグレーションを実行して管理画面からAPI設定を行ってください。',
];
