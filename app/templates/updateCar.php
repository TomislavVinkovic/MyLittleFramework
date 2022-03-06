<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update <?=$car->brand?> <?=$car->model?></title>
        <link rel="stylesheet" href="css/app.css">

    </head>

    <body>
        <form action="/patchCar" method="POST">
            
            <label for="brand">Brand</label>
            <input name="brand" type="text" value="<?=$car->brand?>" required/><br />


            <label for="model">Model</label>
            <input name="model" type="text" value="<?=$car->model?>" required/><br />

            <label for="color">Color</label>
            <input name="color" type="text" value="<?=$car->color?>" /><br />

            <label for="car_weight">Weight</label>
            <input name="car_weight" value="<?=$car->car_weight?>" type="number"/><br />

            <label for="top_speed">Top speed</label>
            <input name="top_speed" value="<?=$car->top_speed?>" type="number"/><br />

            <label for="country_of_origin">Country of origin</label>
            <input name="country_of_origin" value="<?=$car->country_of_origin?>" type="text" required/><br />
            
            <input type="hidden" name="id" value="<?=$car->id?>">

            <button type="submit">Submit</button>
        </form>

    </body>
</html>