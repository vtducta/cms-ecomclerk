<?php
namespace App\Modules\Products;

use App\Modules\Products\Product;
use League\Fractal;
use League\Fractal\TransformerAbstract;

class DatatableTransformer extends TransformerAbstract
{
    public function transform(Product $product){
        return [
            'title' => '<a href='.sysRoute("products.show", encryptIt($product->id)). '>'.$product->amazon_title.'</a>',
            'asin' =>  $product->asin,
            'number_of_packs' =>  $product->number_of_packs,
            'cost' => $product->cost,
            'pack_cost' => number_format($product->pack_cost,2),
            'amazon_buy_box_price' => $product->amazon_buy_box_price,
            'net_after_fba' => number_format($product->net_after_fba,2),
            'gross_profit_fba' => number_format($product->gross_profit_fba,2),
            'gross_roi' => number_format($product->gross_roi,2) .'%',
            'sales' => intval($product->sales),
            'mark_po' => $product->po_status
        ];
    }
}