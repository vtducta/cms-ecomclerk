<?php
/**
 * Created by PhpStorm.
 * User: optima
 * Date: 4/12/18
 * Time: 2:09 PM
 */

namespace App\Modules\Products\Exporter;


use Illuminate\Support\Facades\Response;
use Optimait\Laravel\Services\Export\AbstractExporter;
use Optimait\Laravel\Services\Export\ExportInterface;

class ProductCsv extends AbstractExporter implements ExportInterface
{

    public function export($data)
    {
        $output = '';
        //First lets get the headings
        $output .= implode(',', $this->heading) . "\n";

        foreach ($data as $k => $d) {
            $output .= $this->escapeAndReturn(array(
                $k + 1,
                $d->title,
                $d->amazon_title,
                $d->number_of_packs,
                $d->brand,
                $d->asin,
                $d->amazon_upc_ean,
                $d->upc_ean,
                $d->amazon_buy_box_price,
                $d->net_after_fba,
                $d->cost,
                $d->pack_cost,
                $d->gross_profit_fba,
                $d->gross_roi,
                $d->sales,
                $d->sales_rank,
                $d->sales_rank_30,
                $d->sales_rank_90,
                $d->buybox_win,
                $d->weight,
                $d->number_of_sellers,
                $d->number_of_prime_sellers,
                $d->quantity_buy_in,
                $d->po_status
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

    public function escapeAndReturn($array)
    {
        $str = '';
        foreach ($array as $val) {
            $str .= str_replace(array("\r\n", "\n", "\r", ","), array(" ", " ", " ", " "), $val) . ",";
        }
        return $str;
    }


}