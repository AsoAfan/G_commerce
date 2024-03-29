<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

//    protected $hidden = ["pivot"];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'values')
            ->withPivot([
                'value',
                'display_type',
                'price',
                'image_path',
                'image_name',
                'price',
                'quantity',
                'currency'
            ]);
    }


}
