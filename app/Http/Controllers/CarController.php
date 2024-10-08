<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class CarController extends Controller
{
    public function index()
    {
        // Fetch all cars from the database
        $cars = Car::all();

        // Return the cars as a JSON response
        return response()->json($cars);
    }


    public function show($id)
    {
        $car = Car::with('comments')->find($id);

        if ($car) {
            return response()->json($car);
        } else {
            return response()->json(['message' => 'Car not found'], 404);
        }
    }


}
