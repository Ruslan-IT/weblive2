<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'old_price',
        'is_visible',
        'is_featured',
        'is_hit',
        'is_sale',
        'photo',
        'photos',
        'content_blocks',

    ];


    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'photos' => 'array',
            'content_blocks' => 'array',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
