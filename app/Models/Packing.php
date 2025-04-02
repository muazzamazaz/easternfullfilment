<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packing extends Model
{
    protected $fillable =[
        "width",
        "weight",
        "name", "is_active",  "height",  "unit_id", "size"
      
    ];
    
}
