<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\ProductFBA\Exporter\ProductFbaCsv;
use App\Modules\ProductFBA\ProductFbaRepository;
use App\ProductFBA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Sonnenglas\AmazonMws\AmazonOrderList;
use Sonnenglas\AmazonMws\AmazonReportRequest;
use Sonnenglas\AmazonMws\AmazonReportRequestList;
use Sonnenglas\AmazonMws\AmazonReport;
use Sonnenglas\AmazonMws\AmazonProductInfo;
use Sonnenglas\AmazonMws\AmazonProduct;
use Sonnenglas\AmazonMws\AmazonCore;
use DB;

class ProductFBAController extends Controller
{
    private $fba_product;


    /**
     * ProductFBAController constructor.
     * @param ProductFbaRepository $ProductFbaRepository
     */
    public function __construct(ProductFbaRepository $ProductFbaRepository)
    {
        $this->fba_product = $ProductFbaRepository;
    }

    /**
     * paginate
     * @param mixed $request
     * @return mixed
     */
    public function paginate(Request $request)
    {
        if (\Request::ajax()) {
            $fields = ['title', 'buy_box', 'asin', 'upc', 'profit', 'estimated_monthly_sales'];
            $start = $request->get('start', 0);
            $length = $request->get('length', 2);
            $search = $request->get('search');
            $order = $request->get('order');
            $column = array_get($order, '0.column', 'created_at');
            $direction = array_get($order, '0.dir', 'desc');
            $value = array_get($search, 'value', null);
            $whereSearchKey = is_null($value) ? [] : [['title', 'like', '%' . $value . '%']];
            $length = $length == -1 ? ProductFBA::where($whereSearchKey)->count() : $length;

            $fbaProducts = ProductFBA::where($whereSearchKey)
                ->orderBy($fields[$column], $direction)
                ->offset($start)
                ->limit($length)
                ->get()
                ->map(function ($item) {
                    return [
                        "title" => $item->title,
                        "buy_box" => $item->buy_box,
                        "asin" => $item->asin,
                        "upc" => $item->upc,
                        "profit" => $item->profit,
                        "estimated_monthly_sales" => $item->estimated_monthly_sales,
                    ];
                });
               // echo'<pre>';print_r($fbaProducts);die;

            return response()->json([
                "recordsTotal" => ProductFBA::where($whereSearchKey)->count(),
                'recordsFiltered' => ProductFBA::where($whereSearchKey)->count(),
                'draw' => (int)$request->get('draw', 1),
                "data" => $fbaProducts,
            ]);
        }
    }

    /**
     * index
     * @return mixed
     */
    public function index()
    {
        if (\Request::ajax()) {
            return $this->getList();
        }
        return view('webpanel.fbaproducts.index');
    }

