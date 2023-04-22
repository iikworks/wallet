<?php

namespace App\Models;

use App\Casts\AccountBalanceCast;
use App\Casts\AccountDetailsCast;
use App\Casts\CurrencyCast;
use App\ValueObjects\Account\BankDetails;
use App\ValueObjects\Account\CardDetails;
use Database\Factories\AccountFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * App\Models\Account
 *
 * @property int $id
 * @property int $user_id
 * @property int $balance
 * @property string $currency
 * @property string $type
 * @property BankDetails|CardDetails|null $details
 * @method static Builder|Account|Collection|null query()
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read User $user
 * @method static AccountFactory factory($count = null, $state = [])
 * @method static Builder|Account newModelQuery()
 * @method static Builder|Account newQuery()
 * @method static Builder|Account whereBalance($value)
 * @method static Builder|Account whereCreatedAt($value)
 * @method static Builder|Account whereCurrency($value)
 * @method static Builder|Account whereDetails($value)
 * @method static Builder|Account whereId($value)
 * @method static Builder|Account whereType($value)
 * @method static Builder|Account whereUpdatedAt($value)
 * @method static Builder|Account whereUserId($value)
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
