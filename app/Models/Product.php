<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Product extends Model
{
    use HasFactory, Sluggable;

    //Status for soft delete of product
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;


    //Generate unique slugs for products
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'category_name',
        'brand_name',
        'status',
        'image_name',
        'image_path',
        'created_by',
        'updated_by'
    ];

    //Code for Filter
    public function scopeFilter($query, array $filters)
    {   
        //Filter based on category_name
        $query->when($filters['category_name'] ?? false, fn($query, $search) =>
            $query->where(fn($query) =>
                $query->where('category_name', 'like', '%' . $search . '%')
            )
        );

        //Filter based on brand_name
        $query->when($filters['brand_name'] ?? false, fn($query, $search) =>
            $query->where(fn($query) =>
                $query->where('brand_name', 'like', '%' . $search . '%')
            )
        );

        //Filter based on price
        $query->when($filters['min_price'] ?? false, fn($query, $search) => 
            $query->where(fn($query) => 
                $query->where('price', '>=', $search)
            )
        );

        $query->when($filters['max_price'] ?? false, fn($query, $search)=>
            $query->where(fn($query) => 
                $query->where('price', '<=', $search)
            )
        );

    }

}
