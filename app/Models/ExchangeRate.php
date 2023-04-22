<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * App\Models\ExchangeRate
 *
 * @property int $id
 * @property string $from
 * @property string $to
 * @property int $rate
 * @method static Builder|ExchangeRate|Collection|null query()
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ExchangeRate newModelQuery()
 * @method static Builder|ExchangeRate newQuery()
 * @method static Builder|ExchangeRate whereCreatedAt($value)
 * @method static Builder|ExchangeRate whereFrom($value)
 * @method static Builder|ExchangeRate whereId($value)
 * @method static Builder|ExchangeRate whereRate($value)
 * @method static Builder|ExchangeRate whereTo($value)
 * @method static Builder|ExchangeRate whereUpdatedAt($value)
 */
class ExchangeRate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'from',
        'to',
        'rate',
    ];
}
