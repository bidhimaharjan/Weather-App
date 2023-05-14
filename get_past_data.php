<?php
// include the database connection
include 'connection.php';

// fetches the Weather API for Weather Data of Last 7 Days using timestamp
function get_7days_data($city_name, $timestamp){
    include 'connection.php';
    // variable for the WeatherData table of the database that stores all the fetched data
    $tablename2 = 'WeatherData';

    $api_key = 'e15a64935cbe47b61014d31f36d4a6c2';
    $api_url = "https://history.openweathermap.org/data/2.5/history/city?q=$city_name&type=hour&start=$timestamp&cnt=1&appid=$api_key&units=metric";
    $response = file_get_contents($api_url);
    // decodes the fetched JSON string to PHP variable
    $data = json_decode($response);

    if(!$data){
        // displays error message if no data is found
        "<p>No record found for this city!</p>";
    }
    
    // fetches the required API data
    foreach ($data->list as $weather_data) {
        $date = date('Y-m-d', $weather_data->dt);
        $weathercondition = $weather_data->weather[0]->description;
        $icon = $weather_data->weather[0]->icon;
        $temperature = $weather_data->main->temp;
        $maxtemp = $weather_data->main->temp_max;
        $mintemp = $weather_data->main->temp_min;
        $windspeed = $weather_data->wind->speed;
        $pressure = $weather_data->main->pressure;
        $humidity = $weather_data->main->humidity;

        if ($connection){
            // inserts the fetched data into the table
            $insert_query = "INSERT INTO $tablename2 VALUES('$date', '$weathercondition', '$icon', $temperature, $maxtemp, $mintemp, $windspeed, $pressure, $humidity)";
            $insert_sql = mysqli_query($connection, $insert_query);  
        }
    }
}

// loop of 7 function calls to get 7 days weather data using timestamp
function get_7days_data_main($city_name){
    include 'connection.php';
    // deletes all previous last 7 days data by truncating
    $tablename2 = 'WeatherData';
    $del_previous = "TRUNCATE TABLE $tablename2";
    $del_sql = mysqli_query($connection, $del_previous);

    for ($days = 1; $days <= 7; $days++){
        $timestamp = strtotime("$days days ago"); // to pass the timestamp into the API url
        get_7days_data($city_name, $timestamp);
    }
}

// function call for weather data of searched cities
if(isset($_POST['submit'])){
    $city_name = $_POST['searched_city'];
    get_7days_data_main($city_name);
} else {
    // function call for weather data of default city "Atlanta"
    $city_name = "Atlanta";
    get_7days_data_main($city_name);
}
?>