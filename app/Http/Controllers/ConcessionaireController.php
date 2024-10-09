<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Concessionaire;

class ConcessionaireController extends Controller
{
    public function index()
    {
        // Eager load cars and brands to minimize database queries
        $concessionaires = Concessionaire::get();

        return response()->json($concessionaires);
    }
}
