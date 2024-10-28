<?php

namespace App\Http\Controllers;

use App\Services\Character;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class GotController extends Controller
{
    public function getCharacters() {
        $response = Http::get('https://thronesapi.com/api/v2/Characters');

        if($response->successful()){
            $character = new Character();
            $data = $response->json();

            if (isset($data)) {
                foreach ($data as $item) {
                    // Add each character from the items array
                    $character->addCharacter(
                        $item['fullName'],        // Name field
                        $item['gender'] ?? '',      // Gender field
                        $item['imageUrl'] ?? ''  // Image field, with fallback to empty string
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

        $name = $params['name'] == "" ? null : $params['name'];
        $gender = $params['gender'] == "" ? null : $params['name'];
        $image = $params['image'] === "yes" ? true : false;

        $response = Http::get('https://thronesapi.com/api/v2/Characters');
        $character = new Character();

        if( $response->successful() ){
            $data = $response->json();

            if (isset($data)) { // Check if 'items' key exists
                foreach ($data as $item) {
                    if
                    (
                        ($name != "" ? strpos(strtolower($item['fullName']), strtolower($name)) !== false :true) &&
                        ($image == "yes" ? $item['imageUrl'] != null : $item['imageUrl'] == null)
                    ) {
                        // Add character once the conditions match
                        $character->addCharacter(
                            $item['fullName'],
                            "",
                            isset($item['imageUrl']) ? $item['imageUrl'] : null
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
