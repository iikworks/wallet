<?php

namespace Tests\Feature\Actions\Banks;

use App\Actions\Banks\DestroyBankAction;
use App\Models\Bank;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyBankActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cant_destroy_not_exist_bank()
    {
        $this->expectException(ModelNotFoundException::class);

        (new DestroyBankAction)(1);
    }

    public function test_can_destroy_bank()
    {
        $bank = Bank::factory()->create();

        (new DestroyBankAction)($bank->id);

        $this->assertEquals(0, Bank::query()->count());
    }
}
