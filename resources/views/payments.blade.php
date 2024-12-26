<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <title>Date Picker</title>
    </head>
    <body>

        <h2>Select a Date to Check the Next 3 Days</h2>

        <form action="{{ route('verifyPayments') }}" method="GET">
            @csrf

            <label for="year">Año a calcular:</label>
            <input type="number" id="year" name="year" required>
            <br>

            <label for="fecha">Selecciona Fecha:</label>
            <input type="date" id="fecha" name="fecha" required>
            <br>

            <input type="submit" value="Verify Payments">
        </form>

        @if (session('message'))
            <h3>{{ session('message') }}</h3>
        @endif

        @if (session('error'))
            <h3 style="color: red;">{{ session('error') }}</h3>
        @endif

    </body>
</html>
