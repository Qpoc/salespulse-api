<?php

namespace App\Models\Variants;

use Illuminate\Database\Eloquent\Model;

class VariantLabel extends Model
{
    protected $fillable = [
        'label',
        'description',
    ];
}
