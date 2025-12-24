<?php
declare(strict_types=1);

namespace BcSantaMessage\Service\Ai;

interface AiClientInterface
{
    public function generate(string $prompt, array $options = []): string;
}
