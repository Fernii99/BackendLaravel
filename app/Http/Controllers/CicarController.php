<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SoapClient;

class cicarController extends Controller
{
    public function obtenerListaDeZonas()
    {
        $wsdl = 'http://extranet.cicar.com/webservices/soap/wsreservas.dll/wsdl/IReservas';

        try {
            // Create a new SoapClient instance
            $client = new SoapClient($wsdl, [
                'trace' => true,
                'keep_alive' => false,
                'connection_timeout' => 5000,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression'  => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);

        //  Call the ObtenerListaDeZonas operation
            $result = $client->ObtenerListaDeOficinasEnZona("FUE", "ES");

            return $result;

        // $zones = [];
        // if (isset($result->ZonaArray) && is_array($result->ZonaArray)) {
        //     foreach ($result->ZonaArray as $zone) {
        //         // Check if stdClass exists in each zone and access properties
        //         if (isset($zone->stdClass)) {
        //             $zoneData = $zone->stdClass;
        //             $zones[] = [
        //                 'code' => $zoneData->Codigo ?? null,
        //                 'name' => $zoneData->Nombre ?? 'Unknown',
        //                 'description' => $zoneData->Descripcion ?? ''
        //             ];
        //         }
        //     }
        // }


        // return response()->json([
        //     'status' => $result->ResultStatus,
        //     'errorText' => $result->ResultErrorText,
        //     'zones' => $result->ZonaArray,
        // ]);

        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerModelosDisponibles(Request $request)
    {
        $wsdl = 'http://extranet.cicar.com/webservices/soap/wsreservas.dll/wsdl/IReservas';

        $params = [
            'Idioma' => "ES",
            'Empresa' => "K11",
            'Usuario' => "DitGes",
            'Clave' => "DitCan2023",
            'Tarifa' => $request->input('Tarifa', ''),
            'FechaInicio' => $request->input('FechaInicio', ''),
            'FechaFin' => $request->input('FechaFin', ''),
            'Zona' => $request->input('Zona', ''),
            'OfiEnt' => $request->input('OfiEnt', ''),
            'OfiDev' => $request->input('OfiDev', ''),
            "EntHotel" => $request->input('EntHotel', false), // Default to false if not provided
            "DevHotel" => $request->input('DevHotel', false),
            'SillasBebe' => $request->input('SillasBebe', 0), // Default to 0 if not provided
            'Elevadores' => $request->input('Elevadores', 0),
            'ConductoresAdicionales' => $request->input('ConductoresAdicionales', 0),
            'Baca' => $request->input('Baca', false)
        ];



        try {
            // Create a new SoapClient instance
            $client = new SoapClient($wsdl, [
                'trace' => true,
                'keep_alive' => false,
                'connection_timeout' => 5000,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression'  => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);

        //  Call the ObtenerListaDeZonas operation
        $result = $client->ObtenerModelosDisponibles($params);

        return response()->json($result)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Authorization, Origin');


        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerlistaoficinas()
    {
        $wsdl = 'http://extranet.cicar.com/webservices/soap/wsreservas.dll/wsdl/IReservas';

        try {
            // Create a new SoapClient instance
            $client = new SoapClient($wsdl, [
                'trace' => true,
                'keep_alive' => false,
                'connection_timeout' => 5000,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression'  => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);

        //  Call the ObtenerListaDeOficinas operation
        $result = $client->obtenerlistadeoficinascompleto("ES");

        return response()->json([
            'status' => $result->ResultStatus,
            'errorText' => $result->ResultErrorText,
            'offices' => $result->OficinaArray,
        ]);

        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
