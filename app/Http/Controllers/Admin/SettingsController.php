<?php

namespace App\Http\Controllers\Admin;

use App\Services\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index( Request $request, Settings $settings )
    {
        return view("admin.settings", [
            'settings' => $settings->values()
        ]);
    }

    public function mailLogs( Request $request, Settings $settings )
    {
        return view( "admin.mailLogs", [
        ] );
    }

    public function update(Request $request, $type, Settings $settings )
    {
        switch ($type) {
            case "skills":
                $this->validate( $request, [
                    'max_skills' => 'required|numeric|between:1,100',
                    'low_rate_threshold' => 'required|numeric|between:0,1',
                    'high_rate_threshold' => 'required|numeric|between:1,10',
                    'low_user_count_threshold' => 'required|numeric|between:0,50'
                ] );
            break;
        }

        $settings->saveFromRequest($request);

        return response()->json( [
            'message' => "Settings updated successfully.",
            'status'  => "success"
        ] );
    }
}
