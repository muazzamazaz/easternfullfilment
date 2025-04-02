<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductIntegration extends Model
{
	protected $table = 'product_integration';
    protected $fillable =[

        "integration_id", "domain", "access_token"
    ];
}
