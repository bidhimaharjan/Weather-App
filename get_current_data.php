<?php
// include the database connection
include 'connection.php';
// variable for the CurrentData table of the database that stores all the fetched data
$tablename = 'CurrentData';

$city_name = $_GET['city_name']; // gets the city_name from JS

if ($connection) {
    // checks if the weather data of the city already exists in the database
    $select_query = "SELECT * FROM $tablename WHERE city='$city_name'";
    $select_sql = mysqli_query($connection, $select_query);
    $num_rows = mysqli_num_rows($select_sql);

    // if data exists, fetches the data from the database and returns it in JSON object (ready to be fetched by JavaScript)
    if ($num_rows > 0) {
        $select_query = "SELECT * FROM $tablename1 WHERE city='$city_name'";
        $select_sql = mysqli_query($connection, $select_query);
        $database_data = mysqli_fetch_assoc($select_sql);
        echo json_encode($database_data);
    // else if data does not exist, fetches new data and returns it
    } else {
        $api_key = '16c4c29d33efe4324aad58b0a0cbab91';        
        $encoded_city_name = urlencode($_GET['city_name']); // encodes the city name before passing into the url
        $api_url = "https://api.openweathermap.org/data/2.5/weather?q=${encoded_city_name}&appid=${api_key}&units=metric";
        $response = file_get_contents($api_url);
        // decodes the fetched JSON string to a PHP object
        $weather_data = json_decode($response);

        // extracts the relevant weather data from the PHP object
        $city = $weather_data->name;
        $country = $weather_data->sys->country;
        $date = date('Y-m-d', $weather_data->dt);
        $icon = $weather_data->weather[0]->icon;
        $weathercondition = $weather_data->weather[0]->description;
        $temperature = $weather_data->main->temp;
        $maxtemp = $weather_data->main->temp_max;
        $mintemp = $weather_data->main->temp_min;
        $windspeed = $weather_data->wind->speed;
        $pressure = $weather_data->main->pressure;
        $humidity = $weather_data->main->humidity;
        
        // inserts the new weather data
        $insert_query = "INSERT INTO $tablename (city, country, DATE, icon, weathercondition, temperature, maxtemp, mintemp, windspeed, pressure, humidity)
                        VALUES('$city', '$country', '$date', '$icon', '$weathercondition', $temperature, $maxtemp, $mintemp, $windspeed, $pressure, $humidity)";
        $insert_sql = mysqli_query($connection, $insert_query);

        // fetches newly inserted data from the database and returns it in JSON object (ready to be fetched by JavaScript)
        $select_query = "SELECT * FROM $tablename WHERE city='$city_name'";
        $select_sql = mysqli_query($connection, $select_query);
        $database_data = mysqli_fetch_assoc($select_sql);
        echo json_encode($database_data);
    }
}               
?>
