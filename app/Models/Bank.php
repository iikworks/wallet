<?php

namespace App\Models;

use Database\Factories\BankFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * App\Models\Bank
 *
 * @property int $id
 * @property string $title
 * @method static Builder|Bank|Collection|null query()
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static BankFactory factory($count = null, $state = [])
 * @method static Builder|Bank newModelQuery()
 * @method static Builder|Bank newQuery()
 * @method static Builder|Bank whereCreatedAt($value)
 * @method static Builder|Bank whereId($value)
 * @method static Builder|Bank whereTitle($value)
 * @method static Builder|Bank whereUpdatedAt($value)
 */
class Bank extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
    ];
}
