<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CharactersController extends Controller
{
    public function getExternalData() {
        $response = Http::get('https://api.sampleapis.com/coffee/hot');

        if($response->successful()){

            $data = $response->json();

            $filteredData = collect($data) // Assuming 'items' is the key that holds the recipes
                ->filter(function ($item) {
                    // Check if 'coffee' is in the ingredients array
                    return in_array('Espresso', $item['ingredients']);
                });

            // Return the filtered data as a JSON response
            return response()->json($filteredData->values());
        }else{
            return response()->json(['error' => 'Unable to fetch data from API'], $response->status());
        }
    }
}
