<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index()
    {
        // Fetch all cars from the database
        // $cars = Car::with(['brand', 'brands'])->get();
        $cars = Car::join('brands', 'cars.brand', '=', 'brands.id')
        ->select('brands.name as brand_name', 'cars.id', 'cars.car_model', 'cars.image', 'cars.type', 'cars.color', 'cars.manufacturingYear', )
        ->get();
        // Return the cars as a JSON response
        return response()->json($cars);
    }


    public function show($id)
    {
        // $car = Car::find($id);
        $car = Car::with(['comments'])->find($id);
        $brand = Brand::find($car->brand);

        if($brand){
            $result = [
                'brand' => $brand->name,
                'model' => $car->car_model,
                'type' => $car->type,
                'color' => $car->color,
                'manufacturingYear' => $car->manufacturingYear,
                'comments' => $car->Comments,
                'image' => $car->image,
            ];
        }

        if ($car) {
            return response()->json($result);
        } else {
            return response()->json(['message' => 'Car not found'], 404);
        }
    }



    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // optional validation
            // other validation rules for fields
        ]);

        // Check if an image is uploaded
        if ($request->hasFile('image')) {
            // Store the image in the 'public/cars' directory
            $imagePath = $request->file('image')->store('carImages', 'public');

            // Get the URL to the stored image
            $imageUrl = Storage::url($imagePath);

            // Save the image URL and other details in the database
            $car = new Car();
            $car->brand = $request->brand; // example of other fields
            $car->car_model = $request->model; // example of other fields
            $car->color = $request->color;
            $car->image = $imageUrl; // example of other fields
            $car->manufacturingYear = $request->manufacturingYear;
            $car->type = $request->type;
            $car->concessionaire_id = 2; // save the image URL
             // save the image URL
            $car->save();

            return redirect()->back()->with('success', 'Car added successfully!');
        }

        return redirect()->back()->with('error', 'Image upload failed!');
    }


}
