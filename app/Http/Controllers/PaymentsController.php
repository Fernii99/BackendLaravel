<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentsController extends Controller
{
   public function verificarDiasDePago(Request $request)
{
    $year = $request->input('year');
    $fecha = $request->input('day');

    // Validate inputs
    if (!$year || !$fecha) {
        return response()->json(['error' => 'Year and Day are required'], 400);
    }

    // Validate and parse the input date
    $fecha = DateTime::createFromFormat('Y-m-d', $fecha);
    if (!$fecha) {
        return response()->json(['error' => 'Invalid date format'], 400);
    }

    // Fetch holiday data
    $url = sprintf(
        'https://opendata.euskadi.eus/contenidos/ds_eventos/calendario_laboral_%d/opendata/calendario_laboral_%d.json',
        $year,
        $year
    );
    $response = Http::get($url);

    if (!$response->successful()) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch holiday data.',
        ]);
    }

    // Filter and format holiday data
    $festivos = collect($response->json())
        ->where('territory', 'Todos/denak')
        ->map(function ($holiday) {
            return DateTime::createFromFormat('Y/m/d', $holiday['date']);
        })
        ->filter()
        ->map(function ($date) {
            return $date->format('Y/m/d');
        })
        ->unique()
        ->toArray();

    // Generate weekends for the year
    $weekends = $this->generateSaturdaySundaysYear($year);

    // Merge holidays and weekends
    $festivosMerge = array_values(array_unique(array_merge($festivos, $weekends)));
    sort($festivosMerge);

    // Find the next non-holiday, non-weekend day
    $nextWorkingDay = $this->findNextWorkingDay($fecha, $festivosMerge);

    return response()->json([
        'festivos' => $festivosMerge,
        'noFestivos' => [$nextWorkingDay],
        'message' => 'El próximo día hábil para realizar el pago es:',
        'status' => 200,
    ]);
}

/**
 * Generate an array of all Saturdays and Sundays for a given year.
 */
private function generateSaturdaySundaysYear($year)
{
    $weekends = [];
    $startDate = new DateTime("$year-01-01");
    $endDate = new DateTime("$year-12-31");

    while ($startDate <= $endDate) {
        if (in_array($startDate->format('N'), [6, 7])) {
            $weekends[] = $startDate->format('Y/m/d');
        }
        $startDate->modify('+1 day');
    }

    return $weekends;
}

/**
 * Find the next working day that is not a holiday or weekend.
 */
private function findNextWorkingDay(DateTime $fecha, array $festivosMerge)
{
    $currentDate = clone $fecha;

    while (true) {
        $currentDate->modify('+1 day');
        $currentDateStr = $currentDate->format('Y/m/d');

        if (!in_array($currentDateStr, $festivosMerge)) {
            return $currentDateStr;
        }
    }
}
}

