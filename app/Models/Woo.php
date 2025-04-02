<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Woo extends Model
{
    use HasFactory;
    
    protected $table="woocommerce";

    protected $fillable = [
        'woocommerce_domain',
        'consumer_key',
        'consumer_secret',
        'integration_id',
        'user_id'
    ];

    // Property to hold the access token
    protected $consumer_key;
    protected $secret_key;

    public function __construct(string $consumer_key = null,string $secret_key = null)
    {
        parent::__construct();

        if ($consumer_key) {
            $this->consumer_key = $consumer_key;
        }

        if ($secret_key) {
            $this->secret_key = $secret_key;
        }
    }

    public function getConsumerKey(): ?string
    {
        return $this->consumer_key;
    }

    public function getSecretKey(): ?string
    {
        return $this->secret_key;
    }
}
