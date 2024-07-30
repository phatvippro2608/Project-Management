<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\OptionModel;
use App\Models\OptionCurrencyModel;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    //
    public function getView()
    {
        $options = OptionModel::first();
        $currencies = OptionCurrencyModel::all();
        $currencyOptions = [];
        $symbolOptions = [];

        // foreach ($currencies as $currency) {
        //     $currencyOptions[$currency->currency_id] = $currency->currency_currency;
        //     $symbolOptions[$currency->currency_id] = $currency->currency_symbol;
        // }

        return view('auth.settings.settings', [
            'options' => $options,
            'currencies' => $currencies,
            // 'currencyOptions' => $currencyOptions,
            // 'symbolOptions' => $symbolOptions,
        ]);
    }

    public function updateForm(Request $request)
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'option_title' => 'required|string|max:255',
                'option_description' => 'nullable|string',
                'option_copyright' => 'nullable|string|max:255',
                'option_contact' => 'nullable|string|max:255',
                'option_currency' => 'required|exists:options_currency,currency_id',
                'option_email' => 'nullable|email|max:255',
                'option_address' => 'nullable|string|max:255',
                'option_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Find the option record
            $option = OptionModel::first();

            // Update the fields with request data
            $option->option_title = $request->input('option_title');
            $option->option_description = $request->input('option_description');
            $option->option_copyright = $request->input('option_copyright');
            $option->option_contact = $request->input('option_contact');
            $option->currency_id = $request->input('option_currency');
            $option->option_email = $request->input('option_email');
            $option->option_address = $request->input('option_address');

            // Handle the image upload
            if ($request->hasFile('option_img')) {
                if ($option->option_img && file_exists(public_path('assets/img/' . $option->option_img))) {
                    unlink(public_path('assets/img/' . $option->option_img));
                }

                $image = $request->file('option_img');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('assets/img'), $imageName);
                $option->option_img = 'assets/img/'.$imageName;
            }

            // Save the updated option record
            $option->save();

            // Redirect back with success message
            return redirect()->back()->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            \Log::error('Error updating settings: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating settings.');
        }
    }


}
