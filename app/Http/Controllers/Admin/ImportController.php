<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UserImportHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\UserImport;
use App\Modules\UserImport\UserImportRepository;
use Auth;
use App\Jobs\ImportCSV;
use App\Modules\UserImport\Exporter\UserImportHistoryCsv;
class ImportController extends Controller
{

    private $userImport;
    public function __construct(UserImportRepository $userImportRepository)
    {
        $this->userImport = $userImportRepository;
    }

    public function index()
    {
        if (\Request::ajax()) {
            return $this->getList();
        }
        return view('webpanel.import.index');
    }

    public function paginate(Request $request)
    {

        $user = Auth::user();
        //var_dump($user->id);die;
        if (\Request::ajax()) {
            $fields = ['job_id', 'file_name', 'created_at', 'updated_at', 'row_count', 'result_file'];
            $start = $request->get('start', 0);
            $length = $request->get('length', 2);
            $search = $request->get('search');
            $order = $request->get('order');
            $column = array_get($order, '0.column', 'created_at');
            $direction = array_get($order, '0.dir', 'desc');
            $value = array_get($search, 'value', null);

            $imports = UserImport::where([['user_id','=',$user->id]])->orderBy($fields[$column], $direction)
                ->offset($start)
                ->limit($length)
                ->get()
                ->map(function ($item) {
                    return [
                        "job_id" => $item->job_id,
                        "file_name" => $item->file_name,
                        "created_at" =>  $item->created_at->format('d M Y'),
                        "updated_at" => $item->updated_at->format('d M Y'),
                        "row_count" => $item->row_count,
                        "result_file" => $item->result_file
                    ];
                });
            // echo'<pre>';print_r($fbaProducts);die;

            return response()->json([
                "recordsTotal" => UserImport::where([['user_id','=',$user->id]])->count(),
                'recordsFiltered' => UserImport::where([['user_id','=',$user->id]])->count(),
                'draw' => (int)$request->get('draw', 1),
                "data" => $imports,
            ]);


        }
    }

    public function import(Request $request)
    {

        $user = Auth::user();
        ini_set('max_execution_time', 1000);

        $this->userImport->validator->setDefault('import')->with($request->all())->isValid();
        $destinationPath = storage_path('app') ;
        $file_temp = $request->file('file');
        $extension = $file_temp->getClientOriginalExtension() ?: 'csv';
        $safeName = str_random(10) . '.' . $extension;
        $header = null;
        $data = array();
        $file_temp->move($destinationPath, $safeName);
        //after save file
        //1. save to db
        $data_row = array();
        $data_row['user_id']=$user->id;
        $data_row['file_name'] =$safeName;
        $row_id = $this->userImport->insertUserImport($data_row);
        //var_dump($row_id); die;

        //2. push queue
        $job_id = $this->dispatch(new ImportCSV($data_row['file_name']));
        //3. update job_id
        $this->userImport->updateJobId($row_id,$job_id);

        //4. redirect to page list
        return redirect('/import');
    }

    public function error_export($job_id,UserImportHistoryCsv $exporter)
    {
        $user_import_history = UserImportHistory::where([['job_id','=',$job_id]])->orderBy('id', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    "job_id" => $item->job_id,
                    "row" => $item->row,
                    "attribute" =>  $item->attribute,
                    "message" => $item->message
                ];
            });


        return $exporter->setName('Results')->setHeadings([
            'job_id', 'row', 'attribute', 'message',
        ])->export($user_import_history);
    }

}