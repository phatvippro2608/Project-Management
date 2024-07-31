<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{
    public function getView()
    {
        $sql = DB::table('employees')->get()->map(function ($item) {
            // Kiểm tra xem photo có tồn tại và không phải là null
            $item->photoExists = !is_null($item->photo) && file_exists(public_path($item->photo));
            return $item;
        });
        return view('auth.portfolio.portfolio', ['sql' => $sql]);
    }
    public function getViewHasId($id) {
        return view('auth.portfolio.portfolioHasId', ['id'=>$id]);
    }

}
