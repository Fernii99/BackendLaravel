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

    public function obtenerModelosDisponiblesEnGrupo(Request $request)
    {
        $wsdl = 'http://extranet.cicar.com/webservices/soap/wsreservas.dll/wsdl/IReservas';

        $Empresa = "K11";
        $Usuario = "DitGes";
        $Clave = "DitCan2023";
        $Tarifa = "";
        $Grupo = "";
        $Zona = $request->input('Zona', 'ACE');
        $OfiEnt = $request->input('OfiEnt', '3K');
        $OfiDev = $request->input('OfiEnt', '3K');
        $FIni = $request->input('FechaInicio', date("Y-m-d H:i:s", strtotime("+15 days")));
        $FFin = $request->input('FechaFin', date("Y-m-d H:i:s", strtotime("+20 days")));
        $Idioma = $request->input('Idioma', 'ES');

        $params = [
            'Empresa' => "K11",
            'Usuario' => "DitGes",
            'Clave' => "DitCan2023",
            'Tarifa' => "",
            'Grupo' => "",
            'Zona' => $request->input('Zona', 'ACE'),
            'OfiEnt' => $request->input('OfiEnt', '3K'),
            'OfiDev' => $request->input('OfiEnt', '3K'),
            'FechaInicio' => $request->input('FechaInicio', date("Y-m-d H:i:s", strtotime("+15 days"))),
            'FechaFin' => $request->input('FechaFin', date("Y-m-d H:i:s", strtotime("+20 days"))),
            'Idioma' => $request->input('Idioma', 'ES'),
            'EntHotel' => '',
            'DevHotel' => '',
            'SillasBebe' => 0,
            'Elevadores' => 0,
            'ConductoresAdicionales' => 0,
            'Baca' => false,
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

            $result = $client->ObtenerModelosEnGrupo($Empresa,  $Usuario, $Clave,  '', '', $Zona, $OfiEnt, $OfiDev, $FIni, $FFin, $Idioma);
            $result2 = $client->ObtenerModelosDisponibles($params); // First response
        //  Call the ObtenerListaDeZonas operation

        $combinedResults = [];

        foreach ($result['ModeloArray'] as $item1) {
            // Look for a matching item in the second response
            foreach ($result2['ModeloDisponibleArray'] as $item2) {
                if (isset($item1->stdClass) && isset($item2->stdClass) && $item1['Codigo'] == $item2['Codigo']) {
                    // var_dump('item1:'.$item1. '       '. 'item2:'.$item2);
                    // Create a new object with selected data from each item
                    $combinedResults[] = [
                        'Codigo' => $item1['Codigo'],
                        'Aire' => $item1['Aire'],
                        'Capacidad'=> $item1['Capacidad'],
                        'Categoria' => $item1['Categoria'],
                        'Cierre' => $item1['Cierre'],
                        'Direccion' => $item1['Direccion'],
                        'Disponible' => $item1['Disponible'],
                        'Elevalunas' => $item1['Elevalunas'],
                        'Fotos' => $item1['Fotos'],
                        'OnRequest' => $item1['OnRequest'],
                        'Pax' => $item1['Pax'],
                        'Portabultos' => $item1['Portabultos'],
                        'Puertas' => $item1['Puertas'],
                        'RadioCD' => $item1['RadioCD'],
                        'Thumbnail' => $item1['Thumbnail'],
                        'Nombre' => $item2['Nombre'],
                        'Total' => $item2['Total'],
                        'Impuestos' => $item2['Impuestos'],
                        'BaseImponible' => $item2['BaseImponible'],
                    ];
                };
            };
        };

        return $combinedResults;

        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerModelosDisponibles(Request $request)
    {

        // Your WSDL and parameters for both requests
        $wsdl = 'http://extranet.cicar.com/webservices/soap/wsreservas.dll/wsdl/IReservas';


        $params = [
            'Empresa' => "K11",
            'Usuario' => "DitGes",
            'Clave' => "DitCan2023",
            'Tarifa' => "",
            'Grupo' => "",
            'Zona' => $request->input('Zona', 'ACE'),
            'OfiEnt' => $request->input('OfiEnt', '3K'),
            'OfiDev' => $request->input('OfiEnt', '3K'),
            'FechaInicio' => $request->input('FechaInicio', date("Y-m-d H:i:s", strtotime("+15 days"))),
            'FechaFin' => $request->input('FechaFin', date("Y-m-d H:i:s", strtotime("+20 days"))),
            'Idioma' => $request->input('Idioma', 'ES'),
            'EntHotel' => '',
            'DevHotel' => '',
            'SillasBebe' => 0,
            'Elevadores' => 0,
            'ConductoresAdicionales' => 0,
            'Baca' => false,
        ];

        try {
            $client = new SoapClient($wsdl, [
                'trace' => true,
                'keep_alive' => false,
                'connection_timeout' => 5000,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression'  => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);

            // Fetch both responses
            $result = $client->ObtenerModelosDisponibles($params); // First response

            return response()->json($result);

        } catch (\Exception $e) {
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

            // Call the ObtenerListaDeOficinas operation
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
