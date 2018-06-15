<?php
/**
 * @desc
 * @package
 */

namespace App\Services;

use App\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class Settings
{

    /**
     * @var Collection
     */
    protected $mappedSettings;

    public function __construct()
    {
        $this->refresh();
    }

    public function refresh()
    {
        $this->mappedSettings = Setting::all();
        return $this;
    }

    public function saveFromRequest(Request $request) {
        foreach ($request->except("_token") as $key => $val) {
            $this->save([['name' => $key, 'value' => $val]]);
        }
        return $this;
    }

    public function save(Array $settings) {
        foreach ($settings as $setting) {
            Setting::updateOrCreate(['name' => $setting['name']], $setting);
        }
        return $this;
    }

    public function all()
    {
        return $this->mappedSettings;
    }

    public function values()
    {
        $settings = $this->mappedSettings->mapWithKeys(function($item) {
            return [$item->name => $item->value];
        });

        return $settings;
    }

    public function keyed()
    {
        $settings = $this->mappedSettings->mapWithKeys( function ( $item ) {
            return [ $item->name => $item ];
        } );

        return $settings;
    }

    public function get($key, $default = null)
    {

        $setting = $this->mappedSettings->filter(function($item) use ($key) {
            return $item->name == $key;
        });


        if ($setting->first()) {
            return $setting->first()->value;
        }

        return $default;
    }

}
