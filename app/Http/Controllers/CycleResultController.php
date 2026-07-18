<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cycle2Result;
use Illuminate\Http\Request;

class CycleResultController extends Controller
{
    public function getCycle2Results()
    {
        $results = Cycle2Result::all();
        return response()->json($results);
    }
    
    public function getCycle1Results()
    {
        // If you have Cycle1Result model
        // return response()->json(Cycle1Result::all());
        return response()->json([]);
    }
}