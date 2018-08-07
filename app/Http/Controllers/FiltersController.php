<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FiltersController extends Controller
{
    public function save(Request $request)
    {
        $activeFilters = $request->only('active');
        Session::put('filters', $activeFilters['active']);
        return response()->json('success');
    }

    public function show(){
        return response()->json(Session::get('filters'));
    }
}
