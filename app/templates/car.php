<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add a new car</title>

        <style>
            .updateBtn {
                display: inline-block;
                padding: 20px;
                background-color: turquoise;
                color: white;
                text-decoration: none;
                font-size: 18px;
                margin-top: 20px;
            }

            .deleteBtn {
                display: inline-block;
                padding: 20px;
                background-color: crimson;
                color: white;
                text-decoration: none;
                font-size: 18px;
                margin-top: 20px;
            }
        </style>

    </head>

    <body>

        <h1><?= $car->brand . ' ' . $car->model ?></h1><br />
        <span> <strong>Chasis number: </strong> <?= $car->chasis_number ?></span><br />
        <span><strong>Weight: </strong><?= $car->car_weight ?> kg</span><br />
        <span> <strong>Country of origin: </strong> <?= $car->country_of_origin ?></span><br />
        <span><strong>Top speed: </strong><?= $car->top_speed ?></span><br />
        
        <a class="updateBtn" href="/updateCar?id=<?=$car->id?>">Update</a>
        <a class="deleteBtn" href="/deleteCar?id=<?=$car->id?>">Delete</a>
    </body>
</html>