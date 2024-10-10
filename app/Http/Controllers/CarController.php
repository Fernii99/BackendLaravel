<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class CarController extends Controller
{
    public function index()
    {
        // Fetch all cars from the database
        // $cars = Car::with(['brand', 'brands'])->get();
        $cars = Car::join('brands', 'cars.brand', '=', 'brands.id')
        ->select('brands.name as brand_name', 'cars.id', 'cars.model', 'cars.image', 'cars.type', 'cars.color', 'cars.manufacturingYear', )
        ->get();
        // Return the cars as a JSON response
        return response()->json($cars);
    }


    public function show($id)
    {
        // $car = Car::find($id);
        $car = Car::with(['comments'])->find($id);

        if ($car) {
            return response()->json($car);
        } else {
            return response()->json(['message' => 'Car not found'], 404);
        }
    }


}
