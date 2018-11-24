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

class PurchaseOrdersController extends Controller
{
    private $purchaseOrders;

    /**
     * PurchaseOrdersController constructor.
     * @param ProductFbaRepository $purchaseOrders
     */
    public function __construct(ProductFbaRepository $purchaseOrders)
    {
        $this->purchaseOrders = $purchaseOrders;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $prodTotalInfo = array();
        $productfbaList = DB::table('product_fba')
            ->select('*')
            ->join('vendor_products', 'product_fba.upc', '=', 'vendor_products.upc')
            ->join('vendors', 'vendor_products.vendor_id', '=', 'vendors.id')
            ->get();

        foreach ($productfbaList->toArray() as $productinfo) {
            $productvendors[$productinfo->vendor_id][] = (array)$productinfo;
        }

        foreach ($productvendors as $vendor_id => $productValue) {
            $total_quantity = 0;
            $total_amount = 0;
            foreach ($productValue as $product) {
                /*getting quantity by checking restock_qty is greater than case_quantity and restock_status not null */
                if ($product['restock_qty'] > $product['case_quantity'] && $product['restock_status'])
                    $quantity = floor($product['restock_qty'] / $product['case_quantity']) * $product['case_quantity'];
                else
                    $quantity = $product['restock_qty'];

                $total_quantity = $total_quantity + $quantity;
                $total_amount = $total_amount + $product['cost'];

                /* getting vendor status */
                if (($product['minimum_purchase_amount'] == 0 || $product['minimum_purchase_amount'] == '') && ($product['minimum_weight_amount'] == 0 || $product['minimum_weight_amount'] == '') && ($product['minimum_case_quantity'] == 0 || $product['minimum_case_quantity'] == ''))
                    $status = 'Qualified';

            }
            $prodTotalInfo[$vendor_id]['total_quantity'] = $total_quantity;
            $prodTotalInfo[$vendor_id]['total_amount'] = $total_amount;
            $prodTotalInfo[$vendor_id]['status'] = $status;
        }
        return view('webpanel.purchaseorders.index', compact('productvendors', 'prodTotalInfo'));
    }

    /**
     * @param Request $request
     * @param $vendor_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginate(Request $request, $vendor_id)
    {
        if (\Request::ajax()) {
            $fields = ['UPC', 'Qty', 'Amount'];
            $start = $request->get('start', 0);
            $length = $request->get('length', 2);
            $search = $request->get('search');
            $order = $request->get('order');
            $column = array_get($order, '0.column', 'created_at');
            $direction = array_get($order, '0.dir', 'desc');
            $value = array_get($search, 'value', null);
            $whereSearchKey = is_null($value) ? [] : [['product_fba.upc', 'like', '%' . $value . '%']];
            $total_quantity = 0;
            $total_amount = 0;
            $productfbaList = DB::table('product_fba')
                ->select('*')
                ->join('vendor_products', 'product_fba.upc', '=', 'vendor_products.upc')
                ->where('vendor_products.vendor_id', $vendor_id)
                ->where($whereSearchKey)
                ->offset($start)
                ->limit($length)
                ->get()
                ->map(function ($item) {
                    /*getting quantity by checking restock_qty is greater than case_quantity and restock_status not null */
                    if ($item->restock_qty > $item->case_quantity && $item->restock_status)
                        $quantity = floor($item->restock_qty / $item->case_quantity) * $item->case_quantity;
                    else
                        $quantity = $item->restock_qty;

                    return [
                        "UPC" => $item->upc,
                        "Qty" => $quantity,
                        "Amount" => $item->cost
                    ];
                });

            return response()->json([
                "recordsTotal" => DB::table('product_fba')
                    ->select('*')
                    ->join('vendor_products', 'product_fba.upc', '=', 'vendor_products.upc')
                    ->where($whereSearchKey)
                    ->where('vendor_products.vendor_id', $vendor_id)->count(),
                'recordsFiltered' => DB::table('product_fba')
                    ->select('*')
                    ->join('vendor_products', 'product_fba.upc', '=', 'vendor_products.upc')
                    ->where($whereSearchKey)
                    ->where('vendor_products.vendor_id', $vendor_id)->count(),
                'draw' => (int)$request->get('draw', 1),
                "data" => $productfbaList
            ]);
        }
    }

    /**
     * @param ProductFbaCsv $exporter
     * @param $vendor_id
     * @return mixed
     */
    public function export(ProductFBACsv $exporter, $vendor_id)
    {
        $purchaseOrders = $this->purchaseOrders->getPurchaseOrderPaginated(Input::all(), null, Input::get('orderBy', 'created_at'), Input::get('orderType', 'DESC'), $vendor_id);

        return $exporter->setName('Results')->setHeadings([

            'title', 'vendor_item_number', 'product_title', 'case_quantity', 'Qty of case', 'Qty', 'Subtotal', 'weight', 'total cases'

        ])->exportPurchaseOrders($purchaseOrders);

    }
}
