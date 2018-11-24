<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFBAHistory extends Model
{
    protected $table = 'product_fba_history';
    protected $fillable = [
        'id', 'product_fba_id','title', 'amazon_category', 'brand', 'buy_box', 'asin', 'upc', 'pack_unit', 'cost', 'buy_box_price', 'buy_box_share', 'fba_fee', 'profit', 'estimated_monthly_sales'
    ];
}
