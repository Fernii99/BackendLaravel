<?php

namespace App\Http\Controllers;

use App\Services\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TheOfficeController extends Controller
{
    public function getCharacters() {
        $response = Http::get('https://theofficeapi.dev/api/characters');

        if($response->successful()){
            $character = new Character();
            $data = $response->json();

            if (isset($data['results'])) {
                foreach ($data['results'] as $item) {
                    // Add each character from the items array
                    $character->addCharacter(
                        $item['name'],        // Name field
                        $item['gender'] ?? '',      // Gender field
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

        $response = Http::get('https://theofficeapi.dev/api/characters');
        $character = new Character();

        if($response->successful()){
            $data = $response->json();

            if (isset($data['results'])) { // Check if 'items' key exists

                foreach ($data['results'] as $item) {
                    if (
                        ($name != "" ? strpos(strtolower($item['name']), strtolower($name)) !== false :true) &&
                        ($item['gender'] !== "Select Gender" ?  true : strtolower($item['gender']) === strtolower($gender) )
                    ) {
                        // Add character once the conditions match
                        $character->addCharacter(
                            $item['name'],
                            $item['gender'],
                            null
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
