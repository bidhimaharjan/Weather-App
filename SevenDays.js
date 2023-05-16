// fetches the weather data
function fetchWeatherData(cityName){
  const displayCity = document.querySelector('#city_name');
  displayCity.innerHTML = `Last 7 Days Weather Data of ${cityName}`;

  // if weather data of the city available in localStorage, fetch it 
  // else fetch from API
  const database = localStorage.getItem(`${cityName}History`);
  const stored_data = JSON.parse(database);

  // if localStorage data available for the city, gets it
  if (stored_data) {
    displayWeatherData(stored_data);
    console.log("History Data Accessed from Local Storage.");
  } else {
    // API function call
    fetchApiData(cityName);
  }
} 

// fetches the weather data from API if needed
function fetchApiData(cityName) {
  // fetch SevenDays.php to get API data
    fetch("SevenDays.php?cityname=" + cityName)
    .then((response) => response.json())
    .then((fetched_data) => {
      // set the fetched data into the localstorage for offline uses
      const database = JSON.stringify(fetched_data);
      localStorage.setItem(`${cityName}History`, database);

      displayWeatherData(fetched_data);
      console.log("History Data Accessed from Internet.");
    })
    .catch((error) => {
      console.error(error);
      alert("Error: No records found for the city!!!"); // alert message for when invalid city is entered
    });
}

// displays the past weather data in the DOM
function displayWeatherData(fetched_data){
  const weatherTable = document.querySelector('.seven-days-data');
  // iterates through each element of the data array to display the 7 days weather data
  for (let i = 0; i < fetched_data.length; i++) {
    const data = fetched_data[i];
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${data.DATE}</td>
      <td>${data.weathercondition} <img src='http://openweathermap.org/img/wn/${data.icon}@2x.png' width='50' height='50' align='center'></td>
      <td>${data.temperature}°</td>
      <td>${data.maxtemp}°</td>
      <td>${data.mintemp}°</td>
      <td>${data.windspeed} km/h</td>
      <td>${data.pressure} Pa</td>
      <td>${data.humidity}%</td>
    `;
    weatherTable.appendChild(row);
  }
}

// gets the city name and search status set by the main html
const cityName = localStorage.getItem('cityName');
const searchStatus = localStorage.getItem('searchStatus');

if (searchStatus == 'true') {
  fetchWeatherData(cityName); // if searched, fetch and display weather data for the input city
} else {
  fetchWeatherData("Atlanta"); // use default city name if input is empty
}