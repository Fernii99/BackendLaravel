<?php

namespace App\Http\Controllers;

use App\Services\Vehicle;
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

        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerModelosDisponiblesEnGrupo($data)
{


    $wsdl = 'http://extranet.cicar.com/webservices/soap/wsreservas.dll/wsdl/IReservas';


    $params = [
        'Empresa' => "K11",
        'Usuario' => "DitGes",
        'Clave' => "DitCan2023",
        'Tarifa' => $data['Tarifa'],
        'Grupo' => $data['Grupo'],
        'FechaInicio' => $data['FechaInicio'],
        'HoraInicio' => $data['HoraInicio'],
        'FechaFin' => $data['FechaFin'],
        'HoraFin' => $data['HoraFin'],
        'Zona' => $data['Zona'],
        'OfiEnt' => $data['OfiEnt'],
        'OfiDev' => $data['OfiDev'] ,
        'EntHotel' => $data['EntHotel'],
        'DevHotel' => $data['DevHotel'],
        'Oficina' => $data['Oficina'],
        'SillasBebe' => 0,
        'Elevadores' => 0,
        'ConductoresAdicionales' => 0,
        'Baca' => false,
        'Idioma' => "ES"
    ];

    try {

        $client = new SoapClient($wsdl, [
            'trace' => true,
            'keep_alive' => false,
            'connection_timeout' => 5000,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
        ]);

        $result = $client->ObtenerModelosEnGrupo(
            $data['Empresa'],
            $data['Usuario'],
            $data['Clave'],
            $data['Tarifa'],
            $data['Grupo'],
            $data['Zona'],
            $data['OfiEnt'],
            $data['OfiDev'],
            strtotime($data['FechaInicio'].$data['HoraInicio']),
            strtotime($data['FechaFin'].$data['HoraFin']),
            "ES",
        );

        $result2 = $client->ObtenerModelosDisponibles($params);

        // Debug safely without affecting response
        Log::info('Result1:', [$result]);
        Log::info('Result2:', [$result2]);

        $vehicleModel = new Vehicle();
        $availableModels = [];
        $vehicleTypes = [];

        if (isset($result2->ModeloDisponibleArray) && is_array($result2->ModeloDisponibleArray)) {
            foreach ($result2->ModeloDisponibleArray as $item2) {
                $availableModels[$item2->Codigo] = $item2;
            }
        }

        if (isset($result->ModeloArray) && is_array($result->ModeloArray)) {
            foreach ($result->ModeloArray as $item1) {
                if (isset($availableModels[$item1->Codigo])) {
                    $item2 = $availableModels[$item1->Codigo];

                    $vehicleData = $vehicleModel->addVehicle(
                        $item1->Codigo ?? "",
                        $item1->Disponible ? "Available" : "NotAvailable",
                        $item1->Categoria ?? "",
                        $item1->Portabultos ?? "",
                        $item1->Nombre ?? "",
                        $item1->Codigo ?? "",
                        "https://www.cicar.com/" . ($item1->Foto ?? ""),
                        $item1->Capacidad ?? "",
                        (string)$item1->Pax ?? null,
                        $item1->Puertas ?? "",
                        $item2->TipoTarifa ?? null,
                        (string)$item2->Total ?? "",
                        $item2->TotalMargenDitChargeS ?? "",
                        $item1->SupImg ?? "",
                        $item2->TotalPVPCharge ?? "",
                        $item2->Currency ?? "EUR",
                        $item1->RateQualifier ?? "",
                        $item1->Aire ? "Y" : "N",
                        $item1->Direccion ? "Y" : "N",
                        $item2->Categoria ?? null,
                        $item1->CarDescription ?? "",
                        $item1->Features ?? "",
                        $item1->SupplierCode ?? "CC",
                        $item1->Supplier ?? "Cicar",
                        $item1->SupplierDetails ?? "",
                        $item1->LocationType ?? "",
                        $item1->FuelSurChargeVal ?? 0,
                        $item1->FuelSurChargeCur ?? 0,
                        $item1->ExcessVal ?? "",
                        $item1->ExcessCur ?? "",
                        $item1->FuelChargeVal ?? 0,
                        $item1->FuelChargeCur ?? 0,
                        $item1->Anotation ?? [
                            "FUEL - Full to Full",
                            "EXCESS - Standard Excess",
                            "OTHER COSTS - Excess:744.00:EUR,Deposit:750:EUR",
                            "CANCELLATIONS - No se aplican gastos",
                            "NO SHOWS - En caso de no recoger el coche (no show) los gastos son: Tarifa Estándar 100.00 EUR,"
                        ],
                        $item1->DropCharge ?? 0,
                        $item1->DropChargeCurrency ?? "EUR",
                        $item1->ProductCostsL ?? "",
                        $item1->ProductCostsS ?? "https://prodxml-2.vpackage.net/coches/public/TandC/Cicar.html",
                        $item1->TandCURL ?? "",
                        $item1->FuelPolicy ?? "",
                        $item1->ExcessPolicy ?? "",
                        $item1->ERP ?? 0,
                        $item1->idOp ?? "",
                        $item1->Codigo ?? "",
                        $item2->TotalPVPChargeLCCoin ?? ""
                    );

                    $vehicleTypes[] = $vehicleData;
                }
            }
        }

        return $vehicleTypes; // Always return an array
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
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
            'FechaInicio' => $request->input('FechaInicio'). '' . $request->input('HoraInicio'),
            'FechaFin' => $request->input('FechaFin'). '' . $request->input('HoraFin'),
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
