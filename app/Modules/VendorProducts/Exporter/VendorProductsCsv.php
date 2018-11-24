<?php
/**
 * Created by PhpStorm.
 * User: optima
 * Date: 4/12/18
 * Time: 2:09 PM
 */

namespace App\Modules\VendorProducts\Exporter;

use Illuminate\Support\Facades\Response;
use Optimait\Laravel\Services\Export\AbstractExporter;
use Optimait\Laravel\Services\Export\ExportInterface;

class VendorProductsCsv extends AbstractExporter implements ExportInterface
{

    /**
     * @param $data
     * @return mixed
     */
    public function export($data)
    {
        $output = '';
        //First lets get the headings
        $output .= implode(',', $this->heading) . "\n";

        foreach ($data as $k => $d) {
            $output .= $this->escapeAndReturn(array(
                $d->id,
                $d->vendor_id,
                $d->product_title,
                $d->vendor_item_number,
                $d->upc,
                $d->vendor_cost,
                $d->case_quantity,
                $d->weight,
                $d->category,
            ));
            $output .= "\n";
        }
        $filename = $this->filename != '' ? $this->filename : 'Exports-' . @date('Y-m-d-H-i-s');
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        );
        return Response::make($output, 200, $headers);
    }

    /**
     * @param $array
     * @return string
     */
    public function escapeAndReturn($array)
    {
        $str = '';
        foreach ($array as $val) {
            $str .= str_replace(array("\r\n", "\n", "\r", ","), array(" ", " ", " ", " "), $val) . ",";
        }
        return $str;
    }


}