<?php

namespace App\Models;

use App\Casts\AccountBalanceCast;
use App\Casts\AccountDetailsCast;
use App\Casts\CurrencyCast;
use App\ValueObjects\Account\BankDetails;
use App\ValueObjects\Account\CardDetails;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $user_id
 * @property int $balance
 * @property string $currency
 * @property string $type
 * @property BankDetails|CardDetails|null $details
 * @method static Builder|Account|Collection|null query()
 */
class Account extends Model
{
    use HasFactory;

    public const CASH_TYPE = 'cash';
    public const BANK_ACCOUNT_TYPE = 'bank account';
    public const CARD_TYPE = 'card';
    public const SYSTEMS = ['Visa', 'MasterCard'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'balance',
        'currency',
        'details',
    ];

    protected $casts = [
        'balance' => AccountBalanceCast::class,
        'currency' => CurrencyCast::class,
        'details' => AccountDetailsCast::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
