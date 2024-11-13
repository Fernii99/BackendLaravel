<?php

namespace App\Services;

class Vehicle
{
    public $data;


    // Constructor
    public function __construct()
    {
        $this->data = [];              // Initialize as an empty array
    }

    public function addVehicle($InsertStatId,$Status,$Category,$Size,$Name,$Code,$img,
   $Luggage,$Passenger,$Doors,$TipoTarifa,$TotalCharge,$TotalMargenDitChargeS,$SupImg,
   $TotalPVPCharge,$Currency,$RateQualifier,$IsAirCon,$IsAutomatic,$CarType,$CarDescription,
   $Features,$SupplierCode,$Supplier,$SupplierDetails,$LocationType,$FuelSurChargeVal,
   $FuelSurChargeCur,$ExcessVal,$ExcessCur,$FuelChargeVal,$FuelChargeCur,$Anotation, $DropCharge,
   $DropChargeCurrency,$ProductCostsL,$ProductCostsS,$TandCURL,$FuelPolicy,$ExcessPolicy,
   $ERP,$idOp,$InsertId,$TotalPVPChargeLCCoin)
    {
        $vehicleData = [              // Append Vehicle data to the data array
            'InsertStatId' => $InsertStatId,
            'Status' => $Status,
            'Categoria' => $Category,
            'Size' => $Size,
            'Nombre' => $Name,
            'Code' => $Code,
            'Foto' => $img,
            'Maletas' => $Luggage,
            'Capacidad' => $Passenger,
            'Puertas' => $Doors,
            'TipoTarifa' => $TipoTarifa,
            'Total' => $TotalCharge,
            'TotalMargenDitChargeS' => $TotalMargenDitChargeS,
            'SupImg' => $SupImg,
            'TotalPVPCharge' => $TotalPVPCharge,
            'Currency' => $Currency,
            'RateQualifier' => $RateQualifier,
            'Aire' => $IsAirCon,
            'IsAutomatic' => $IsAutomatic,
            'CarType' => $CarType,
            'CarDescription' => $CarDescription,
            'Features' => $Features,
            'SupplierCode' => $SupplierCode,
            'Supplier' => $Supplier,
            'SupplierDetails'=> $SupplierDetails,
            'LocationType' => $LocationType,
            'FuelSurChargeVal' => $FuelSurChargeVal,
            'FuelSurChargeCur' => $FuelSurChargeCur,
            'ExcessVal' => $ExcessVal,
            'ExcessCur' => $ExcessCur,
            'FuelChargeVal' => $FuelChargeVal,
            'FuelChargeCur' => $FuelChargeCur,
            'Anotation' => $Anotation,
            'DropCharge' => $DropCharge,
            'DropChargeCurrency' => $DropChargeCurrency,
            'ProductCostsL' => $ProductCostsL,
            'ProductCostsS' => $ProductCostsS,
            'TandCURL' => $TandCURL,
            'FuelPolicy' => $FuelPolicy,
            'ExcessPolicy' => $ExcessPolicy,
            'ERP' => $ERP,
            'idOp' => $idOp,
            'InsertId' => $InsertId,
            'TotalPVPChargeLCCoin' => $TotalPVPChargeLCCoin,
        ];

        $this->data[] = $vehicleData;

        // Return the vehicle data so it can be used immediately
        return $vehicleData;
    }

    // Method
    public function getResponse()
    {

        // Structure the response
        $response = [
            'data' => $this->data,      // Character array
        ];

        // Return as JSON
        return response()->json($response);

    }
}
