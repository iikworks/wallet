<?php

namespace App\Models;

use App\Casts\AmountCast;
use App\Casts\TransactionTypeCast;
use Carbon\Carbon;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $account_id
 * @property int $organization_id
 * @property string $type
 * @property int $amount
 * @property Carbon $date
 * @property Account $account
 * @property Organization $organization
 * @method static Builder|Transaction|Collection|null query()
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static TransactionFactory factory($count = null, $state = [])
 * @method static Builder|Transaction newModelQuery()
 * @method static Builder|Transaction newQuery()
 * @method static Builder|Transaction whereAccountId($value)
 * @method static Builder|Transaction whereAmount($value)
 * @method static Builder|Transaction whereCreatedAt($value)
 * @method static Builder|Transaction whereDate($value)
 * @method static Builder|Transaction whereId($value)
 * @method static Builder|Transaction whereOrganizationId($value)
 * @method static Builder|Transaction whereType($value)
 * @method static Builder|Transaction whereUpdatedAt($value)
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
