<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'brand',
        'description',
        'price',
        'image',
        'tags',
        'category',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function categoryModel()
    {
        return $this->belongsTo(Category::class, 'category', 'slug');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function getTagsArrayAttribute(): array
    {
        return array_map('trim', explode(',', $this->tags ?? ''));
    }
}
