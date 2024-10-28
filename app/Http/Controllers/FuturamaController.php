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
            $character = new Character();
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

    public function getFilteredCharacters($params) {

        $name = $params['name'] == "" ? "" : $params['name'];
        $gender = $params['gender'] == "" ? null : $params['gender'];
        $image = $params['image'] === "yes" ? true : false;

        $response = Http::get('https://futuramaapi.com/api/characters');
        $character = new Character();

        if($response->successful()){
            $data = $response->json();

            if (isset($data['items'])) { // Check if 'items' key exists
                foreach ($data['items'] as $item) {
                    if (
                        ($name != "" ? strpos(strtolower($item['name']), strtolower($name)) !== false :true) &&
                        ($image == "yes" ? $item['image'] != null : $item['image'] == null) &&
                        ($item['gender'] === "Select Gender" ?  true : strtolower($item['gender']) === strtolower($gender) )
                    ) {
                        // Add character once the conditions match
                        $character->addCharacter(
                            $item['name'],
                            $item['gender'],
                            $item['image']
                        );
                    }
                }

                return $character->getResponse();
            } else {
                return response()->json(['error' => 'Invalid data structure'], 400);
            }

        }else{
            return response()->json(['error' => 'Unable to fetch data from API'], $response->status());
        }
    }
}
