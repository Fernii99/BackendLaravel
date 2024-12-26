<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Date Picker</title>
    </head>
    <body>
        <h2>Select a Date to Check the Next 3 Days</h2>

        <form action="calculadora.php" method="POST">
            <label for="fecha">Selecciona el año a calcular:</label>
            <input type="number" id="year" name="year" required>

            <label for="fecha">Choose a date:</label>
            <input type="date" id="fecha" name="fecha" required>

            <input type="submit" value="Check Days">
        </form>

    </body>
</html>
