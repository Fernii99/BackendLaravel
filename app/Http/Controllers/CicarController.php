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
            'FechaInicio' => $request->input('FechaInicio'.'HoraInicio', date("Y-m-d H:i:s", strtotime("+1 days"))),
            'FechaFin' => $request->input('FechaFin'.'HoraFin', date("Y-m-d H:i:s", strtotime("+2 days"))),
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
        $vehicleModel = new Vehicle();
        $availableModels = [];
        $vehicleTypes = []; // Array to store vehicle types

        foreach ($result2->ModeloDisponibleArray as $item2) {
            $availableModels[$item2->Codigo] = $item2;
        }

        foreach ($result->ModeloArray as $item1) {
            if (isset($availableModels[$item1->Codigo])) {
                $item2 = $availableModels[$item1->Codigo];

                // Add vehicle data with the matching item
                $vehicleData = $vehicleModel->addVehicle(
                    $item1->Codigo ?? "",
                    $item1->Disponible ? "Available" : "NotAvailable",
                    $item1->Categoria ??  "",
                    $item1->Portabultos ?? "",
                    $item1->Nombre ?? "",
                    $item1->Codigo ?? "",
                    $item1->Foto ?? "",
                    $item1->Capacidad ?? "",
                    $item1->Pax ?? "",
                    $item1->Puertas ?? "",
                    $item2->BaseImponible ?? "",
                    $item2->Total ?? "",
                    $item2->TotalMargenDitChargeS ?? "",
                    $item1->SupImg ?? "",
                    $item2->TotalPVPCharge ?? "",
                    $item2->Currency ?? "EUR",
                    $item1->RateQualifier ?? "",
                    $item1->Aire ? "Y" : "N",
                    $item1->Direccion ? "Y" : "N",
                    $item2->Grupo ? "" : "",
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
                        "FUEL - Full to Full ",
                        "EXCESS - Standard Excess ",
                        "OTHER COSTS - Excess:775:EUR,Deposit:975:EUR ",
                        "CANCELLATIONS - No se aplican gastos ",
                        "NO SHOWS - No se aplican gastos "
                    ],
                    $item1->DropCharge ?? 0,
                    $item1->DropChargeCurrency ?? "EUR",
                    $item1->ProductCostsL ?? "",
                    $item1->ProductCostsS ?? "https://prodxml-2.vpackage.net/coches/public/TandC/Cicar.html",
                    $item1->TandCURL ?? "",
                    $item1->FuelPolicy ?? "Los coches se entregar\u00e1n con el tanque lleno y se tendr\u00e1n que devolver con el tanque lleno. De lo contrario el cliente tendr\u00e1 que pagar por el combustible que falta, m\u00e1s un suplemento por el servicio de repostaje.Es importante que justo antes de devolver el coche, el cliente reposte a menos de 10 km aprox de distancia de la oficina de devoluci\u00f3n y que conserve el ticket de caja de la gasolinera.",
                    $item1->ExcessPolicy ?? "El importe de la Franquicia por da\u00f1os y robo es (tasas locales no incluidas): - MBMR, MBAR, ECMR, EDAH - 670.00 EURCDMR, CGMH, DCMR, CBMR, EXMR - 775.00 EURIMMR, IVMR, JGMR - 790.00 EURNBAE, EDAE, IMAR, DDAH, CCAR - 805.00 EURIDMR - 840.00 EURETMN - 880.00 EURLVMR - 980.00 EURFVMR, CDAE - 1015.00 EURIFMR, DGAR, IGAH - 1080.00 EURJDAV, RGDR, SGAR, JFDR, GFAR, IGAR - 1800.00 EURUFAR - 2200.00 EUR ",
                    $item1->ERP ?? 0,
                    $item1->idOp ?? "",
                    $item1->Codigo ?? "",
                    $item2->TotalPVPChargeLCCoin ?? ""
                );
                $vehicleTypes[] = $vehicleData;
            }
        }
            return $vehicleTypes;
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
