<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    //
    public function getView()
    {
        $options = DB::table('options')->first();
        $currencies = DB::table('options_currency')->get();
        $currencyOptions = [];
        $symbolOptions = [];

        foreach ($currencies as $currency) {
            $currencyOptions[$currency->currency_id] = $currency->currency_currency;
            $symbolOptions[$currency->currency_id] = $currency->currency_symol;
        }

        return view('auth.settings.settings', [
            'options' => $options,
            'currencyOptions' => $currencyOptions,
            'symbolOptions' => $symbolOptions,
        ]);
    }
}
