<?php

namespace App\Models;

use App\Casts\AmountCast;
use App\Casts\TransactionTypeCast;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $account_id
 * @property int $organization_id
 * @property string $type
 * @property int $amount
 * @property Carbon $date
 * @property Account $account
 * @property Organization $organization
 * @method static Builder|Transaction|Collection|null query()
 */
class Transaction extends Model
{
    use HasFactory;

    public const EXPENSE_TYPE = 'expense';
    public const REPLENISHMENT_TYPE = 'replenishment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_id',
        'organization_id',
        'type',
        'amount',
        'date',
    ];
    protected $casts = [
        'type' => TransactionTypeCast::class,
        'amount' => AmountCast::class,
        'date' => 'datetime',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
