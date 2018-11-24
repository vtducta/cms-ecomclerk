<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class MikeShopifyController extends Controller
{

    public function getShopifyOrders()
    {
        $ftp_server = 'edi.unfi.com';
        $conn_id = ftp_connect($ftp_server) or die("Could not connect to FTP server");
        $login_result = ftp_login($conn_id, env('HVA_login'), env('HVA_password'));

        if ($login_result)
        {
            ftp_sync($conn_id, 'hva');
        }

        $login_result = ftp_login($conn_id, env('PHI_login'), env('PHI_password'));
        if ($login_result)
        {
            ftp_sync($conn_id, 'phi');
        }

        $login_result = ftp_login($conn_id, env('RAC_login'), env('RAC_password'));
        if ($login_result)
        {
            ftp_sync($conn_id, 'rac');
        }

        

    }

}