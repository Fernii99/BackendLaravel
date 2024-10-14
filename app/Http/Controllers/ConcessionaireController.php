<?php

namespace App\Http\Controllers;

use App\Models\Concessionaire;
use Illuminate\Http\Request;

use App\Models\Car;
use App\Models\Brand;

class ConcessionaireController extends Controller
{
    public function index()
    {
        // Eager load cars and brands to minimize database queries
        $concessionaires = Concessionaire::with(['Cars.comments', 'Brand'])->get();

        return response()->json($concessionaires);
    }

    public function find($id)
    {
        $concessionaire = Concessionaire::with(['Cars.comments', 'Brand'])->find($id);

        // Check if the concessionaire exists
        if (!$concessionaire) {
            return response()->json(['error' => 'Concessionaire not found'], 404);
        }

        return response()->json($concessionaire);
    }
}
