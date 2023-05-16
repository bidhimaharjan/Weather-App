<?php
// include the database connection
include 'connection.php';

// fetches the Weather API for Weather Data of Last 7 Days using timestamp
function get_7days_data($city_name, $timestamp){
    include 'connection.php';

    $api_key = 'e15a64935cbe47b61014d31f36d4a6c2';
    // encodes the city name before passing into the url
    $encoded_city_name = urlencode($_GET['cityname']);

    $api_url = "https://history.openweathermap.org/data/2.5/history/city?q=$encoded_city_name&type=hour&start=$timestamp&cnt=1&appid=$api_key&units=metric";
    $response = file_get_contents($api_url);
    // decodes the fetched JSON string to a PHP object
    $data = json_decode($response);

    if (!$data) {
        // displays error message if no data is found
        echo "<p>No record found for this city!</p>";
    } else {
        // fetches the required API data
        foreach ($data->list as $weather_data) {
            // extracts the relevant weather data from the PHP object
            $date = date('Y-m-d', $weather_data->dt);
            $weathercondition = $weather_data->weather[0]->description;
            $icon = $weather_data->weather[0]->icon;
            $temperature = $weather_data->main->temp;
            $maxtemp = $weather_data->main->temp_max;
            $mintemp = $weather_data->main->temp_min;
            $windspeed = $weather_data->wind->speed;
            $pressure = $weather_data->main->pressure;
            $humidity = $weather_data->main->humidity;

            if ($connection) {
                // inserts the fetched data into the table
                $insert_query = "INSERT INTO WeatherData (city, DATE, weathercondition, icon, temperature, maxtemp, mintemp, windspeed, pressure, humidity)
                                VALUES('$city_name', '$date', '$weathercondition', '$icon', $temperature, $maxtemp, $mintemp, $windspeed, $pressure, $humidity)";
                $insert_sql = mysqli_query($connection, $insert_query);  
            }
        }
    }
}

function get_7days_data_main() {
    include 'connection.php';
    // gets the city_name from JS
    $city_name = $_GET['cityname'];

    // checks if the weather data of the city already exists in the database
    $select_query = "SELECT * FROM WeatherData WHERE city='$city_name'";
    $select_sql = mysqli_query($connection, $select_query);
    $num_rows = mysqli_num_rows($select_sql);

    // if data exists, fetches the data from the database and returns it in JSON object (ready to be fetched by JavaScript)
    if ($num_rows > 0) {
        $select_query = "SELECT * FROM WeatherData WHERE city='$city_name'";
        $select_sql = mysqli_query($connection, $select_query);
        $rows = mysqli_num_rows($select_sql);

        if ($rows) {
            $data_array = array();
            while ($database_data = mysqli_fetch_assoc($select_sql)) {
                $data_array[] = $database_data;
            }
            echo json_encode($data_array);
        } 
    // else if data does not exist, fetches new data from API, inserts into database and returns it
    } else {
        // loop of 7 function calls to get 7 days weather data using timestamp
        for ($days = 1; $days <= 7; $days++){
            $timestamp = strtotime("$days days ago"); // to pass the timestamp into the API url
            get_7days_data($city_name, $timestamp);
        }
        
        $select_query = "SELECT * FROM WeatherData WHERE city='$city_name'";
        $select_sql = mysqli_query($connection, $select_query);
        $rows = mysqli_num_rows($select_sql);

        if ($rows) {
            $data_array = array();
            while ($database_data = mysqli_fetch_assoc($select_sql)) {
                $data_array[] = $database_data;
            }
            echo json_encode($data_array);
        }      
    }
}

get_7days_data_main();

?>