<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Integration;
use App\IntegrationSetting;
use Auth;
use Input;
class DTIntegrationController extends Controller
{
    private $controls;

    public function __construct()
    {
        $this->controls = array(
            'amazon' => array(
                array(
                    'label' => 'Amazon Seller ID',
                    'type' => 'text',
                    'name' => 'amazon_seller_id'
                ),
                array(
                    'label' => 'Amazon Secret Key',
                    'type' => 'text',
                    'name' => 'amazon_secret_key'
                )
            ),
            'shopify' => array(
                array(
                    'label' => 'Shopify URL',
                    'type' => 'text',
                    'name' => 'shopify_url',
                ),
                array(
                    'label' => 'Shopify API Key',
                    'type' => 'text',
                    'name' => 'shopify_api_key',
                ),
                array(
                    'label' => 'Shopify Shared Secret',
                    'type' => 'text',
                    'name' => 'shopify_shared_secret',
                )
            )
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $integrations = Integration::get();

        return view('webpanel.integration.index', compact('integrations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();

        $integration = Integration::where('url_key', $id)->first();
        $settings = IntegrationSetting::where('integration_id', $integration->id)->where('user_id', $user->id)->get();
        $values = array();
        foreach ($settings as $setting)
        {
            $values[$setting->option_key] = $setting->option_value;
        }
        if (is_null($integration)) {
            throw new ResourceNotFoundException('Integration not Found');
        }
        $controls = $this->controls[$id];
        return view('webpanel.integration.edit', compact(array('integration', 'controls', 'values')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $integration = Integration::where('url_key', $id)->first();
        $options = Input::get('option_name');
        foreach ($options as $key => $value)
        {
            $integration_setting = IntegrationSetting::where('integration_id', $integration->id)->where('user_id', $user->id)->where('option_key', $key)->first();
            if ($integration_setting)
            {
                $integration_setting->option_value = $value;
                $integration_setting->save();
            } else {
                IntegrationSetting::create(
                    ['integration_id' => $integration->id, 'option_key' => $key, 'user_id' => $user->id, 'option_value' => $value]
                );
            }
        }

        return response()->json(array(
            'notification' => ReturnNotification(array('success' => 'Integration Info Saved Successfully')),
            'redirect' => route('webpanel.integrations.index')
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
