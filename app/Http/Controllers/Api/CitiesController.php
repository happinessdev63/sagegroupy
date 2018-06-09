<?php

namespace App\Http\Controllers\Api;

use App\Reference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Job;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CitiesController extends Controller
{

    public function fetchByCountryCode( Request $request )
    {

        if ( !$request->has( 'countryCode' ) ) {
            return [];
        }

        /* Use smaller cities file when possible */
        if ($request->has('country')) {
            $citiesSmall = json_decode(file_get_contents(public_path( "js/cities_small.json" )), true);
            if (isset($citiesSmall[$request->country])) {
                $cities = collect( $citiesSmall[$request->country] );
                $cities = $cities->unique();
                $cities = $cities->sort();
            }
        } else {
            $fileName = public_path( "js/cities/" . $request->countryCode . "_cities.json" );
            if ( !file_exists( $fileName ) ) {
                return [];
            }

            $cities = file_get_contents( $fileName );
            $cities = json_decode( $cities, true );

            $cities = collect( $cities );
            $cities = $cities->unique( "name" );
            $cities = $cities->sortBy( "name" );
        }

        /* Format for auto complete selects */
        if ($request->get('format') == 'autocomplete') {
            $autoComplete = [];
            foreach ($cities as $city) {
                $autoComplete[] = [
                    'label' => $city,
                    'value' => $city
                ];
            }
            return $autoComplete;
        }


        return $cities->values()->all();

    }

}

