<?php
declare(strict_types=1);

namespace BcSantaMessage\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class SantaMessageForm extends Form
{
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('child_name', 'string')
            ->addField('age', 'integer')
            ->addField('good_thing', 'string')
            ->addField('gift_hint', 'string')
            ->addField('tone', 'string');
    }

    public function validationDefault(Validator $validator): Validator
    {
        return $validator
            ->requirePresence('child_name', true)
            ->notEmptyString('child_name', '名前は必須です')
            ->integer('age')
            ->range('age', [1, 120], '年齢が不正です')
            ->allowEmptyString('good_thing')
            ->allowEmptyString('gift_hint')
            ->allowEmptyString('tone');
    }
}
