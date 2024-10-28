<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Services\Character;


class CharactersController extends Controller
{

    protected $futuramaController;
    protected $gotController;
    protected $theOfficeController;

    public function __construct()
    {
        $this->futuramaController = new FuturamaController();
        $this->gotController = new GotController();
        $this->theOfficeController = new TheOfficeController();
    }

    public function getAllCharacters(  ) {
        $futuramaResponse = $this->futuramaController->getCharacters();
        $gotResponse = $this->gotController->getCharacters();
        $theOfficeResponse = $this->theOfficeController->getCharacters();

        $allCharacters = [
            'futurama' => $futuramaResponse->getData(),
            'got' => $gotResponse->getData(),
            'theOffice' => $theOfficeResponse->getData(),
        ];

        return response()->json($allCharacters);
    }

    public function getFilteredCharacters( Request $request ) {

        $params = $request->all();

        $gotResponse = $this->gotController->getFilteredCharacters($params);
        $futuramaResponse = $this->futuramaController->getFilteredCharacters($params);
        $theOfficeResponse = $this->theOfficeController->getFilteredCharacters($params);

        $allCharacters = [
            'Game of Thrones' => $gotResponse->getData(),
            'Futurama' => $futuramaResponse->getData(),
            'The Office' => $theOfficeResponse->getData(),
        ];

        return response()->json($allCharacters);
    }
}
