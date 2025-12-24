<?php
declare(strict_types=1);

namespace BcSantaMessage\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class SantaMessagesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('santa_messages');
        $this->setPrimaryKey('id');
        $this->setDisplayField('child_name');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('child_name')->maxLength('child_name', 60)->requirePresence('child_name')->notEmptyString('child_name')
            ->integer('age')->allowEmptyString('age')
            ->scalar('good_thing')->maxLength('good_thing', 120)->allowEmptyString('good_thing')
            ->scalar('gift_hint')->maxLength('gift_hint', 120)->allowEmptyString('gift_hint')
            ->scalar('tone')->maxLength('tone', 120)->allowEmptyString('tone')
            ->scalar('provider')->maxLength('provider', 40)->allowEmptyString('provider')
            ->scalar('model')->maxLength('model', 80)->allowEmptyString('model')
            ->scalar('message')->notEmptyString('message');
        return $validator;
    }
}
