<?php

namespace Modules\Category\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Category\Database\factories\SubCategoryFactory;

class SubCategory extends Model
{
   protected $fillable = ['category_id', 'name', 'slug', 'status'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
