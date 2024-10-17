<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Comment;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{

    //FIND ALL THE CARS STORED IN THE DATABASE
    public function index()
    {
        // Fetch all cars from the database
        // $cars = Car::with(['brand', 'brands'])->get();
        $cars = Car::join('brands', 'cars.brand', '=', 'brands.id')
        ->select('brands.name as brand_name', 'brands.id as brand_id', 'cars.id', 'cars.car_model', 'cars.image', 'cars.type', 'cars.color', 'cars.manufacturingYear', )
        ->get();
        // Return the cars as a JSON response
        return response()->json($cars);
    }

    public function findConcessionaireComments($id)
    {
        $comments = Comment::whereHas('car', function ($query) use ($id) {
            $query->where('concessionaire_id', $id);
        })
        ->orderBy('created_at', 'desc') // Assuming you want to order by the creation date of the comments
        ->get();

        if ($comments) {
            return response()->json($comments);
        } else {
            return response()->json(['message' => 'no comments for this car'], 404);
        }
    }


    //FIND ONE CAR INFORMATION WITH ITS COMMENTS
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'color' => 'required|string|max:50',
            'manufacturingYear' => 'required|integer',
            'type' => 'required|string|max:50',
        ]);

        // Check if an image is uploaded
        if ($request->hasFile('image')) {
            // Store the image in the 'public/cars' directory
            $imagePath = $request->file('image')->store('public/storage/carImages');

            // Get the URL to the stored image
            $imageUrl = 'http://localhost:8000' . Storage::url($imagePath);

            // Save the image URL and other details in the database
            $car = new Car();
            $car->brand = $request->brand;
            $car->car_model = $request->model;
            $car->color = $request->color;
            $car->image = $imageUrl;
            $car->manufacturingYear = $request->manufacturingYear;
            $car->type = $request->type;
            $car->concessionaire_id = $request->concessionaire_id ?? 2;
            $car->save();

            return redirect()->back()->with('success', 'Car added successfully!');
        }

        return redirect()->back()->with('error', 'Image upload failed!');
    }

    public function update(Request $request, $id){
        $car = Car::findOrFail($id);

        $car->update( [
            //DATABASE FIELD NAME => REQUEST FIELD NAME
            'brand' => is_numeric($request->input('brand')) ? $request->input('brand') : $car->brand,
            'car_model' => $request->input('model'),
            'type' => $request->input('type'),
            'color' => $request->input('color'),
            'manufacturingYear' => $request->input('manufacturingYear'),
            'created_at' => $car->created_at,
            'updated_at' => $car->updated_at
        ]);

        return response()->json([
            'message' => 'Car updated successfully!',
            'car' => $car,
        ], 200);
    }


}
