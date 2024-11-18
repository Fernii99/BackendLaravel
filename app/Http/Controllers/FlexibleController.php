<?php

namespace App\Http\Controllers;

use App\Services\Vehicle;

class FlexibleController extends Controller
{
    public function ObtenerListaDeVehiculos() {

        $filePath = storage_path('fakePetition.json');
        $jsonData = file_get_contents($filePath);
        $vehicles = json_decode($jsonData, true);


        $vehicleModel = new Vehicle();
        $vehicleTypes = []; // Array to store vehicle types

        foreach ($vehicles as $vehicle) {
           $vehicleData = $vehicleModel->addVehicle(
                $vehicle['InsertStatId'] ?? null,
                $vehicle['Status'] ?? null,
                $vehicle['Category'] ?? null,
                $vehicle['Size'] ?? null,
                $vehicle['Name'] ?? null,
                $vehicle['Code'] ?? null,
                $vehicle['img'] ?? null,
                $vehicle['Luggage'] ?? null,
                (string)$vehicle['Passenger'] ?? null,
                $vehicle['Doors'] ?? null,
                $vehicle['TipoTarifa'] ?? null,
                (string)$vehicle['TotalCharge'] ?? null,
                $vehicle['TotalMargenDitChargeS'] ?? null,
                $vehicle['SupImg'] ?? null,
                $vehicle['TotalPVPCharge'] ?? null,
                $vehicle['Currency'] ?? null,
                $vehicle['RateQualifier'] ?? null,
                $vehicle['IsAirCon'] ?? null,
                $vehicle['IsAutomatic'] ?? null,
                $vehicle['CarType'] ?? null,
                $vehicle['CarDescription'] ?? null,
                $vehicle['Features'] ?? null,
                $vehicle['SupplierCode'] ?? null,
                $vehicle['Supplier'] ?? "",
                $vehicle['SupplierDetails'] ?? null,
                $vehicle['LocationType'] ?? null,
                $vehicle['FuelSurChargeVal'] ?? null,
                $vehicle['FuelSurChargeCur'] ?? null,
                $vehicle['ExcessVal'] ?? null,
                $vehicle['ExcessCur'] ?? null,
                $vehicle['FuelChargeVal'] ?? null,
                $vehicle['FuelChargeCur'] ?? null,
                $vehicle['Anotation'] ?? null,
                $vehicle['DropCharge'] ?? null,
                $vehicle['DropChargeCurrency'] ?? null,
                $vehicle['ProductCostsL'] ?? null,
                $vehicle['ProductCostsS'] ?? null,
                $vehicle['TandCURL'] ?? null,
                $vehicle['FuelPolicy'] ?? null,
                $vehicle['ExcessPolicy'] ?? null,
                $vehicle['ERP'] ?? null,
                $vehicle['idOp'] ?? null,
                $vehicle['InsertId'] ?? null,
                $vehicle['TotalPVPChargeLCCoin'] ?? null
            );

           // Add each vehicle's data to the array
           $vehicleTypes[] = $vehicleData;
        }

        return $vehicleTypes; // Return array of vehicle items
    }

}
