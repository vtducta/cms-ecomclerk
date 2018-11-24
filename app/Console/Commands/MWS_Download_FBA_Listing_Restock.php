<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ProductFBA;
use Sonnenglas\AmazonMws\AmazonOrderList;
use Sonnenglas\AmazonMws\AmazonReportRequest;
use Sonnenglas\AmazonMws\AmazonReportRequestList;
use Sonnenglas\AmazonMws\AmazonReport;

class MWS_Download_FBA_Listing_Restock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MWSDownloadFBAListing:mwsdownloadfbalisting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh FBA listings in the database & restock inventory report';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $amz = new AmazonReportRequest("store1");
        $amz->setReportType('_GET_RESTOCK_INVENTORY_RECOMMENDATIONS_REPORT_');
        $amz->requestReport();

        $report_request_id = $amz->getReportRequestId();

        sleep(120);

        $report_request_list = new AmazonReportRequestList("store1");
        $report_request_list->setRequestIds($report_request_id);
        $report_request_list->fetchRequestList();
        $report_id = $report_request_list->getReportId();

        /*
         * This is to make sure that we get the report id
         */
        while (!$report_id)
        {
            sleep(120);
            $report_request_list = new AmazonReportRequestList("store1");
            $report_request_list->setRequestIds($report_request_id);
            $report_request_list->fetchRequestList();
            $report_id = $report_request_list->getReportId();
        }

        $report = new AmazonReport("store1");
        $report->setReportId($report_id);
        $report_content = $report->fetchReport();

        /*
         * @todo Change the file path to S3
         */
        $data = array();
        $header = null;

        file_put_contents(public_path() . '/reports/' . $report_id . '.csv', $report_content);
        if (($handle = fopen(public_path() . '/reports/' . $report_id . '.csv', 'r')) !== FALSE)
        {
            while(($row = fgetcsv($handle, 0, "\t")) !== FALSE)
            {
                if (!$header)
                {
                    $header = $row;

                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        /*
         * @todo Separate this by user id
         */
        foreach ($data as $row)
        {
            $product = ProductFBA::updateOrCreate(
                ['sku' => $row['SKU']],
                [
                    'asin' => $row['ASIN'],
                    'title' => $row['Product Description'],
                    'sku' => $row['SKU'],
                    'sale_30day' => $row['Sales last 30 days (units)'],
                    'qty_available' => $row['Available Inventory'],
                    'inbound_qty' => $row['Inbound Inventory'],
                    'restock_qty' => $row['Recommended Order Quantity'],
                    'restock_date' => $row['Recommended Order Date'],
                    'restock_status' => $row['Instock Alert']
                ]
            );
        }
    }
}
