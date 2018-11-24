<?php

namespace App\Imports;

use App\ProductFBA;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use App\UserImportHistory;
use App\UserImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnError;

class ProductFBAImport implements ToCollection, WithChunkReading, WithBatchInserts, WithHeadingRow, SkipsOnFailure, SkipsOnError
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected  $job_id;
    protected $row_index =0;
    protected $row_fail_count =0;
    public function __construct(int $job_id)
    {
        Log::info('init process job_id: '. $job_id );
        $this->job_id = $job_id;
    }

    public function collection(Collection $rows)
    {
        Log::info('begin collection');

        try{
            //valid format file
            $columnInvalid = ['inventoryaction','site','sellersku','title','producttype','productid','cost','quantity'];
            $first_row = $rows->first();

            Log::info($first_row);
            Log::info($first_row->toArray());
            $check_row = true;
            foreach ($columnInvalid as $column){
                $check_row = $check_row && array_key_exists($column,$first_row->toArray());
                if(!array_key_exists($column,$first_row->toArray())){
                    Log::info($column . ' column does not exits');
                    break;
                }
            }
            if(!$check_row){
                //update user_import result wrong format
                $this->updateUserImportResult(0,'file import wrong format');
                //return job
                Log::info('file wrong format when check first row');
                Log::info('end collection');
                return;
            }

            //process for all row

            foreach ($rows as $row)
            {
                $this->row_index++;

                try {

                    if ($row['inventoryaction'] && $row['site'] && $row['sellersku'])
                    {
                        switch ($row['inventoryaction'])
                        {
                            case 'Add':
                                //valid field required to add, if empty continue to process
                                if(!$row['title']){
                                    $this->insertUserImportHistoryFailRow($this->row_index,'title','Title required');
                                    $this->row_fail_count++;
                                    continue;
                                }
                                if(!$row['producttype']){
                                    $this->insertUserImportHistoryFailRow($this->row_index,'producttype','ProductType required');
                                    $this->row_fail_count++;
                                    continue;
                                }
                                if(!$row['productid']){
                                    $this->insertUserImportHistoryFailRow($this->row_index,'productid','ProductId required');
                                    $this->row_fail_count++;
                                    continue;
                                }
                                if(!$row['cost']){
                                    $this->insertUserImportHistoryFailRow($this->row_index,'cost','Cost required');
                                    $this->row_fail_count++;
                                    continue;
                                }
                                if(!$row['quantity']){
                                    $this->insertUserImportHistoryFailRow($this->row_index,'quantity','Quantity required');
                                    $this->row_fail_count++;
                                    continue;
                                }


                                //switch site to do
                                switch ($row['site'])
                                {
                                    case 'AmazonFBA':
                                        $product_fba = ProductFBA::where('sku', $row['sellersku'])->first();
                                        if($product_fba){
                                            $this->insertUserImportHistoryFailRow($this->row_index,'sellersku','SellerSku exits');
                                            $this->row_fail_count++;
                                            continue;
                                        }
                                        ProductFBA::create([
                                            'title' => $row['title'],
                                            'asin' => $row['productid'],
                                            'sku' => $row['sellersku'],
                                            'qty_available' => $row['quantity'],
                                            'cost' => $row['cost'],
                                        ]);
                                        Log::info('insert productFBA');
                                        break;
                                    case 'Amazon':
                                        /*
                                         * @TODO: Import products for Amazon
                                         */
                                        break;
                                    case 'Shopify':
                                        /*
                                         * @TODO: Import producs for FBA
                                         */
                                        break;
                                }
                                break;
                            case 'Modify':
                                //valid field required to modify, if empty continue to process
                                if(!$row['cost']){
                                    $this->insertUserImportHistoryFailRow($this->row_index,'cost','Cost required');
                                    $this->row_fail_count++;
                                    continue;
                                }
                                switch ($row['site'])
                                {
                                    case 'AmazonFBA':
                                        $product_fba = ProductFBA::where('sku', $row['sellersku'])->first();
                                        if ($product_fba)
                                        {
                                            $product_fba->cost = (isset($row['cost'])) ? $row['cost'] : $product_fba->cost;
                                            $product_fba->save();
                                        }else{
                                            $this->insertUserImportHistoryFailRow($this->row_index,'sellersku','SellerSku does not exits');
                                            $this->row_fail_count++;
                                            continue;
                                        }
                                        break;
                                }
                                break;
                            case 'Delete':
                                switch ($row['site'])
                                {
                                    case 'AmazonFBA':
                                        $product_fba = ProductFBA::where('sku', $row['sellersku'])->fist();
                                        if ($product_fba)
                                        {
                                            ProductFBA::where('sku', $row['sellersku'])->delete();
                                        }else{
                                            $this->insertUserImportHistoryFailRow($this->row_index,'sellersku','SellerSku does not exits');
                                            $this->row_fail_count++;
                                            continue;
                                        }
                                        break;
                                }
                                break;
                            default:
                                $this->insertUserImportHistoryFailRow($this->row_index,'','InventoryAction not in (Add,Modify,Delete) ');
                                $this->row_fail_count++;
                                continue;
                        }
                    }else{
                        $this->insertUserImportHistoryFailRow($this->row_index,'','InventoryAction, Site, Sellersku required');
                        $this->row_fail_count++;
                        continue;
                    }
                } catch (\Exception $ex){
                    $this->insertUserImportHistoryFailRow(row_index,'','Error be not handled in row, error message: '. $ex->getMessage());
                    $this->row_fail_count++;
                    Log::error('error in process row loop ' . $ex->getMessage());
                    continue;
                }
            }

            Log::info('Process done collection');
            $message_result = $this->createResultMessage($this->row_index,$this->row_fail_count);
            $this->updateUserImportResult($this->row_index,$message_result);

        }catch (\Exception $ex){
            $this->updateUserImportResult('Error be not handled in file, error message: ' . $ex->getMessage());
            Log::error('error in collection ' . $ex->getMessage());
        }
        Log::info('end process collection');
    }

    public function updateUserImportResult($row_count=0, $result_content){
        UserImport::where('job_id',$this->job_id)->update(['row_count'=>$row_count, 'result_file' => $result_content]);
    }

    public  function  insertUserImportHistoryFailRow($row_id=0,$field_tile,$message){
        UserImportHistory::create([
            'job_id' => $this->job_id,
            'row' => $row_id,
            'attribute' => $field_tile,
            'message' => $message,
        ]);
    }
    public function createResultMessage($row_count,$row_fail_count){
        $message='Total row processed: '.$row_count;
        if($row_fail_count>0){
            $message .= '<br>';
            $message .= '<a href="' . url('import/result_error/'. $this->job_id) .'" />Total row failed: ' . $row_fail_count . '</a>';
        }

        return $message;
    }

    public function onFailure(Failure ...$failures)
    {
        /*
         * @TODO: Write the function to write failure to the database and then create an exportable format of results
         */
        Log::info('begin process fail');
        foreach ($failures as $failure)
        {
            /*
             * @TODO Write to the database the row that has error
             */
            UserImportHistory::create([
                'job_id' => $this->job_id,
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'message' => $failure->errors(),
            ]);
        }
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 1000000;
    }


    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'title' => 'title required',
            'producttype' => 'producttype required' ,
            'productid' => 'productid required',
            'cost' => 'cost required',
            'quantity' => 'quantity required',
        ];
    }

    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
        Log::error('onError: ' . $e->getMessage());
    }
}
