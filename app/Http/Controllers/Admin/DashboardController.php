<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Modules\Leads\Source;
use App\Modules\Users\UserRepository;
use App\Services\LeadsChartsTransformer;
use App\User;

class DashboardController extends Controller
{
    public function __construct(UserRepository $userRepository)
    {
        $this->users = $userRepository;
    }

    public function getIndex()
    {


        return view('webpanel.dashboard');
    }

}