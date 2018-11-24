<?php

namespace App\Http\Controllers;

use App\Modules\Products\Product;
use App\Modules\Products\ProductRepository;
use MarcL\AmazonAPI;
use MarcL\AmazonUrlBuilder;

class CornController extends Controller
{
    private $products;

    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function amazon(ProductRepository $productRepository)
    {
        $products = $productRepository->getNoPrimeProducts(5);
        if ($products->count() > 0) {

            $urlBuilder = new AmazonUrlBuilder(
                config('api.amazon.key'),
                config('api.amazon.secret'),
                config('api.amazon.associateId'),
                'us'
            );
            $amazonAPI = new AmazonAPI($urlBuilder, 'array');
            $asinIds = $products->pluck('asin')->toArray();
            $items = $amazonAPI->ItemLookUp($asinIds);

            dd('Items', $items);

            foreach ($items as $product) {
                $eligibility = null;
                if (isset($product['IsEligibleForPrime'])) {
                    $eligibility = 0;
                    if ($product['IsEligibleForPrime'] != '') {
                        $eligibility = $product['IsEligibleForPrime'];
                    }
                }
                Product::where('asin', '=', $product['asin'])->update([
                    'is_eligible_for_prime' => $eligibility,
                ]);
            }
        }
        return response("OK");
    }
}
