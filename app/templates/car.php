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

        <form action="/storeCar" method="POST">
            <label for="brand">Brand</label>
            <input name="brand" type="text" required/><br />


            <label for="model">Model</label>
            <input name="model" type="text" required/><br />

            <label for="color">Color</label>
            <input name="color" type="text" /><br />

            <label for="car_weight">Weight</label>
            <input name="car_weight" type="number"/><br />

            <label for="top_speed">Top speed</label>
            <input name="top_speed" type="number"/><br />

            <label for="country_of_origin">Country of origin</label>
            <input name="country_of_origin" type="text" required/><br />
        
            <button type="submit">Submit</button>
        </form>

    </body>
</html>