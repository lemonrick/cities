<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $name = $request->input('name');
        $cities = City::where('name', 'LIKE', "%$name%")->get(['name', 'id']);

        return response()->json(['data' => $cities]);
    }

    public function show($id)
    {
        $city = City::find($id);

        if (!$city) {
            abort(404);
        }

        return view('detail', compact('city'));
    }
}
