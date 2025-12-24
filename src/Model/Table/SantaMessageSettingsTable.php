<?php
declare(strict_types=1);

namespace BcSantaMessage\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class SantaMessageSettingsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('bc_santa_message_settings');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('provider')->inList('provider', ['gemini', 'ollama'], 'providerが不正です')
            ->boolean('enabled')
            ->integer('max_tokens')->range('max_tokens', [50, 2000], 'max_tokensは50〜2000')
            ->decimal('temperature')->range('temperature', [0, 2], 'temperatureは0〜2')
            ->integer('rate_limit_seconds')->range('rate_limit_seconds', [0, 3600], 'レート制限は0〜3600');

        return $validator;
    }
}
