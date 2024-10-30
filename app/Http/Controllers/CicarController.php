<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;

class cicarController extends Controller
{
    public function obtenerListaDeZonas()
    {
        $wsdl = 'https://extranet.cicar.com/webservices/soap/wsreservas.dll/wsdl/IReservas';

        try {
            // Create a new SoapClient instance
            $client = new SoapClient($wsdl, [
                'trace' => 1,    // Enables tracing for debugging
                'exceptions' => true,
            ]);

            // Call the ObtenerListaDeZonas operation
            $result = $client->__soapCall('ObtenerListaDeZonas', (array)["ES"]);

            return response()->json($result);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
