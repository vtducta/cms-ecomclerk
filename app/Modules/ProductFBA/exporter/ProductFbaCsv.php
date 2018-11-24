<?php
/**
 * Created by PhpStorm.
 * User: optima
 * Date: 4/12/18
 * Time: 2:09 PM
 */

namespace App\Modules\ProductFBA\Exporter;

use Illuminate\Support\Facades\Response;
use Optimait\Laravel\Services\Export\AbstractExporter;
use Optimait\Laravel\Services\Export\ExportInterface;

class ProductFbaCsv extends AbstractExporter implements ExportInterface
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
                $d->title,
                $d->buy_box,
                $d->asin,
                $d->upc,
                $d->profit,
                $d->estimated_monthly_sales,
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
     * @param $data
     * @return mixed
     */
    public function exportPurchaseOrders($data)
    {
        $output = '';
        //First lets get the headings
        $output .= implode(',', $this->heading) . "\n";

        foreach ($data as $k => $d) {
            /*getting quantity by checking restock_qty is greater than case_quantity and restock_status not null */
            if ($d->restock_qty > $d->case_quantity && $d->restock_status)
                $quantity = floor($d->restock_qty / $d->case_quantity) * $d->case_quantity;
            else
                $quantity = $d->restock_qty;
                $d->quantity = $quantity;
                $output .= $this->escapeAndReturn(array(
                $d->title,
                $d->vendor_item_number,
                $d->product_title,
                $d->case_quantity,
                $d->vendor_item_number,
                $d->quantity,
                $d->cost,
                $d->weight,
                $d->vendor_item_number
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
