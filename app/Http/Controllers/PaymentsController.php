<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentsController extends Controller
{
    // Function to verify if the next 3 days are not holidays or weekends
    public function verificarDiasDePago(Request $request)
    {
        $year = $request->input('year');
        $fecha = $request->input('day');

        // Validate inputs
        if (!$year || !$fecha) {
            return response()->json(['error' => 'Year and Day are required'], 400);
        }

        // Validate date format
        $fecha = DateTime::createFromFormat('Y-m-d', $fecha); // Assuming frontend sends date as 'Y-m-d'
        if (!$fecha) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        // Fetch holiday data
        $url = 'https://opendata.euskadi.eus/contenidos/ds_eventos/calendario_laboral_' . $year . '/opendata/calendario_laboral_' . $year . '.json';
        $response = Http::get($url);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch holidays data.',
            ]);
        }

        $jsonData = $response->json();

        // Filter holidays based on the territory
        $festivos = array_filter($jsonData, function ($holiday) {
            return in_array($holiday['territory'], ['Todos/denak']);
        });

        // Standardize holiday dates to the 'Y/m/d' format
        $festivos = array_map(function ($holiday) {
            $date = DateTime::createFromFormat('Y/m/d', $holiday['date']);
            return $date ? $date->format('Y/m/d') : null;
        }, $festivos);

        // Remove null values (if any)
        $festivos = array_filter($festivos);

        // Generate weekends for the year
        $weekends = $this->generateSaturdaySundaysYear($year);

        // Merge holidays and weekends into one array
        $festivosMerge = array_merge($festivos, $weekends);

        // Remove duplicates and standardize the format
        $festivosMerge = array_map(function ($date) {
            return DateTime::createFromFormat('Y/m/d', $date)->format('Y/m/d');
        }, $festivosMerge);

        // Remove duplicates
        $festivosMerge = array_unique($festivosMerge);
    sort($festivosMerge, );


        // Find the next non-holiday, non-weekend day
        $dia_no_festivo = [];
        $currentDate = clone $fecha;

        while (count($dia_no_festivo) < 1) {
            $currentDate->modify('+1 day');
            $currentDateStr = $currentDate->format('Y/m/d'); // Standardize date format

            // Check if the day is not in the merged holidays/weekends array
            if (!in_array($currentDateStr, $festivosMerge)) {
                $dia_no_festivo[] = $currentDateStr;
            }
        }

        return response()->json([
            'festivos' => $festivosMerge,
            'noFestivos' => $dia_no_festivo,
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
        $startDate = new DateTime("$year/01/01");
        $endDate = new DateTime("$year/12/31");

        while ($startDate <= $endDate) {
            $dayOfWeek = $startDate->format('N'); // 6 for Saturday, 7 for Sunday
            if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                $weekends[] = $startDate->format('Y/m/d');
            }
            $startDate->modify('+1 day');
        }

        return $weekends;
    }
}

