// fetches the weather data
function fetchCurrentData(cityName) {
  // if weather data of the city available in localStorage for today's date, fetch it 
  // else fetch from API

  const database = localStorage.getItem(cityName);
  const stored_data = JSON.parse(database);

  // if localStorage data available for the city, gets it
  if (stored_data) {
    // gets the date from the last stored data
    const last_updated_date = stored_data.DATE;
    // gets today's date in ISO form using Date()
    const todays_date = new Date().toISOString().slice(0, 10);
    if (last_updated_date != todays_date) {
      // API function call
      fetchApiData(cityName);
    } else {
      displayCurrentData(stored_data);
      console.log("Current Data Accessed from Local Storage.");
    } 
  } else {
    // API function call
    fetchApiData(cityName);
  } 
}

// fetches the weather data from API if needed
function fetchApiData(cityName) {
  // fetch get_current_data.php to get API data
    fetch("get_current_data.php?city_name=" + cityName)
    .then((response) => response.json())
    .then((fetched_data) => {
      // set the fetched data into the localstorage for offline uses
      const database = JSON.stringify(fetched_data);
      localStorage.setItem(cityName, database);

      displayCurrentData(fetched_data);
      console.log("Current Data Accessed from Internet.");
    })
    .catch((error) => {
      console.error(error);
      alert("Error: Weather Data Not Found!!!"); // alert message for when invalid city is entered
    });
}

// displays the current weather data
function displayCurrentData(data){
  // Selectors and Variables to assign the stored or fetched to respective DOM elements
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
const searchButton = document.querySelector('#search-bar');
const cityInput = document.querySelector('#input-value');

// function to get weather data and display it
function getCurrentData(event) {
  event.preventDefault(); // prevent default form submit behavior
  
  let cityName = cityInput.value;
  if (!cityName) {
    cityName = "Atlanta"; // use default city name if input is empty
  }
  localStorage.setItem('cityName', cityName); // set the input city name in localstorage for SevenDays.html
  localStorage.setItem('searchStatus', true); // if searched, set the search status as true for SevenDays.html
  fetchCurrentData(cityName); // fetch and display weather data for the input city
}

// event listener to the form for submit event
searchButton.addEventListener('submit', getCurrentData);

// displays weather data for default city on page load
fetchCurrentData("Atlanta");
// set default search status as false
localStorage.setItem('searchStatus', false);