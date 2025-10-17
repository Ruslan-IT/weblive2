<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['title', 'slug', 'parent_id', 'description', 'photo'];


    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }


    public static function getCategoriesTree($categories, $parentId = null, $depth = 0): array
    {
        $options = [];
        foreach ($categories->where('parent_id', $parentId) as $category) {
            $prefix = str_repeat('- ', $depth);
            $options[$category->id] = $prefix . $category->title;
            $children = self::getCategoriesTree($categories, $category->id, $depth + 1);
            $options += $children;
        }
        return $options;
    }
}
