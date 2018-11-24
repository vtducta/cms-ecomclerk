<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class MikeShopifyOrder extends Model
{
    protected $table = 'mike_shopify_orders';
    protected $fillable = [
        'store_order_id', 'ship_to_name', 'total_amount', 'item_total', 'shipping_charge', 'discount', 'item_cost', 'actual_shipping_charge', 'user_id'
    ];

    public function user()
    {
        $this->belongsTo( '\App\User');
    }
}
