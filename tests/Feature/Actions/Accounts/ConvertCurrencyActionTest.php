<?php

namespace Tests\Feature\Actions\Accounts;

use App\Actions\Accounts\ConvertCurrencyAction;
use App\Models\ExchangeRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class ConvertCurrencyActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_input_currency_must_be_right(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ExchangeRate::query()->create([
            'from' => 'USD',
            'to' => 'BYN',
            'rate' => 3000,
        ]);

        (new ConvertCurrencyAction)('wrong', 'BYN', 5000);
    }

    public function test_output_currency_must_be_right(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ExchangeRate::query()->create([
            'from' => 'USD',
            'to' => 'BYN',
            'rate' => 3000,
        ]);

        (new ConvertCurrencyAction)('USD', 'wrong', 5000);
    }

    public function test_successful_convert(): void
    {
        ExchangeRate::query()->create([
            'from' => 'USD',
            'to' => 'BYN',
            'rate' => 4000,
        ]);

        $result = (new ConvertCurrencyAction)('USD', 'BYN', 5000);
        $this->assertEquals(12500, $result);
    }
}
