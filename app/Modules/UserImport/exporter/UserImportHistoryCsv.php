<?php
/**
 * Created by PhpStorm.
 * User: optima
 * Date: 4/12/18
 * Time: 2:09 PM
 */

namespace App\Modules\UserImport\Exporter;

use Illuminate\Support\Facades\Response;
use Optimait\Laravel\Services\Export\AbstractExporter;
use Optimait\Laravel\Services\Export\ExportInterface;

class UserImportHistoryCsv extends AbstractExporter implements ExportInterface
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
        foreach ($data as $d) {
            $output .= $this->escapeAndReturn(array(
                $d['job_id'],
                $d['row'],
                $d['attribute'],
                $d['message']
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
     * escapeAndReturn
     * @param mixed $array
     * @return mixed
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
