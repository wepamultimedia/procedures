<?php

namespace Wepa\Procedures\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Wepa\Procedures\Models\ProcedureFile
 *
 * @property int $id
 * @property int $procedure_id
 * @property string $name
 * @property string $file_url
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProcedureFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProcedureFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProcedureFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProcedureFile whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProcedureFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProcedureFile whereProcedureId($value)
 *
 * @mixin \Eloquent
 */
class ProcedureFile extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'procedures_files';

    protected $fillable = [
        'procedure_id',
        'name',
        'file_url',
    ];
}
