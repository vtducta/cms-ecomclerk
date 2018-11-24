<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorProduct extends Model
{
    protected $fillable = [
        'id', 'vendor_id', 'product_title', 'vendor_item_number', 'upc', 'vendor_cost', 'case_quantity', 'weight', 'category'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendorProducts()
    {
        return $this->belongsTo(Vendor::class);
    }
}
