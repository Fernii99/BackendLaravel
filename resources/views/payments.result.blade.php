<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <title>Verification Results</title>
</head>
<body>

    <h2>Verification Results</h2>

    @if (!empty($dias_no_festivos))
        <p>The next 3 days that are not holidays or weekends are:</p>
        <ul>
            @foreach ($dias_no_festivos as $dia)
                <li>{{ $dia }}</li>
            @endforeach
        </ul>
    @else
        <p>No available days. Some of the next 3 days are holidays or weekends.</p>
    @endif

    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <a href="{{ route('verifyPayments') }}">Go back</a>

</body>
</html>
