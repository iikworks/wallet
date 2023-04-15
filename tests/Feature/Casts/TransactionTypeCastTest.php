<?php

namespace Tests\Feature\Casts;

use App\Models\Account;
use App\Models\Organization;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class TransactionTypeCastTest extends TestCase
{
    use RefreshDatabase;

    public function test_err_type_must_exist(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $transaction = new Transaction();
        $transaction->account_id = Account::factory()->create()->id;
        $transaction->organization_id = Organization::factory()->create()->id;
        $transaction->type = 'wrong';
    }

    public function test_no_err(): void
    {
        $transaction = new Transaction();
        $transaction->account_id = Account::factory()->create()->id;
        $transaction->organization_id = Organization::factory()->create()->id;
        $transaction->type = Transaction::EXPENSE_TYPE;
        $transaction->amount = 500;
        $transaction->date = Carbon::now();
        $transaction->save();

        $this->assertEquals(Transaction::EXPENSE_TYPE, $transaction->type);
    }
}
