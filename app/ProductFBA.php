<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFBA extends Model
{
    protected $table = 'product_fba';
    protected $fillable = [
        'id', 'title', 'sku', 'sale_30day', 'qty_available', 'inbound_qty', 'restock_qty', 'restock_date', 'restock_status', 'amazon_category', 'brand', 'buy_box', 'asin', 'upc', 'pack_unit', 'cost', 'buy_box_price', 'buy_box_share', 'fba_fee', 'profit', 'estimated_monthly_sales'
    ];
}
