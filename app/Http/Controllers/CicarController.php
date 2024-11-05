<?php

namespace App\Http\Controllers;

use DateTime;
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
            $result = $client->ObtenerListaDeOficinasCompleto("ES");

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
            'Empresa' => "K11",
            'Usuario' => "DitGes",
            'Clave' => "DitCan2023",
            'Tarifa' => $request->input('Tarifa', ''),
            'Grupo' => $request->input('Grupo', ''),
            'Zona' => $request->input('Zona', 'ACE'),
            'OfiEnt' => $request->input('OfiEnt', '3K'),
            'OfiDev' => $request->input('OfiEnt', '3K'),
            'FIni' => $request->input('FechaInicio', date("Y-m-d H:i:s", strtotime("+15 days"))),
            'FFin' => $request->input('FechaFin', date("Y-m-d H:i:s", strtotime("+20 days"))),
            'Idioma' => $request->input('Idioma', 'ES'),
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

            $result = $client->ObtenerModelosEnGrupo("K11", "DitGes", "DitCan2023",  '', '','ACE', '3K', '3K', date("Y-m-d H:i:s", strtotime("+15 days")), date("Y-m-d H:i:s", strtotime("+20 days")), 'ES');
        //  Call the ObtenerListaDeZonas operation

        return response()->json($result)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Authorization, Origin');


        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerListaDeOficinasEnZona(Request $request)
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

            $params = [
                'Zona' => $request->input('zona'),   // Retrieve 'Zona' from the request
                'Idioma' => "ES"  // Set the language code, e.g., Spanish
            ];

            var_dump($request->input());

            //  Call the ObtenerListaDeOficinas operation
            $result = $client->ObtenerListaDeOficinasEnZona($request->input('Zona'), "ES");

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
