<?php
    include_once("DB/Connection.php");
    include_once("Models/Car.php");
    require 'vendor/autoload.php';

    use App\DB\Connection;
    use App\Models\Car;
    use Carbon\Carbon;

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
    <body>
        <?php
    
            $conn = new Connection('database.db');
            $db = $conn->connect();
            
            //Car::createTable($db);

            /*
            $car = new Car();
            $car->brand = 'BMW';
            $car->model = 'Series 3';
            $car->color = 'Black';
            $car->car_weight = 1418.5;
            $car->top_speed = 235;
            $car->country_of_origin = "Germany";
            $car->save($db);
            */
            

            $car = Car::find($db, 1);
            $car->color = 'Grey';
            
            $car->update($db);

        ?>
    </body>
</html>