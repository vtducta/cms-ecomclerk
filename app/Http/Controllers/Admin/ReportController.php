<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Modules\ProductFBA\Exporter\ProductFbaCsv;
use App\Modules\ProductFBA\ProductFbaRepository;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\VendorProduct;
use App\ProductFBA;
use DB;
use App\Vendor;
use Elasticsearch\ClientBuilder;
use App\Transaction;


class ReportController extends Controller
{

    /**
     * ReportController constructor.
     * @param ProductFbaRepository $productRepository
     */
    private $products;
    public function __construct(ProductFbaRepository $productRepository)
    {
         $this->products = $productRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $params = [
            'index' => 'fba_reports',
            'body' => [
                'aggs' => [
                    'sum_of_product_sales' => [
                        'sum' => [
                            'field' => 'product_sales'   
                        ]
                    ],
                
                    'sum_of_cost' => [
                        'sum' => [
                            'field' => 'cost'   
                        ]
                    ],
                    'sum_of_selling_fees' => [
                        'sum' => [
                            'field' => 'selling_fees'   
                        ]
                    ]
                ],
                "sort"=> [
                     [ "product_sales"=> ["order" => "asc"] ]
                  ]
            ]   
        ];

        $data = array();
        $client = $this->settings();
        $indexParams['index']  = 'fba_reports';   
        $checkIndex = $client->indices()->exists($indexParams);
        if($checkIndex == '1'){
            $response = $client->search($params);
            $data = $response['hits']['hits'];
            $sum_of_cost = $response['aggregations']['sum_of_cost']['value'];
            $sum_of_product_sales = $response['aggregations']['sum_of_product_sales']['value']; 
            $selling_fees = $response['aggregations']['sum_of_selling_fees']['value']; 
        }else{
            $sum_of_cost = 0;
            $sum_of_product_sales = 0;
            $selling_fees = 0;
        }
        return View('webpanel.reports.index',compact('sum_of_cost','sum_of_product_sales','data','selling_fees'));
        
    }

    public function settings(){
       $hosts = [
            [
            'host' => '4910e4ced04e43cba7f3af1bb9c6c605.us-east-1.aws.found.io',
            'port' => '9243',
            'scheme' => 'https',
            'user' => 'elastic', 
            'pass' => 'ZE3u8jPcx8L0mX5rpdY94kSc'
            ]
        ];
        $client = ClientBuilder::create() 
        ->setHosts($hosts) 
        ->build();
        
        return $client; 
    }

