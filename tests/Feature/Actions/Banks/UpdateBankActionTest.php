<?php

namespace Tests\Feature\Actions\Banks;

use App\Actions\Banks\UpdateBankAction;
use App\Models\Bank;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateBankActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cant_update_not_exist_bank(): void
    {
        $this->expectException(ModelNotFoundException::class);

        (new UpdateBankAction)(1, [
            'title' => 'Bank',
        ]);
    }

    public function test_can_update_bank(): void
    {
        $bank = Bank::factory()->create();

        (new UpdateBankAction)($bank->id, [
            'title' => 'Bank',
        ]);

        tap(Bank::query()->first(), function (Bank $bank) {
            $this->assertEquals('Bank', $bank->title);
        });
    }
}
