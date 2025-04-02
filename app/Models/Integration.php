<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    protected $fillable =[

        "name", "is_active"
    ];

    public function woo()
    {
        return $this->hasMany('App\Models\Woo', 'integration_id'); // Make sure 'integration_id' is correct
    }

    public function shop()
    {
        return $this->hasMany('App\Models\Shop', 'integration_id'); // Adjust 'integration_id' if necessary
    }
    
}
