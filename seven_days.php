<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- CSS -->
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
            body{
                font-family: 'Poppins', sans-serif;
                background-color: rgb(136, 198, 252);
                color: rgb(255, 255, 255);
                text-align: center;
            }

            .home-button{
                float: left;
                background-color: rgb(0, 0, 0);
                border: none;
                text-decoration: none;
                padding: 1rem 1rem;
                font-size: 1rem;
                margin: 2px;
                cursor: pointer; 
                border-radius: 8px;
            }

            .home-button:hover{
                background: rgba(0, 0, 0, 0.2); 
            }

            .home-button a{
                text-decoration: none;
                color: rgb(255, 255, 255);
            }

            .table-container{
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .seven-days-data{
                background:rgba(0, 0, 0, 0.2); 
                border-radius: 18px;
            }

            th{
                font-size: 1.2rem;
                font-weight: bolder;
            }
        </style>

        <title>Weather</title>
    </head>

    <body>
        <?php
        // include the PHP scripts with database connection and fetched & stored last 7 days data
        include 'connection.php';
        include 'get_past_data.php';
        ?>

        <header>
            <!-- Home Button to exit the Last Week Weather page -->
            <button class="home-button"><a href="BidhiMaharjan_2330526.html">Home</a></button>
            <h2><?php echo "Last 7 Days Weather Data of " . ucwords($city_name) ?></h2>
        </header>
        
        <main class="table-container">
            <?php
            // retrieve the stored data from WeatherData to display on DOM as a table
            $select_query = "SELECT * FROM `WeatherData`";
            $select_sql = mysqli_query($connection, $select_query);
            $num_rows = mysqli_num_rows($select_sql);
            
            if ($num_rows) {
                ?>
                <table class="seven-days-data" cellspacing="20" cellpadding="8px">
                    <tr>
                        <th>Date</th>
                        <th>Weather Condition</th>
                        <th>Temperature</th>
                        <th>Highest</th>
                        <th>Lowest</th>
                        <th>Windspeed</th>
                        <th>Pressure</th>
                        <th>Humidity</th>
                    </tr>
                    <?php
                    // retrieve until all weather data of last 7 days are successfully displayed
                    while ($row = mysqli_fetch_assoc($select_sql)) {
                        ?>
                        <tr>
                            <td> <?php echo $row['DATE'] ?> </td>
                            
                            <td> <?php $fetch_icon = $row['icon'];
                                $weather_condition = $row['weathercondition'];
                                echo $weather_condition . "  <img src='http://openweathermap.org/img/wn/$fetch_icon@2x.png' width='50' height='50' align='center'>"?>
                            </td>
                            <td> <?php echo $row['temperature'] . "°" ?> </td>
                            <td> <?php echo $row['maxtemp'] . "°" ?> </td>
                            <td> <?php echo $row['mintemp'] . "°" ?> </td>
                            <td> <?php echo $row['windspeed'] . " km/h" ?> </td>
                            <td> <?php echo $row['pressure'] . " Pa" ?> </td>
                            <td> <?php echo $row['humidity'] . "%" ?> </td>              
                        </tr>
                        <?php
                    }
                } else {
                    // if no data found, display error message
                    ?>
                    <tr>
                        <td>No record found!</td>
                    </tr>
                </table>
                <?php
            }
            ?>
        </main>
    </body>
</html>