    /**
     * export
     * @param mixed $exporter
     * @return mixed
     */
    public function export(ProductFBACsv $exporter)
    {
        $fba_product = $this->fba_product->getPaginated(Input::all(), null, Input::get('orderBy', 'created_at'), Input::get('orderType', 'DESC'));
        return $exporter->setName('Results')->setHeadings([
            'id', 'title', 'buy_box', 'asin', 'upc', 'profit', 'estimated_monthly_sales',
        ])->export($fba_product);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        ini_set('max_execution_time', 1000);

        $this->fba_product->validator->setDefault('import')->with($request->all())->isValid();
        $destinationPath = public_path() . '/uploads/files/';
        $file_temp = $request->file('file');
        $extension = $file_temp->getClientOriginalExtension() ?: 'csv';
        $safeName = str_random(10) . '.' . $extension;
        $header = null;
        $data = array();
        $file_temp->move($destinationPath, $safeName);
        if (($handle = fopen($destinationPath . $safeName, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    if (count($row) > count($header)) {
                        array_pop($row);
                    }
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        \DB::beginTransaction();
        try {

            if ($request->get('type') == 'new') {

                $this->fba_product->deleteAll();
                $this->fba_product->import($data, 'insert');

            }else{
                $this->fba_product->import($data, 'update');
            }
            \DB::commit();

            return redirect()->back()->with(['success' => 'Imported Successfully.']);

        } catch (Exception $e) {
            \DB::rollBack();
            dd($e);
            throw new ApplicationException("Cannot Import.");
        }
    }

    /**
     * @throws \Exception/
     */
    function getAmazonReports()
    {
        $amz = new AmazonReportRequest("store1");
        $amz->setReportType('_GET_RESTOCK_INVENTORY_RECOMMENDATIONS_REPORT_');
        $amz->requestReport();

        $report_request_id = $amz->getReportRequestId();

        sleep(120);

        $report_request_list = new AmazonReportRequestList("store1");
        $report_request_list->setRequestIds($report_request_id);
        $report_request_list->fetchRequestList();
        $report_id = $report_request_list->getReportId();

        /*
         * This is to make sure that we get the report id
         */
        while (!$report_id)
        {
            sleep(120);
            $report_request_list = new AmazonReportRequestList("store1");
            $report_request_list->setRequestIds($report_request_id);
            $report_request_list->fetchRequestList();
            $report_id = $report_request_list->getReportId();
        }

        $report = new AmazonReport("store1");
        $report->setReportId($report_id);
        $report_content = $report->fetchReport();
        echo "<pre>"; print_r($report_content); die('reports');
        dd($report_content);
        /*
         * TO DO: Write a function to read from the report into the database
         */

        /*$client = new \MCS\MWSClient([
            'Marketplace_Id' => config('api.amazon.marketplaceId'),
            'Seller_Id' => config('api.amazon.merchantId'),
            'Access_Key_ID' => config('api.amazon.keyId'),
            'Secret_Access_Key' => config('api.amazon.secretKey'),
        ]);

        if ($client->validateCredentials()) {
            $reportId = $client->RequestReport('_GET_RESTOCK_INVENTORY_RECOMMENDATIONS_REPORT_');
            $report_content = $client->GetReport($reportId);
            if ($report_content) {
                dd($report_content);
            } else {
                dd('Status : false');
            }
        } else {
            dd('client not created');
        }*/
    }

    function updateSalesRank()
    {
        /*
         * ASIN list in chunk of 5
         */

        $amz = new AmazonProductList("store1");
        $amz->setIdType('ASIN');
        $amz->setProductIds($asin_list);
        $amz->fetchProductList();
        $products = $amz->getProduct();

        /*
         * TO DO: Populate information into db. Only sales rank is needed.
         */
        foreach ($products as $product)
        {

        }


    }


    public function getCategoriesForProducts()
    {
        $header = null;
        $data = array();

        $destinationPath = public_path() . '/' . 'product_list.csv';

        if (($handle = fopen($destinationPath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    if (count($row) > count($header)) {
                        array_pop($row);
                    }
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        $asin_chunks = array_chunk($data, 20);

        foreach ($data as $line) {
            /*foreach ($asin_chunk as $line) {
                $asin_list[] = $line['asin'];
            }*/

            $product = new AmazonProductInfo("store1");
            $product->setASINs($line['asin']);
            $product->fetchCategories();
            $products = $product->getProduct();
            foreach ($products as $product)
            {
                $product_info = $product->getData();
                $category = $product_info['Categories'][0];
                $cat_string = $this->getCategoryString($category);
                file_put_contents(public_path() . '/export_categories.csv', $line['asin'] . "," . $cat_string . "\r\n", FILE_APPEND);
            }
        }
    }

    public function getDataFromOnlineDb()
    {
        $header = null;
        $data = array();

        $destinationPath = public_path() . '/' . 'product_list.csv';

        if (($handle = fopen($destinationPath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    if (count($row) > count($header)) {
                        array_pop($row);
                    }
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }



        $url = 'https://api.datafiniti.co/v4/products/search';
        $format = 'JSON';
        $query = 'asins:' ;
        $num_records = 1;
        $download = false;

        $api_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc19hZG1pbiI6ZmFsc2UsInN1YiI6IjU4MiIsImlzcyI6ImRhdGFmaW5pdGkiLCJlbWFpbCI6Im1pa2VuQGRvcHRvcC5jb20ifQ.pkOuiat9l569TMADmKs4moTuM02u-xNV0Rn7xFiDbuY';

        foreach ($data as $line)
        {
            $request_body = array(
                'query' => 'asins:' . $line['asin'],
                'format' => $format,
                'num_records' => $num_records,
                'download' => $download
            );

            $options = array(
                'http' => array (
                    'header'  => "Authorization: Bearer " . $api_token . "\r\n" .
                        "Content-Type: application/json\r\n",
                    'method'  => 'POST',
                    'content' => json_encode($request_body)
                )
            );

            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $result = json_decode($result, true);

            if ($result['num_found'] != 0)
            {
                $product = $result['records'][0];
                if (isset($product['brand']))
                    $brand = $product['brand'];
                else
                    $brand = '';

                $category = @serialize($product['categories']);
                if (isset($product['descriptions']))
                {
                    if (is_array($product['descriptions']))
                    {
                        foreach ($product['descriptions'] as $description)
                        {
                            $description_arr[] = array($description['value'], $description['sourceURLs']);
                        }
                        $product_description = serialize($description_arr);
                    }
                } else {
                    $product_description = '';
                }

                $images = @serialize($product['imageURLs']);
                DB::table('products_dt')->insert(
                    ['asin' => $line['asin'], 'category' => $category, 'brand' => $brand, 'description' => $product_description, 'images' => $images, 'raw_data' => serialize($product)]
                );
            }
        }
    }

    function getCategoryString($category)
    {
        if (is_array($category))
        {
            if (array_key_exists('Parent', $category ) )
            {
                return $this->getCategoryString($category['Parent']) . " > " . $category['ProductCategoryName'];
            } else {
                return $category['ProductCategoryName'];
            }
        }
    }

    public function clearTitle() {
        $data = readFromCSV(public_path() . '/title_list.csv', "\t");
        $patterns = ['/(packs? of ([0-9]+))/i'];
        file_put_contents(public_path() . '/title_list_output.csv', "title\r\n");

        foreach ($data as $line)
        {
            foreach ($patterns as $pattern)
            {
                $line['title'] = preg_replace($pattern, "", $line['title']);
            }

            $line['title'] = trim(str_replace(['(', ')'], '', $line['title']));
            file_put_contents(public_path() . '/title_list_output.csv', $line['title'] . "\r\n", FILE_APPEND);

        }
    }

    public function importDataIntoMikeAmazon()
    {
        /*$data = readFromCSV(public_path() . '/mike_amazon.csv');
        foreach ($data as $line)
        {
            DB::table('mike_products_amazon')->insert(
                [
                    'sku' => $line['SellerSKU'],
                    'title' => $line['Title'],
                    'qty' => (int) $line['Quantity'],
                    'product_id' => $line['ProductID'],
                    'upc' => $line['UPC'],
                    'weight' => number_format($line['Weight'],2),
                    'shipping_cost' => number_format($line['Shipping fee'],2),
                    'site_price' => number_format($line['Price final'], 2),
                    'cost' => number_format($line['Price']),
                    'price_min' => number_format($line['Price Min'], 2),
                    'price_max' => number_format($line['Price Max'], 2),
                ]
            );
        }*/

        $data = readFromCSV(public_path() . '/mike_shopify.csv');
        foreach ($data as $line)
        {
            DB::table('mike_products_shopify')->insert(
                [
                    'sku' => $line['SellerSKU'],
                    'title' => $line['Title'],
                    'qty' => $line['Qty'],
                    'site_price' => number_format($line['Price'], 2),
                ]
            );
        }
    }

    public function updateStock()
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
                        $site_price = calculate_price_shopify($line['Base Wholesale']);
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

            file_put_contents(public_path() . '/output/sa_upload.csv', 'InventoryAction,Site,SellerSKU,Quantity,Price,Price (Minimum),Price (Maximum)');
            file_put_contents(public_path() . '/output/sa_upload_shopify.csv', 'InventoryAction,Site,SellerSKU,Quantity,Price');
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

    public function getLatestPricingForASIN()
    {
        $header = null;
        $data = array();

        $destinationPath = public_path() . '/' . 'product_list.csv';

        if (($handle = fopen($destinationPath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    if (count($row) > count($header)) {
                        array_pop($row);
                    }
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        $asin_chunks = array_chunk($data, 20);

        foreach ($data as $line) {
            /*foreach ($asin_chunk as $line) {
                $asin_list[] = $line['asin'];
            }*/

            $product = new AmazonProductInfo("store1");
            $product->setASINs($line['asin']);
            $product->fetchCompetitivePricing();
            $products = $product->getProduct();
            foreach ($products as $product)
            {
                if (is_object($product))
                {
                    $product_info = $product->getData();
                    //dd($product_info);
                    if (isset($product_info['CompetitivePricing']['CompetitivePrices']))
                        $buybox_price = $product_info['CompetitivePricing']['CompetitivePrices'][1]['Price']['LandedPrice']['Amount'];
                    else
                        $buybox_price = 0;
                    //$sales_rank = $product_info['SalesRankings']['SalesRank']['Rank'];
                    file_put_contents(public_path() . '/export_products.csv', $line['asin'] . "," . $buybox_price . "," . "" .  "\r\n", FILE_APPEND);
                }
            }
        }
    }
}
