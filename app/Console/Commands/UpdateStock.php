<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ProductFBA;
use DB;
class UpdateStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateStock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update stock';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $header = null;
        $data = array();

        $conn_id = ftp_connect('12.178.78.235');
        ftp_pasv($conn_id, true);
        $login_result = ftp_login($conn_id, 'daily_file_access', "dafiftp01!");
        if (ftp_get($conn_id, public_path() . '/productfeed.csv', '/DailyFiles/productfeed.csv', FTP_BINARY))
        {
            if (($handle = fopen(public_path() . '/productfeed.csv', 'r')) !== false) {
                while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
                    if (!$header) {
                        $header = $row;
                    } else {
                        if (count($row) > count($header)) {
                            array_pop($row);
                        }
                        $data[] = array_combine($header, $row);

                    }
                }
            }
            $our_products = DB::table('mike_products_amazon')->select('upc')->get();
            foreach ($our_products as $our_product)
            {
                $upcs[] = $our_product->upc;
            }

            $our_products_shopify = DB::table('mike_products_shopify')->select('sku')->get();
            foreach ($our_products_shopify as $our_product_shopify)
            {
                $sku = str_replace("HG", "", $our_product_shopify->sku);
                $shopify_skus[] = $sku;
            }

            foreach (array_chunk($data, 5000) as $arr_chunk)
            {
                foreach ($arr_chunk as $line)
                {
                    if (in_array($line['Upc'], $upcs))
                    {
                        file_put_contents(public_path() . '/price_update_log.txt', 'Update price for UPC ' . $line['Upc'] . "\r\n", FILE_APPEND);
                        $total_qty = (int) ($line['PHI On Hand']) + (int) ($line['HVA On Hand']) + (int) ($line['RACOnHand']);
                        $shipping_cost = calculate_shipping_price($line['Weight (lbs)']);
                        $safety_qty = $total_qty / 2;
                        $site_price = calculate_price($line['Base Wholesale'], $shipping_cost);
                        $price_min = ($site_price < 5) ? $site_price - 0.2 : $site_price - 1;
                        $price_max = $site_price + 50;
                        DB::table('mike_products_amazon')->where('upc', $line['Upc'])->update([
                            'qty' => $safety_qty,
                            'site_price' => $site_price,
                            'price_min' => $price_min,
                            'price_max' => $price_max,
                            'cost' => number_format($line['Base Wholesale'], 2),
                        ]);
                    }

                    if (in_array($line['UNFI Product #'], $shopify_skus))
                    {
                        $total_qty = (int) ($line['PHI On Hand']) + (int) ($line['HVA On Hand']) + (int) ($line['RACOnHand']);
                        $site_price = calculate_price_shopify($line['Etailer Price After Discounts']);
                        DB::table('mike_products_shopify')->where('sku','LIKE','%' . $line['UNFI Product #'])->update([
                            'qty' => $total_qty,
                            'site_price' => $site_price,
                            'cost' => number_format($line['Base Wholesale'], 2),
                            'upc' => $line['Upc']
                        ]);
                    }
                }
            }

            $products = DB::table('mike_products_amazon')->get();
            $shopify_products = DB::table('mike_products_shopify')->get();

            file_put_contents(public_path() . '/output/sa_upload.csv', "InventoryAction,Site,SellerSKU,Quantity,Price,Price (Minimum),Price (Maximum)\r\n");
            file_put_contents(public_path() . '/output/sa_upload_shopify.csv', "InventoryAction,Site,SellerSKU,Quantity,Price\r\n");

            foreach ($products as $product)
            {
                file_put_contents(public_path() . '/output/sa_upload.csv', 'Modify,Amazon,' . $product->sku . "," . $product->qty . "," . $product->site_price . "," . $product->price_min . "," . $product->price_max . "\r\n", FILE_APPEND);
            }

            foreach ($shopify_products as $shopify_product)
            {
                file_put_contents(public_path() . '/output/sa_upload_shopify.csv', 'Modify,Shopify,' . $shopify_product->sku . "," . $shopify_product->qty . "," . $shopify_product->site_price . "\r\n", FILE_APPEND);
            }
        }
    }
}
