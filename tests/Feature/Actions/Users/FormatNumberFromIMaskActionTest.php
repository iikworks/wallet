<?php

namespace Tests\Feature\Actions\Users;

use App\Actions\Users\FormatNumberFromIMaskAction;
use Tests\TestCase;

class FormatNumberFromIMaskActionTest extends TestCase
{
    public function test_format_number(): void
    {
        $number = '+375 34 2352545';

        $this->assertEquals('+375342352545', (new FormatNumberFromIMaskAction)($number));
    }
}
