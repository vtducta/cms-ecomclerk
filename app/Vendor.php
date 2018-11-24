<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    //
     protected $fillable = [
        'id', 'title', 'minimum_purchase_amount', 'minimum_weight_amount', 'minimum_case_quantity'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorProducts()
    {
        return $this->hasMany(VendorProduct::class);
    }
}
