<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_domain',
        'integration_id',
        'access_token',
        'user_id'
    ];

    // Property to hold the access token
    protected $accessToken;

    /**
     * Constructor to initialize the Shop model with an access token.
     *
     * @param string|null $accessToken
     */
    public function __construct(string $accessToken = null)
    {
        parent::__construct();

        if ($accessToken) {
            $this->accessToken = $accessToken;
        }
    }

    /**
     * Get the access token.
     *
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }
}
