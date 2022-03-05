<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add a new car</title>

        <style>
            input{
                margin-top: 20px;
            }
            button {
                margin-top: 20px;
                font-size: 20px;
            }
        </style>

    </head>

    <body>

        <h1><?= $car->brand . ' ' . $car->model ?></h1><br />
        <h1><?= $car->chasis_number ?></h1>
        <h1><?= $car->car_weight ?></h1>
        <h1><?= $car->country_of_origin ?></h1>
        <h1><?= $car->top_speed ?></h1>

    </body>
</html>