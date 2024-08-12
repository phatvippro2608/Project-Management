<?php

namespace App\Http\Controllers;

use App\Models\ContractModel;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    function getView(){
        $contracts = ContractModel::all();
        return view('auth.contracts.contract' ,['contracts'=>$contracts]);
    }


}
