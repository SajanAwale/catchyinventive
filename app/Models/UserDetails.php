<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    //UserRolePivot
    protected $table = 'user_details';

    protected $fillable = [
        'user_id',
        'image',
        'phone',
        'last_login',
        'is_phone_verified',
        'is_email_send',
        'billing_country_code',
        'billing_country',
        'billing_address',
        'billing_address_optional',
        'billing_town_city',
        'billing_state',
        'billing_postcode',
        'billing_landmarks',
        'shipping_country_code',
        'shipping_country',
        'shipping_address',
        'shipping_address_optional',
        'shipping_town_city',
        'shipping_state',
        'shipping_postcode',
        'shipping_landmarks'
    ];
}
