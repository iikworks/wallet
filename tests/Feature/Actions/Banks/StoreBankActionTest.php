<?php

namespace Tests\Feature\Actions\Banks;

use App\Actions\Banks\StoreBankAction;
use App\Models\Bank;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreBankActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_a_new_bank(): void
    {
        (new StoreBankAction)([
            'title' => 'Bank',
        ]);

        $this->assertEquals(1, Bank::query()->count());

        tap(Bank::query()->first(), function (Bank $bank) {
            $this->assertEquals('Bank', $bank->title);
        });
    }
}
