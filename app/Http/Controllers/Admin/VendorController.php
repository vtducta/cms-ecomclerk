<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Vendors\VendorRepository;
use App\Modules\Vendors\Exporter\VendorCsv;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Vendor;
use App\Http\Requests\EditVendor;

class VendorController extends Controller
{
    private $vendors;

    /**
     * VendorController constructor.
     * @param VendorRepository $vendorRepository
     */
    public function __construct(VendorRepository $vendorRepository)
    {
        $this->vendors = $vendorRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginate(Request $request)
    {
        if (\Request::ajax()) {
            $fields = ['title', 'minimum_purchase_amount', 'minimum_weight_amount', 'minimum_case_quantity'];
            $start = $request->get('start', 0);
            $length = $request->get('length', 2);
            $search = $request->get('search');
            $order = $request->get('order');
            $column = array_get($order, '0.column', 'created_at');
            $direction = array_get($order, '0.dir', 'desc');
            $value = array_get($search, 'value', null);
            $whereSearchKey = is_null($value) ? [] : [['title', 'like', '%' . $value . '%']];
            $length = $length == -1 ? Vendor::where($whereSearchKey)->count() : $length;

            $vendors = Vendor::where($whereSearchKey)
                ->orderBy($fields[$column], $direction)
                ->offset($start)
                ->limit($length)
                ->get()
                ->map(function ($item) {
                    $action = '<a href="' . route('webpanel.vendors.edit', $item->id) . '"><i class="fa fa-pencil"></i></a>';
                    return [
                        "title" => $item->title,
                        "minimum_purchase_amount" => $item->minimum_purchase_amount,
                        "minimum_weight_amount" => $item->minimum_weight_amount,
                        "minimum_case_quantity" => $item->minimum_case_quantity,
                        "action" => $action
                    ];
                });

            return response()->json([
                "recordsTotal" => Vendor::where($whereSearchKey)->count(),
                'recordsFiltered' => Vendor::where($whereSearchKey)->count(),
                'draw' => (int)$request->get('draw', 1),
                "data" => $vendors
            ]);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (\Request::ajax()) {
            return $this->getList();
        }
        return view('webpanel.vendors.index');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function import(Request $request)

    {
        ini_set('max_execution_time', 500);

        $this->vendors->validator->setDefault('import')->with($request->all())->isValid();

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

                $this->vendors->deleteAll();
                $this->vendors->importVendor($data, 'insert');

            }else{
                $this->vendors->importVendor($data, 'update');
            }
//            dd($data);
            \DB::commit();

            return redirect()->back()->with(['success' => 'Imported Successfully.']);

        } catch (Exception $e) {

            \DB::rollBack();

            dd($e);

            throw new ApplicationException("Cannot Import.");

        }

    }

    /**
     * @param VendorCsv $exporter
     * @return mixed
     */
    public function export(VendorCsv $exporter)

    {
        $vendors = $this->vendors->getPaginated(Input::all(), null, Input::get('orderBy', 'created_at'), Input::get('orderType', 'DESC'));

        return $exporter->setName('Results')->setHeadings([

            'id', 'title', 'minimum_purchase_amount', 'minimum_weight_amount', 'minimum_case_quantity'

        ])->export($vendors);

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
        $vendor = Vendor::where('id', $id)->firstOrFail();
        return view('webpanel.vendors.edit', compact('vendor'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $this->vendors->validator->setDefault('edit')->with($request->all())->isValid();
        $vendor = Vendor::where('id', $id)->firstOrFail();
        $status = $vendor->update($request->except(['_method']));
        if ($status) {
            return response()->json(array(
                'notification' => ReturnNotification(array('success' => 'Vendor Info Updated Successfully')),
                'redirect' => route('webpanel.vendors.index')
            ));
        }
    }
}
