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
        // Eager load cars and brands to minimize database queries
        $concessionaires = Concessionaire::with(['Cars', 'Brand'])->find($id);

        return response()->json($concessionaires);
    }
}
