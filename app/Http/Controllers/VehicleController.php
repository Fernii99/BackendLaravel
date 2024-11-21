<?php

namespace App\Http\Controllers;

use App\Http\Controllers\FlexibleController;
use App\Http\Controllers\CicarController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        // Decode as an associative array
        $zona = $request->input('zona');

        try {

            $values = [
                'Empresa' => "K11",
                'Usuario' => "DitGes",
                'Clave' => "DitCan2023",
                'Tarifa' => $zona['Tarifa'],
                'Grupo' => $zona['Grupo'],
                'FechaInicio' => $zona['FechaInicio'],
                'HoraInicio' => $zona['HoraInicio'],
                'FechaFin' => $zona['FechaFin'],
                'HoraFin' => $zona['HoraFin'],
                'Zona' => $zona['Zona'],
                'OfiEnt' => $zona['OfiEnt'],
                'OfiDev' => $zona['OfiDev'],
                'EntHotel' => $zona['EntHotel'],
                'DevHotel' => $zona['DevHotel'],
                'Oficina' => $zona['Oficina'],
            ];

            // Fetch data from controllers
            $result1 = $this->CicarController->obtenerModelosDisponiblesEnGrupo($values);
            $result2 = $this->FlexibleController->ObtenerListaDeVehiculos();

            // Merge results
            $combinedResults = array_merge($result1 ?? [], $result2 ?? []);

            // Return combined data as JSON
            return response()->json(['data' => $combinedResults]);

            } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
