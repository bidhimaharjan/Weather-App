// displays the current weather data
function displayWeatherData(cityName){
  // Selectors and Variables
  const displayCity = document.querySelector('#city');
  const displayDate = document.querySelector('#day-date');
  const displayWeather = document.querySelector('#weather-condition');
  const displayIcon = document.querySelector('#weather-icon');
  const displayMaxTemp = document.querySelector('#max-temp');
  const displayMinTemp = document.querySelector('#min-temp');
  const displayTemp = document.querySelector('#temperature');
  const displayWindspeed = document.querySelector('#windspeed');
  const displayPressure = document.querySelector('#pressure');
  const displayHumidity = document.querySelector('#humidity');  

  // fetch get_current_data.php to get API data and assign it to respective DOM elements
  fetch("get_current_data.php?city_name=" + cityName)
  .then((response) => response.json())
  .then((data) => {
    console.log(data); // to view if data is received  or not

    // City and country name
    const city = data.city;
    const countryName = data.country;
    displayCity.innerHTML = `${city}, ${codeToName(countryName)}`;
    // Day and Date
    displayDate.innerHTML = `${getDate()}`;
    // Weather Icon
    let fetchIcon = `${data.icon}`;
    displayIcon.innerHTML = `<img src = "http://openweathermap.org/img/wn/${fetchIcon}@2x.png">`;
    // Weather Components
    displayWeather.innerHTML = `${data.weathercondition}`; 
    displayTemp.innerHTML = `${data.temperature}` + "°" ;
    displayMaxTemp.innerHTML = "H: " + `${data.maxtemp}` + "°" ;
    displayMinTemp.innerHTML = "L: " + `${data.mintemp}` + "°" ;
    displayWindspeed.innerHTML = "<i class='fas fa-wind'></i> Wind <br>" + `${data.windspeed}` + " km/h" ;
    displayPressure.innerHTML = "<i class='fas fa-gauge'></i> Pressure <br>" + `${data.pressure}` + " Pa" ;
    displayHumidity.innerHTML = "<i class='fas fa-droplet'></i> Humidity <br>" + `${data.humidity}` + "%" ;
  })
  .catch((error) => {
    console.error(error);
    alert("Sorry, City Not Found!!!"); // alert message for when invalid city is entered
  });
}

// converts country code to name
function codeToName(countryCode){
  const countryName = new Intl.DisplayNames(['en'], {type: 'region'}).of(countryCode);
  return countryName;
}

 // gets and formats day and date
function getDate(){
  const today = new Date();
  const daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
  const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  const dayOfWeek = daysOfWeek[today.getDay()];
  const month = months[today.getMonth()];
  const dayOfMonth = today.getDate();
  const year = today.getFullYear();

  const formattedDate = `${dayOfWeek} | ${month} ${dayOfMonth}, ${year}`;
  return formattedDate;
}

// Form Selectors for user input cities
const searchButton = document.querySelector('.search-bar');
const cityInput = document.querySelector('.input-value');

// function to get weather data and display it
function getWeatherData(event) {
  event.preventDefault(); // prevent default form submit behavior
  let cityName = cityInput.value;
  if (!cityName) {
    cityName = "Atlanta"; // use default city name if input is empty
  }
  displayWeatherData(cityName); // fetch and display weather data for the input city
}

// add event listener to the form for submit event
searchButton.addEventListener('submit', getWeatherData);

// display weather data for default city on page load
displayWeatherData("Atlanta");