<?php

namespace App\Models;

use App\Casts\AmountCast;
use App\Casts\CurrencyCast;
use App\Casts\SubscriptionDayCast;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $account_id
 * @property int $organization_id
 * @property int $amount
 * @property string $currency
 * @property int $day
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
