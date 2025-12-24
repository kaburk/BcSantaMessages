<?php
declare(strict_types=1);

namespace BcSantaMessage\Model\Entity;

use Cake\ORM\Entity;

class SantaMessageSetting extends Entity
{
    protected array $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
