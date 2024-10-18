<?php

namespace App\Http\Controllers;

use App\Services\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FuturamaController extends Controller
{
    public function getCharacters() {
        $response = Http::get('https://futuramaapi.com/api/characters');

        if($response->successful()){
            $character = new Character(201, "Data successfully retrieved");
            $data = $response->json();

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    // Add each character from the items array
                    $character->addCharacter(
                        $item['name'],        // Name field
                        $item['gender'],      // Gender field
                        $item['image'] ?? ''  // Image field, with fallback to empty string
                    );
                }

                // Return the JSON response with the added characters
                return $character->getResponse();
            } else {
                // If 'items' key is not found, return an error
                return response()->json(['error' => 'Invalid data structure'], 400);
            }
        }else{
            return response()->json(['error' => 'Unable to fetch data from API'], $response->status());
        }
    }
}
