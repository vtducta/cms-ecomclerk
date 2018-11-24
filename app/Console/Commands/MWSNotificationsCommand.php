<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use AWS;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessMWSNotification;
use App\Modules\Products\Product;
use App\Modules\Products\ProductHistory;
use App\ProductFBA;
use DB;

class MWSNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MWSNotificationsCommand:mwsnotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to receive MWS notifications';

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
        //
        $client = AWS::createClient('sqs');
        $queueUrl = 'https://sqs.us-east-1.amazonaws.com/274483557371/ecom';
        try {
            $result = $client->receiveMessage(array(
                'AttributeNames' => ['SentTimestamp'],
                'MaxNumberOfMessages' => 10,
                'MessageAttributeNames' => ['All'],
                'QueueUrl' => $queueUrl, // REQUIRED
                'WaitTimeSeconds' => 0,
            ));
            if (count($result->get('Messages')) > 0) {

                ProcessMWSNotification::dispatch($result->get('Messages'));

                /* converting xml data into array*/
                if (is_array($result->get('Messages'))) {
                    $messageArr = $result->get('Messages');
                    $xmlstring = $messageArr[0]['Body'];
                    $xml = simplexml_load_string($xmlstring);
                    $json = json_encode($xml);
                    $array = json_decode($json, TRUE);
                }

                /*if($array)
                    file_put_contents('/home/ecomclerk/dev.ecomclerk.com/customLogs/messageReceived' . time() . '.txt', print_r($array, TRUE));*/

                /* mentaining history of products and updating sales rank in products table*/
                /*if (is_array($array) && (isset($array['NotificationPayload']) && isset($array['NotificationPayload']['AnyOfferChangedNotification']) && isset($array['NotificationPayload']['AnyOfferChangedNotification']['OfferChangeTrigger']) && isset($array['NotificationPayload']['AnyOfferChangedNotification']['OfferChangeTrigger']['ASIN']) && isset($array['NotificationPayload']['AnyOfferChangedNotification']['Summary']) && isset($array['NotificationPayload']['AnyOfferChangedNotification']['Summary']['SalesRankings']) && isset($array['NotificationPayload']['AnyOfferChangedNotification']['Summary']['SalesRankings']['SalesRank']) && isset($array['NotificationPayload']['AnyOfferChangedNotification']['Summary']['SalesRankings']['SalesRank'][0]) && isset($array['NotificationPayload']['AnyOfferChangedNotification']['Summary']['SalesRankings']['SalesRank'][0]['Rank']))) {
                    $asin = $array['NotificationPayload']['AnyOfferChangedNotification']['OfferChangeTrigger']['ASIN'];
                    $rank = $array['NotificationPayload']['AnyOfferChangedNotification']['Summary']['SalesRankings']['SalesRank'][0]['Rank'];
                    //$buybox_price = $array['NotificationPayload']['AnyOfferChangedNotification']['BuyBoxPrices']['BuyBoxPrice']['LandedPrice']['Amount'];
                    $buybox_price = 0;
                    $productPreviousInfo = DB::table('product_fba')->where('asin', $asin)->first();
                    if ($productPreviousInfo)
                        $productArr = (array)$productPreviousInfo;
                    /* Inserting product info to products_history table*/
                    /*DB::table('products_history')
                        ->insert(array(
                        'product_id' => $productArr['id'],
                        'title' => $productArr['id'],
                        'cost' => $productArr['cost'],
                        'amazon_title' => $productArr['amazon_title'],
                        'brand' => $productArr['brand'],
                        'asin' => $productArr['asin'],
                        'buybox_win' => $productArr['buybox_win'],
                        'number_of_sellers' => $productArr['number_of_sellers'],
                        'weight' => $productArr['weight'],
                        'sales_rank' => $productArr['sales_rank'],
                        'sales_rank_30' => $productArr['sales_rank_30'],
                        'sales_rank_90' => $productArr['sales_rank_90'],
                        'reviews' => $productArr['reviews'],
                        'ratings' => $productArr['ratings'],
                        'sales' => $productArr['sales'],
                        'amazon_upc_ean' => $productArr['amazon_upc_ean'],
                        'upc_ean' => $productArr['upc_ean'],
                        'amazon_buy_box_price' => $productArr['amazon_buy_box_price'],
                        'net_after_fba' => $productArr['net_after_fba'],
                        'pack_cost' => $productArr['pack_cost'],
                        'number_of_packs' => $productArr['number_of_packs'],
                        'gross_profit_fba' => $productArr['gross_profit_fba'],
                        'gross_roi' => $productArr['gross_roi'],
                        'is_eligible_for_prime' => $productArr['is_eligible_for_prime'],
                        'profit' => $productArr['profit'],
                        'po_status' => $productArr['po_status'],
                        'reason' => $productArr['reason'],
                        'number_of_prime_sellers' => $productArr['number_of_prime_sellers'],
                        'quantity_buy_in' => $productArr['quantity_buy_in'],
                        'created_at' => $productArr['created_at'],
                        'updated_at' => $productArr['updated_at']
                    ));

                    /* Updating sale rank of products*/
                    /*DB::table('product_fba')
                        ->update(['sales_rank' => $rank])
                        ->update(['buy_box_price' => $buybox_price])
                        ->where('asin', $asin);


                }*/


            } else {
                echo "No messages in queue. \n";
                Log::info('Messagae Count -- ' . count($result->get('Messages')));
            }


            Log::info('Messagae Count New -- ' . print_r($array));
        } catch (AwsException $e) {
            // output error message if fails
            Log::error($e->getMessage());
        }
    }
}
