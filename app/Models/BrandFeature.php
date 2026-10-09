<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandFeature extends Model
{
    protected $fillable = ['title', 'description', 'icon', 'sort_order', 'is_active'];
}
