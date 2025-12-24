<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateSantaMessages extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('santa_messages');
        $table
            ->addColumn('child_name', 'string', ['limit' => 60, 'null' => false])
            ->addColumn('age', 'integer', ['null' => true])
            ->addColumn('good_thing', 'string', ['limit' => 120, 'null' => true])
            ->addColumn('gift_hint', 'string', ['limit' => 120, 'null' => true])
            ->addColumn('tone', 'string', ['limit' => 120, 'null' => true])
            ->addColumn('message', 'text', ['null' => false])
            ->addColumn('provider', 'string', ['limit' => 40, 'null' => true])
            ->addColumn('model', 'string', ['limit' => 80, 'null' => true])
            ->addColumn('client_ip', 'string', ['limit' => 64, 'null' => true])
            ->addColumn('user_agent', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->create();
    }
}
