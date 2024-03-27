<?php

namespace Wepa\Procedures\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Wepa\Core\Http\Traits\Backend\PositionModelTrait;
use Wepa\Procedures\Database\Factories\CategoryFactory;

/**
 * Wepa\Procedures\Models\Category
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string $image
 * @property int $position
 * @property array $fillables
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Category extends Model
{
    use HasFactory;
    use PositionModelTrait;

    private static array $_categories = [];

    protected $table = 'procedures_categories';

    protected $fillable = [
        'parent_id',
        'name',
        'image',
        'position',
    ];

    public static function fillables(string $separator = '/'): array
    {
        $categories = self::categoriesArray();

        $fillableCategories = [];

        foreach ($categories as $category) {
            if (! self::hasChildren($category['id'])) {
                $parents = self::getParents($category);
                $parentsNames = Arr::pluck($parents, 'name');
                $parentsNames[] = $category['name'];
                $category['label'] = Arr::join($parentsNames, $separator);

                $fillableCategories[] = Arr::only($category, ['id', 'label']);
            }
        }

        return $fillableCategories;
    }

    public static function categoriesArray(): array
    {
        if (! self::$_categories) {
            return self::$_categories = self::orderBy('position')->get()->toArray();
        }

        return self::$_categories;
    }

    public static function hasProcedures(int $id)
    {
        return (bool) Procedure::whereCategoryId($id)->count();
    }

    public static function hasChildren(int $id = null): bool
    {
        if (! $id) {
            $id = self::$id;
        }

        $categories = self::categoriesArray();

        foreach ($categories as $category) {
            if ($category['parent_id'] === $id) {
                return true;
            }
        }

        return false;
    }

    public static function getParents(array $category, array &$parents = [], bool $reverse = true): array
    {
        $categories = self::categoriesArray();

        if ($category['parent_id'] !== null) {
            foreach ($categories as $cat) {
                if ($cat['id'] === $category['parent_id']) {
                    $parents[] = $cat;
                    if ($cat['parent_id']) {
                        self::getParents($cat, $parents, false);
                    }
                }
            }
        }
        if ($reverse) {
            $parents = array_reverse($parents);
        }

        return $parents;
    }

    public function label(): Attribute
    {
        return Attribute::make(
            get: function () {
                $parents = self::getParents($this->toArray());
                $parentsNames = Arr::pluck($parents, 'name');
                $parentsNames[] = $this->name;

                return Arr::join($parentsNames, '/');
            }
        );
    }

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

    public function procedures(): HasMany
    {
        return $this->hasMany(Procedure::class, 'category_id', 'id');
    }
}
