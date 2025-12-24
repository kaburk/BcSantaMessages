<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateSantaSettings extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('bc_santa_message_settings', [
            'id' => false,
            'primary_key' => ['id'],
        ]);

        $table
            ->addColumn('id', 'integer', ['autoIncrement' => true])
            ->addColumn('provider', 'string', ['limit' => 30, 'default' => 'gemini'])
            ->addColumn('gemini_api_key', 'string', ['limit' => 255, 'default' => ''])
            ->addColumn('gemini_model', 'string', ['limit' => 100, 'default' => 'gemini-2.0'])
            ->addColumn('gemini_endpoint', 'string', ['limit' => 255, 'default' => 'https://generativelanguage.googleapis.com/v1/models'])
            ->addColumn('ollama_base_url', 'string', ['limit' => 255, 'default' => 'http://127.0.0.1:11434'])
            ->addColumn('ollama_model', 'string', ['limit' => 100, 'default' => 'llama3'])
            ->addColumn('max_tokens', 'integer', ['default' => 400])
            ->addColumn('temperature', 'decimal', ['precision' => 3, 'scale' => 2, 'default' => 0.90])
            ->addColumn('rate_limit_seconds', 'integer', ['default' => 10])
            ->addColumn('enabled', 'boolean', ['default' => true])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();
    }
}
