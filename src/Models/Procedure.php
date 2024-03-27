<?php

namespace Wepa\Procedures\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Wepa\Core\Http\Traits\Backend\PositionModelTrait;
use Wepa\Procedures\Database\Factories\ProcedureFactory;

/**
 * Wepa\Procedures\Models\Procedure
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $body
 * @property int $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Procedure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Procedure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Procedure query()
 * @method static \Illuminate\Database\Eloquent\Builder|Procedure whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Procedure whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Procedure whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Procedure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Procedure whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Procedure whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Procedure extends Model
{
    use HasFactory;
    use PositionModelTrait;

    protected $fillable = [
        'category_id',
        'name',
        'body',
        'position',
    ];

    protected $table = 'procedures_procedures';

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProcedureFile::class, 'procedure_id', 'id');
    }

    protected static function newFactory(): ProcedureFactory
    {
        return ProcedureFactory::new();
    }
}
