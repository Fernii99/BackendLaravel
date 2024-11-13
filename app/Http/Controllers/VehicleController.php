<?php

namespace App\Http\Controllers;

use App\Http\Controllers\FlexibleController;
use App\Http\Controllers\CicarController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{

    protected $CicarController;
    protected $FlexibleController;

    public function __construct(CicarController $CicarController, FlexibleController $FlexibleController)
    {
        $this->CicarController = $CicarController;
        $this->FlexibleController = $FlexibleController;
    }


    public function obtenerListaCombinada(Request $request): JsonResponse
    {
        try {
            // Call methods from each controller
            $result1 = $this->CicarController->obtenerModelosDisponiblesEnGrupo($request);
            $result2 = $this->FlexibleController->ObtenerListaDeVehiculos();

            // Check if results are arrays and merge them
            $combinedResults = array_merge(
                $result1 ?? [],
                $result2 ?? []
            );

            // Return combined data as JSON
            return response()->json(['data' => $combinedResults]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }


}
