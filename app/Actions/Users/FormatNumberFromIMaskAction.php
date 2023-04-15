<?php

namespace App\Actions\Users;

readonly class FormatNumberFromIMaskAction
{
    public function __invoke(string $number): string
    {
        return str_replace(' ', '', $number);
    }
}
