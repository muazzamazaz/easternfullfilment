<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biller extends Model
{
    protected $fillable =[
        "name", "woo_customer_id","integration_id","image", "company_name", "vat_number",
        "email", "phone_number", "address", "city",
        "state", "postal_code", "country", "is_active"
    ];

    public function sale()
    {
    	return $this->hasMany('App\Models\Sale');
    }
}
