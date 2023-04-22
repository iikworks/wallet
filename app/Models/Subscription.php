<?php

namespace App\Models;

use App\Casts\AmountCast;
use App\Casts\CurrencyCast;
use App\Casts\SubscriptionDayCast;
use Carbon\Carbon;
use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * App\Models\Subscription
 *
 * @property int $id
 * @property int $account_id
 * @property int $organization_id
 * @property int $amount
 * @property string $currency
 * @property int $day
 * @method static Builder|Subscription|Collection|null query()
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Account $account
 * @property-read Organization $organization
 * @method static SubscriptionFactory factory($count = null, $state = [])
 * @method static Builder|Subscription newModelQuery()
 * @method static Builder|Subscription newQuery()
 * @method static Builder|Subscription whereAccountId($value)
 * @method static Builder|Subscription whereAmount($value)
 * @method static Builder|Subscription whereCreatedAt($value)
 * @method static Builder|Subscription whereCurrency($value)
 * @method static Builder|Subscription whereDay($value)
 * @method static Builder|Subscription whereId($value)
 * @method static Builder|Subscription whereOrganizationId($value)
 * @method static Builder|Subscription whereUpdatedAt($value)
 */
class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_id',
        'organization_id',
        'amount',
        'currency',
        'day',
    ];
    protected $casts = [
        'day' => SubscriptionDayCast::class,
        'amount' => AmountCast::class,
        'currency' => CurrencyCast::class,
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function daysBeforePayment(): int
    {
        $now = Carbon::now();

        if ($now->day > $this->day) {
            $deadline = Carbon::now()->addMonth()->setDay($this->day);
            return $deadline->diffInDays($now);
        } else if ($now->day < $this->day) {
            return $this->day - $now->day;
        }

        return 0;
    }
}