    public function import(Request $request){
        $this->products->validator->setDefault('import')->with($request->all())->isValid();
        $destinationPath = public_path() . '/uploads/files/';
        $file_temp = $request->file('file');
        $extension = $file_temp->getClientOriginalExtension() ?: 'csv';
        $safeName = str_random(10) . '.' . $extension;
        $header = NULL;
        $data = array();
        $file_temp->move($destinationPath, $safeName);
        if (($handle = fopen($destinationPath . $safeName, 'r')) !== FALSE){
            while(($row = fgetcsv($handle, 0, ",")) !== FALSE){
                if (!$header){
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
		
        try {
			$transactions = array();
            foreach ($data as $row) {
                $row['cost'] = number_format($row['cost'],2);
                $row['fba_fees'] = number_format($row['fba_fees'],2);
                $row['selling_fees'] = number_format($row['selling_fees'],2);
                $row['product_sales'] = number_format($row['product_sales'],2);
                $row['shipping_credits'] = number_format($row['shipping_credits'],2);
                $row['gift_wrap_credits'] = number_format($row['gift_wrap_credits'],2);
                $row['sales_tax_collected'] = number_format($row['sales_tax_collected'],2);
                $row['Marketplace_Facilitator_Tax'] = number_format($row['Marketplace_Facilitator_Tax'],2);
                $row['other_transaction_fees'] = number_format($row['other_transaction_fees'],2);
                $row['total'] = number_format($row['total'],2);
               
                $row['date_time'] = date('Y-m-d H:i:s', strtotime($row['date_time']));
                
				
				
				//$transactions[] = $row;
				$transactions[] = '( "'.$row["date_time"].'", '.$row["settlement_id"].', "'.$row["type"].'", "'.$row["order_id"].'", "'.$row["sku"].'", "'.$row["description"].'", '.$row["quantity"].',"'.$row["marketplace"].'", "'.$row["fulfillment"].'", "'.$row["order_city"].'", "'.$row["order_state"].'" ,"'.$row["order_postal"].'", '.$row["product_sales"].', '.$row["shipping_credits"].', '.$row["gift_wrap_credits"].', '.$row["promotional_rebates"].', '.$row["sales_tax_collected"].', '.$row["Marketplace_Facilitator_Tax"].', '.$row["fba_fees"].', '.$row["other_transaction_fees"].', '.$row["other"].', '.$row["total"].', '.$row["cost"].' )';
				
				/****** For saving into elastic searcDB *****/
					 //$row['created_at'] = date('Y-m-d');
					//$this->saveData($row);
            }
			
			$saveTxns = DB::raw('insert into transactions ( date_time, settlement_id, type, order_id, sku, description, quantity,marketplace, fulfillment, order_city, order_state,order_postal, product_sales,shipping_credits, gift_wrap_credits, promotional_rebates, sales_tax_collected, Marketplace_Facilitator_Tax, fba_fees, other_transaction_fees, other, total, cost ) VALUES  '.implode(', ',$transactions). ' ON DUPLICATE KEY UPDATE date_time = VALUES(date_time), settlement_id = VALUES(settlement_id), type = VALUES(type), sku = VALUES(sku), description = VALUES(description), quantity = VALUES(quantity), marketplace = VALUES(marketplace), fulfillment = VALUES(fulfillment), order_city = VALUES(order_city), order_state = VALUES(order_state), order_postal = VALUES(order_postal), product_sales = VALUES(product_sales), shipping_credits = VALUES(shipping_credits), gift_wrap_credits = VALUES(gift_wrap_credits), promotional_rebates = VALUES(promotional_rebates), sales_tax_collected = VALUES(sales_tax_collected), Marketplace_Facilitator_Tax = VALUES(Marketplace_Facilitator_Tax), fba_fees = VALUES(fba_fees), other_transaction_fees = VALUES(other_transaction_fees), other = VALUES(other), total = VALUES(total), cost = VALUES(cost)');
			
			if($saveTxns){
				return redirect()->back()->with(['success' => 'Imported Successfully.']);
			}else{
				return redirect()->back()->with(['error' => 'Error in importing records']);
			}
			
        } catch (Exception $e) {
            //throw new ApplicationException("Cannot Import.");
			return redirect()->back()->with(['error' => 'Exception : Error in importing records']);
        }
    }


    public function saveData($data_){
        $data = [
            "index"=> "fba_reports",
            "type"=> "fba_reports",
            "id" => $data_['order_id'],
            "body" => $data_
            
        ];

        $client = $this->settings();
        $response = $client->index($data);

    }

    public function paginate(Request $request)
    {
       //echo "Hello"; die('Here');
        if (\Request::ajax()) {
            $start = $request->get('start', 0);
            $length = $request->get('length', 2);
            $search = $request->get('search');
            $order = $request->get('order');
            $column = array_get($order, '0.column', 'created_at');
            $direction = array_get($order, '0.dir', 'desc');
            $value = array_get($search, 'value', null);
            $whereSearchKey = is_null($value) ? [] : [['title', 'like', '%' . $value . '%']];
            $length = $length == -1 ? ProductFBA::where($whereSearchKey)->count() : $length;

            $params = [
                'index' => 'fba_reports',
                'from' => $start,
                'size' => $length,
                'body' => [
                    /*'query' => [
                        'bool' => [
                            'must' => [
                                'match_phrase' => [
                                    'created_at' => date('Y-m-d')
                                ]
                            ]
                        ]
                    ],*/
                    'aggs' => [
                        'sum_of_product_sales' => [
                            'sum' => [
                                'field' => 'product_sales'   
                            ]
                        ],
                    
                        'sum_of_cost' => [
                            'sum' => [
                                'field' => 'cost'   
                            ]
                        ],
                        'sum_of_selling_fees' => [
                            'sum' => [
                                'field' => 'selling_fees'   
                            ]
                        ]
                    ],
                    "sort"=> [
                        [ "product_sales"=> ["order" => "asc"] ]
                    ]
                ]   
            ];

            $data = array();
            $data_ = array();
            $client = $this->settings();
            $indexParams['index']  = 'fba_reports';   
            $checkIndex = $client->indices()->exists($indexParams);
            if($checkIndex == '1'){
                $response = $client->search($params);
                /*echo '<pre>';print_r($response);die;*/
                
                $data = $response['hits']['hits'];
                foreach ($data as $key => $value) {
                   $value['sku'] = $value['_source']['sku'];
                   $value['order_id'] = $value['_source']['order_id'];
                   $value['quantity'] = $value['_source']['quantity'];
                   $value['product_sales'] = $value['_source']['product_sales'];
                   $value['selling_fees'] = $value['_source']['selling_fees'];
                   $value['sales_tax_collected'] = $value['_source']['sales_tax_collected'];
                   $value['date_time'] = $value['_source']['date_time'];
                   $value['cost'] = $value['_source']['cost'];
                   $value['total'] = $value['_source']['total'];
                   $data_[] = $value;
                }
                $sum_of_cost = $response['aggregations']['sum_of_cost']['value'];
                $sum_of_product_sales = $response['aggregations']['sum_of_product_sales']['value']; 
                return response()->json([
                    "data" => $data_,
                    'sum_of_cost' => $sum_of_cost,
                    'sum_of_product_sales' => $sum_of_product_sales,
                    'recordsTotal' => $response['_shards']['total'],
                    'recordsFiltered' => count($data),
                ]);
            }else{
               return response()->json([
                    "data" => $data_,
                    'sum_of_cost' => 0,
                    'sum_of_product_sales' => 0
                ]);
            }
        }
    }







    

}
