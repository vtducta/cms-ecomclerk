<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\VendorProducts\VendorProductsRepository;
use App\Modules\VendorProducts\Exporter\VendorProductsCsv;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\VendorProduct;

class VendorProductController extends Controller
{
    private $vendorProducts;

    /**
     * VendorProductController constructor.
     * @param VendorProductsRepository $vendorProductsRepository
     */
    public function __construct(VendorProductsRepository $vendorProductsRepository)
    {
        $this->vendorProducts = $vendorProductsRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginate(Request $request)
    {
        if (\Request::ajax()) {
            $fields = ['product_title', 'vendor_item_number', 'upc', 'vendor_cost', 'case_quantity', 'weight', 'category'];
            $start = $request->get('start', 0);
            $length = $request->get('length', 2);
            $search = $request->get('search');
            $order = $request->get('order');
            $column = array_get($order, '0.column', 'created_at');
            $direction = array_get($order, '0.dir', 'desc');
            $value = array_get($search, 'value', null);
            $whereSearchKey = is_null($value) ? [] : [['product_title', 'like', '%' . $value . '%']];
            $length = $length == -1 ? VendorProduct::where($whereSearchKey)->count() : $length;

            $vendorProducts = VendorProduct::where($whereSearchKey)
                ->orderBy($fields[$column], $direction)
                ->offset($start)
                ->limit($length)
                ->get()
                ->map(function ($item) {
                    $action = '<a href="' . route('webpanel.vendor-products.edit', $item->id) . '"><i class="fa fa-pencil"></i></a> <a class="confirm-action sa-warning" data-title="Confirm action?" data-text="Product will be deleted. Are you sure?" data-confirm-button-text="Delete" data-method="DELETE" data-href="' . route('webpanel.vendor-products.destroy', $item->id) . '" data-success-title="Action completed" data-success-text="Product successfully Deleted!" data-refresh-page-on-success="true"><i class="fa fa-trash"></i></a>';
                    return [
                        "product_title" => $item->product_title,
                        "vendor_item_number" => $item->vendor_item_number,
                        "upc" => $item->upc,
                        "vendor_cost" => $item->vendor_cost,
                        "case_quantity" => $item->case_quantity,
                        "weight" => $item->weight,
                        "category" => $item->category,
                        "action" => $action
                    ];
                });

            return response()->json([
                "recordsTotal" => VendorProduct::where($whereSearchKey)->count(),
                'recordsFiltered' => VendorProduct::where($whereSearchKey)->count(),
                'draw' => (int)$request->get('draw', 1),
                "data" => $vendorProducts
            ]);
        }
    }

    /**
     *
     */
    public function destroy($id)
    {
        $vendorProduct = VendorProduct::where('id', $id)->firstOrFail();
        $vendorProduct->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Vendor Product Successfully Deleted',
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (\Request::ajax()) {
            return $this->getList();
        }
        return view('webpanel.vendor_products.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function import(Request $request)
    {
        ini_set('max_execution_time', 1000);
        $this->vendorProducts->validator->setDefault('import')->with($request->all())->isValid();
        $destinationPath = public_path() . '/uploads/files/';
        $file_temp = $request->file('file');
        $extension = $file_temp->getClientOriginalExtension() ?: 'csv';
        $safeName = str_random(10) . '.' . $extension;
        $header = NULL;
        $data = array();
        $file_temp->move($destinationPath, $safeName);
        if (($handle = fopen($destinationPath . $safeName, 'r')) !== FALSE) {
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

                $this->vendorProducts->deleteAll();
                $this->vendorProducts->importVendorProducts($data, 'insert');

            }else{
                $this->vendorProducts->importVendorProducts($data, 'update');
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
     * @param VendorProductsCsv $exporter
     * @return mixed
     */
    public function export(VendorProductsCsv $exporter)
    {
        $vendorProducts = $this->vendorProducts->getPaginated(Input::all(), null, Input::get('orderBy', 'created_at'), Input::get('orderType', 'DESC'));
        return $exporter->setName('Results')->setHeadings([
            'id', 'vendor_id', 'product_title', 'vendor_item_number', 'upc', 'vendor_cost', 'case_quantity', 'weight', 'category'
        ])->export($vendorProducts);
    }

    /**
     *
     */
    public function show()
    {

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $vendorProducts = VendorProduct::where('id', $id)->firstOrFail();
        return view('webpanel.vendor_products.edit', compact('vendorProducts'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $this->vendorProducts->validator->setDefault('edit')->with($request->all())->isValid();
        $vendorProducts = VendorProduct::where('id', $id)->firstOrFail();
        $status = $vendorProducts->update($request->except(['_method']));
        if ($status) {
            return response()->json(array(
                'notification' => ReturnNotification(array('success' => 'Vendor Product Info Updated Successfully')),
                'redirect' => route('webpanel.vendor-products.index')
            ));
        }
    }
}
