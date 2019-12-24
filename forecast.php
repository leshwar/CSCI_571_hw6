<?php
#-------------- Icons for Table --------------
$icon_link["clear-day"] = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-12-512.png";
$icon_link["clear-night"] = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-12-512.png";
$icon_link["rain"] = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-04-512.png";
$icon_link["snow"] = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-19-512.png";
$icon_link["sleet"] = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-07-512.png";
$icon_link["wind"] = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-27-512.png";
$icon_link["fog"] = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-28-512.png";
$icon_link["cloudy"] = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-01-512.png";
$icon_link["partly-cloudy-day"] = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-02-512.png";
$icon_link["partly-cloudy-night"] = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-02-512.png";

#-------------- Icons for Summary Card --------------
$summary_icon["clear-day"] = "https://cdn3.iconfinder.com/data/icons/weather-344/142/sun-512.png";
$summary_icon["clear-night"] = "https://cdn3.iconfinder.com/data/icons/weather-344/142/sun-512.png";
$summary_icon["rain"] = "https://cdn3.iconfinder.com/data/icons/weather-344/142/rain-512.png";
$summary_icon["snow"] = "https://cdn3.iconfinder.com/data/icons/weather-344/142/snow-512.png";
$summary_icon["sleet"] = "https://cdn3.iconfinder.com/data/icons/weather-344/142/lightning-512.png";
$summary_icon["wind"] = "https://cdn4.iconfinder.com/data/icons/the-weather-is-nice-today/64/weather_10-512.png";
$summary_icon["fog"] = "https://cdn3.iconfinder.com/data/icons/weather-344/142/cloudy-512.png";
$summary_icon["cloudy"] = "https://cdn3.iconfinder.com/data/icons/weather-344/142/cloud-512.png";
$summary_icon["partly-cloudy-day"] = "https://cdn3.iconfinder.com/data/icons/weather-344/142/sunny-512.png";
$summary_icon["partly-cloudy-night"] = "https://cdn3.iconfinder.com/data/icons/weather-344/142/sunny-512.png";
#-------------- PHP Code Starts Here --------------
function getForecastDetails($latitude, $longitude) {
    $forecast_api_key = "772090218efb7cbfa01ea469bc55ac2e";
    $forecast_api_url = "https://api.forecast.io/forecast/$forecast_api_key/$latitude,$longitude?exclude=minutely,hourly,alerts,flags";
    $forecast_response = '';
    try {
        $forecast_response = file_get_contents($forecast_api_url);
    }
    catch(Exception $e) {
        echo "ForeCast Exception caught".$e;
    }
    $json_data = json_decode($forecast_response);
    return $json_data;
}

//Function for the Second Page
function printSummary($params) {
    $forecast_api_key = "772090218efb7cbfa01ea469bc55ac2e";
    $params = explode (",", $params);  
    $daily_weather_api_url = "https://api.darksky.net/forecast/".$forecast_api_key."/".$params[0].",".$params[1].",".$params[2]."?exclude=minutely";
    $daily_weather_response = '';
    try {
        $daily_weather_response = file_get_contents($daily_weather_api_url);
    }
    catch(Exception $e) {
        echo "Daily Weather Exception caught".$e;
    }
    $json_data = json_decode($daily_weather_response);
    return $json_data;
}

if(isset($_POST["btnSubmit"])) {
    if(isset($_POST["current_location"])) {
        $lat_lon = explode (",", $_POST["check_value"]);
        $city = $lat_lon[0];
        $_POST["zero_results"] = "false";
        $json_response = getForecastDetails($lat_lon[1], $lat_lon[2]);
    }
    else {
        $street = $_POST["street"];
        $city = $_POST["city"];
        $state = $_POST["state"];
        $google_api_key = "AIzaSyA2bk8A1fJ4DfQ7ldgHfG9dFnc3GfkVqNU";
        $address = $street.','.$city.','.$state;
        $google_api_url = "https://maps.googleapis.com/maps/api/geocode/xml?address=".urlencode($address)."&key=$google_api_key";
        $geo_code_response = '';
        try {
            $geo_code_response = file_get_contents($google_api_url);
        }
        catch(Exception $e) {
            echo "GeoCode Exception caught".$e;
        }
    
        $xml_data = simplexml_load_string($geo_code_response) or die("Error: Cannot create XML object");
        if($xml_data->status == "ZERO_RESULTS") {
            $_POST["zero_results"] = "true";
        }
        else {
            $latitude = $xml_data->result->geometry->location->lat;
            $longitude = $xml_data->result->geometry->location->lng;
            $_POST["zero_results"] = "false";
            $json_response = getForecastDetails($latitude, $longitude);
        }
    }
}
#-------------- PHP Code Ends Here --------------
?>
<!DOCTYPE html>
<!-------------- HTML Code for the Form Starts here -------------->
<html>
    <head>
        <title>Weather Forecast</title>
        <meta charset="utf-8">
        <style>
            .form_div {
                height: 215px;
                width: 740px;
                background-color: #00AE17;
                border-radius: 9px;
                color: white;
                text-align: center;
                padding: 10px;
            }
            .container {
                padding-top: 10px;
                display: flex;
                justify-content: center;
                margin: auto;
                padding-top: 30px;
            }
            .weather_search {
                font-size: 36px;
                
                font-weight: initial;
                font-style: italic;
            }
            .degree {
                position: absolute;
            }
            .item {
                vertical-align: top;
                display: inline-block;
                width: 53px;
                padding-top: 10px;
                padding-left: 10px;
                text-align: center;
                font-size: 18px;
            }
            .weather_container {

            }
            .weather_card {
                margin: auto;
                height: 260px;
                width: 420px;
                background-color: #1FC5F5;
                border-radius: 9px;
                color: white;
                padding-top: 15px;
            }
            .item img {
                width: 25px;
                height: 25px;
            }
            .card_city {
                font-size: 28px;
                font-weight: bold;
            }
            .summary {
                font-size: 38px;
                font-weight: bold;
                white-space: nowrap; 
                overflow: hidden;
                text-overflow:ellipsis;
            }
            .summary:hover::before {
                content: attr(data-title);
                position: absolute;
                
                display: inline-block;
                padding: 3px 6px;
                border-radius: 2px;
                background: #000;
                color: #fff;
                font-size: 14px;
                font-family: sans-serif;
                white-space: nowrap;
            }
            .summary:hover::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 8px;
                display: inline-block;
                color: #fff;
                
                border-bottom: 8px solid #000;
            }
            .temperature {
                font-size: 75px;
                font-weight: bolder;
            }
            .temp_div {
                padding-top: 12px;
            }
            .timezone {
                font-size: 13px;
            }
            .temp_degree {
                font-size: 38px;
                position: relative;
                font-weight: bold;
                padding-left: 20px;
            }
            .summary,.temperature,.timezone,.temp_degree,.card_city {
                padding-left: 18px;
            }
            
            .humidity {

            }
            .pressure {
                
            }
            .windSpeed {
                
            }
            .visibility {
                
            }
            .cloudCover {
                
            }
            .ozone {
                
            }
            .column {
                float: left;
                width: 50%;
                height: 192px;
            }
            .row:after {
                content: "";
                display: ;
                clear: both;
            }
            .weather_table {
                background-color: #A1CBF3;
                color: white;
                text-align: center;
                font-weight: bold;
                margin: auto;
                /* width: 50%; */
                
                padding: 10px;
            }
            .table_container {
                padding-top: 20px;
            }
            .table_container table, td, th {
               border: 2px solid #5698BD;
               border-collapse: collapse;
            }
            .results_container {
                margin-left: -50px;
                margin: auto;
                width: 60%;
                padding-top: 20px;
            }
            .card_container {
                margin: auto;
                width: 50%;
                
                padding: 10px;
            }
            p {
                border: 3px solid #A2A2A2;
                background-color: #F0F0F0;
                width: 310px;
                text-align: center;
            }
            .input_error {
	            padding-left: 572px;
            }
            a {
                text-decoration: none;
                color: inherit;
            }
            .link-button {
                border: none;
                background-color: inherit;
                padding: 14px 28px;
                font-size: 15px;
                cursor: pointer;
                color: inherit;
                font-weight: bold;
            }
            .summary_card {
                width: 465px;
                height: 395px;
                background-color: #9CCED7;
                border-radius: 9px;
                color: white;
            }
            .summary_icon {
                padding-left: 0px;
                padding-top: 10px;
                padding-right: 10px;
            }
            .column1 {
                float: left;
                width: 50%;
                height: 170px;
                margin-top: -10px;
            }
            .row1:after {
                content: "";
                display: table;
                clear: both;
            }
            .temperature2 {
                font-size: 100px;
                font-weight: bolder;
            }
            .temp_summary {
                padding-top: 73px;
                padding-left: 25px;
                text-align: initial;
            }
            .summary_2 {
                font-size: 31px;
                font-weight: bold;
            }
            .temp_degree_2 {
                font-size: 65px;
                position: relative;
                font-weight: bold;
                padding-left: 11px;
            }
            .card_details {
                padding-left: 30px;
                padding-top: 65px;
                font-weight: bold;
                font-size: 16px;
                line-height: 24px;
            }
            .card_details_text {
                font-size: 24px;
            }
            .card_details_text_ap {
                font-size: 16px;
            }
            .card_details_table {
                padding-left: 150px;
                line-height: 20px;
            }
            .card_details_table_left {
                text-align: right;
            }
            .card_details_table_right {
                text-align: left;
            }
            table.card_details_table tr {
                border: 0px solid #00AE17;
                border-collapse: collapse;
            }
            table.card_details_table td {
                border: 0px solid #00AE17;
                border-collapse: collapse;
            }
            .summary_container {
                position: absolute;
                left: 39%;
                margin-left: -50px;
                text-align: center;
            }
            table.initial_table {
                padding-left: 45px;
                padding-top: 5px;
                text-align: left;
            }
            table.initial_table tr {
                border: 2px solid #00AE17;
                border-collapse: collapse;
            }
            table.initial_table td {
                border: 2px solid #00AE17;
                border-collapse: collapse;
            }
            .main_form {
                width: 120px;
            }
            .vertical_line {
                width: 5px;
                background-color: white;
                border-radius: 10px;
                height: 135px;
                margin-left: 45px;
            }
            .current_location {
                padding-left: 140px;
                position: absolute;
                padding-top: 10px;
                font-weight: bold;
            }
            .buttons {
                padding-left: 240px;
                padding-top: 30px;
            }
            .chart_div {
                display: none;
            }
            .chart_container {
                position: absolute;
                left: 39%;
                margin-left: -190px;
                text-align: center;
                margin-top: 560px;
                padding-top: 10px;
            }
            .error {
                border:1.5px solid red;
            }
            /* [data-title] {
                font-size: 38px; 
                position: relative;
            }

            [data-title]:hover::before {
                content: attr(data-title);
                position: absolute;
                bottom: -26px;
                display: inline-block;
                padding: 3px 6px;
                border-radius: 2px;
                background: #000;
                color: #fff;
                font-size: 12px;
                font-family: sans-serif;
                white-space: nowrap;
            }
            [data-title]:hover::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 8px;
                display: inline-block;
                color: #fff;
                border: 8px solid transparent;	
                border-bottom: 8px solid #000;
            } */
        </style>
    <head>
    <body>
    <!-------------- Javascript Code Starts here -------------->
    <script type="text/javascript" src = "https://www.gstatic.com/charts/loader.js"></script>
    <script type = "text/javascript">
        google.charts.load('current', {packages: ['corechart', 'line']});
        
        function displayChart(temperature_array) {
            var array = temperature_array;
            //console.log(array);
            google.charts.setOnLoadCallback(drawLogScales);
            function drawLogScales() {
                var data = new google.visualization.DataTable();
                data.addColumn('number', 'Time');
                data.addColumn('number', 'T');
                for(i = 0; i < array.length; i++) {
                    var temp = [];
                    temp.push(i);
                    temp.push(array[i]);
                    data.addRow(temp);
                }
                //console.log(data);
                var options = {
                    hAxis: {
                        title: 'Time',
                    },
                    vAxis: {
                        title: 'Temperature',
                    },
                    colors: ['#9CCED7'],
                    width: 730,
                    height: 200
                };
                var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            }
        }
        
        function search() {
            //Only Validation here
            if (!document.getElementById('current_location').checked) {
                var street_value = document.getElementById("street").value;
                var city_value = document.getElementById("city").value;
                var state_value = document.getElementById("state").value;
                if(street_value == '' || city_value == '' || state_value == '' ) {
                    document.getElementById('input_error').style.display = "block";
                    //Clear the Results Container
                    var element = document.querySelector('.results_container');
                    if(element) {
                        element.parentNode.removeChild(element);
                    }
                    //Clear the Summary Container
                    var element = document.querySelector('.summary_container');
                    if(element) {
                        element.parentNode.removeChild(element);
                    }

                    //Clear error class
                    if(street_value == '') {
                        var element = document.getElementById('street');
                        element.classList.add('error');
                    }
                    else {
                        var element = document.getElementById('street');
                        element.classList.remove('error');
                    }
                    if(city_value == '') {
                        var element = document.getElementById('city');
                        element.classList.add('error');
                    }
                    else {
                        var element = document.getElementById('city');
                        element.classList.remove('error');
                    }
                    if(state_value == '') {
                        var element = document.getElementById('state');
                        element.classList.add('error');
                    }
                    else {
                        var element = document.getElementById('state');
                        element.classList.remove('error');
                    }
                    return false;
                }
                var element = document.getElementById('street');
                element.classList.remove('error');
                var element = document.getElementById('city');
                element.classList.remove('error');
                var element = document.getElementById('state');
                element.classList.remove('error');
                return true;
            }
            else {
                var ip_api_url = "http://ip-api.com/json";
                var json_response = xmlHttpCaller(ip_api_url);
                //console.log(json_response)
                var city = json_response.city; 
                var lat = json_response.lat;
                var lon = json_response.lon;
                var check_value = city+','+lat+','+lon;
                document.getElementById("check_value").value = String(check_value);
                return true;
            }
        }
        function clean() {
            //Clear all the fields here.
            document.getElementById("street").value = '';
            document.getElementById("city").value = '';
            document.getElementById("state").value = '';
            document.getElementById("current_location").checked = false;
            document.getElementById("street").disabled = false;
            document.getElementById("city").disabled = false;
            document.getElementById("state").disabled = false;

            var element = document.getElementById('street');
            element.classList.remove('error');
            var element = document.getElementById('city');
            element.classList.remove('error');
            var element = document.getElementById('state');
            element.classList.remove('error');
            
            //Clear the Weather content
            var element = document.querySelector('.results_container');
            if(element) {
                element.parentNode.removeChild(element);
            }
            
            //Disable the Error content
            document.getElementById('input_error').style.display = "none";

            //Clear the Summary content
            var element = document.querySelector('.summary_container');
            if(element) {
                element.parentNode.removeChild(element);
            }
            //Clear the Chart content
            var element = document.querySelector('.chart_container');
            if(element) {
                element.parentNode.removeChild(element);
            }
        }
        function xmlHttpCaller (url) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET", url, false);
            xmlhttp.send();
            if(xmlhttp.status === 200) {
                var responseText = xmlhttp.responseText;
                return JSON.parse(responseText);
            }
            
        }
        function checkBox() {
            if (document.getElementById('current_location').checked) {
                document.getElementById("street").value = '';
                document.getElementById("city").value = '';
                document.getElementById("state").value = '';

                document.getElementById("street").disabled = true;
                document.getElementById("city").disabled = true;
                document.getElementById("state").disabled = true;

                var element = document.getElementById('street');
                element.classList.remove('error');
                var element = document.getElementById('city');
                element.classList.remove('error');
                var element = document.getElementById('state');
                element.classList.remove('error');
            } 
            else {
                document.getElementById("street").disabled = false;
                document.getElementById("city").disabled = false;
                document.getElementById("state").disabled = false;
            }
        }
        function toggleChart() {
            var toggle_src = document.getElementById('toggle_chart').src;
            if(toggle_src == "https://cdn4.iconfinder.com/data/icons/geosm-e-commerce/18/point-down-512.png") {
                document.getElementById('chart_div').style.display = 'block';
                document.getElementById('toggle_chart').src = "https://cdn0.iconfinder.com/data/icons/navigation-set-arrows-part-one/32/ExpandLess-512.png";
            }
            else {
                document.getElementById('chart_div').style.display = 'none';
                document.getElementById('toggle_chart').src = "https://cdn4.iconfinder.com/data/icons/geosm-e-commerce/18/point-down-512.png";
            }
        }
    </script>
    <!-------------- Javascript Code Ends here -------------->
    <div class = "container">
        <div class = "form_div">
            <form id="forecast_form" METHOD = "POST" ACTION = "">
                <span class = "weather_search">Weather Search</span><br>
                <div class="row">
                    <div class="column" style="">
                        <table class = "initial_table">
                            <tr><td><label><b>Street</b></label></td>
                            <td><input class = "main_form" type = "text" name = "street" id = "street" value = "<?php echo isset($_POST['street']) ? $_POST['street'] : '' ?>" <?php if(isset($_POST['current_location']) && $_POST['current_location'] == "on") echo "disabled"; ?>></input></td></tr>
                            
                            <tr><td><label><b>City</b></label></td>
                            <td><input class = "main_form" type = "text" name = "city" id = "city" value = "<?php echo isset($_POST['city']) ? $_POST['city'] : '' ?>" <?php if(isset($_POST['current_location']) && $_POST['current_location'] == "on") echo "disabled"; ?>></input></td></tr>
                            
                            <tr><td><label><b>State</b></label></td>
                            <td><select name = "state" id = "state" style = "width: 200px;"<?php if(isset($_POST['current_location']) && $_POST['current_location'] == "on") echo "disabled"; ?>>
                                <option value="" selected="selected">State</option>
                                <optgroup label="--------------------------"></optgroup>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "AL") echo 'selected="selected"';?> value="AL">Alabama</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "AK") echo 'selected="selected"';?> value="AK">Alaska</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "AZ") echo 'selected="selected"';?>value="AZ">Arizona</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "AR") echo 'selected="selected"';?> value="AR">Arkansas</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "CA") echo 'selected="selected"';?> value="CA">California</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "CO") echo 'selected="selected"';?> value="CO">Colorado</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "CT") echo 'selected="selected"';?> value="CT">Connecticut</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "DE") echo 'selected="selected"';?> value="DE">Delaware</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "DC") echo 'selected="selected"';?> value="DC">District Of Columbia</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "FL") echo 'selected="selected"';?> value="FL">Florida</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "GA") echo 'selected="selected"';?> value="GA">Georgia</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "HI") echo 'selected="selected"';?> value="HI">Hawaii</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "ID") echo 'selected="selected"';?> value="ID">Idaho</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "IL") echo 'selected="selected"';?> value="IL">Illinois</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "IN") echo 'selected="selected"';?> value="IN">Indiana</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "IA") echo 'selected="selected"';?> value="IA">Iowa</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "KS") echo 'selected="selected"';?> value="KS">Kansas</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "KY") echo 'selected="selected"';?> value="KY">Kentucky</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "LA") echo 'selected="selected"';?> value="LA">Louisiana</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "ME") echo 'selected="selected"';?> value="ME">Maine</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "MD") echo 'selected="selected"';?> value="MD">Maryland</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "MA") echo 'selected="selected"';?> value="MA">Massachusetts</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "MI") echo 'selected="selected"';?> value="MI">Michigan</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "MN") echo 'selected="selected"';?> value="MN">Minnesota</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "MS") echo 'selected="selected"';?> value="MS">Mississippi</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "MO") echo 'selected="selected"';?> value="MO">Missouri</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "MT") echo 'selected="selected"';?> value="MT">Montana</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "NE") echo 'selected="selected"';?> value="NE">Nebraska</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "NV") echo 'selected="selected"';?> value="NV">Nevada</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "NH") echo 'selected="selected"';?> value="NH">New Hampshire</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "NJ") echo 'selected="selected"';?> value="NJ">New Jersey</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "NM") echo 'selected="selected"';?> value="NM">New Mexico</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "NY") echo 'selected="selected"';?> value="NY">New York</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "NC") echo 'selected="selected"';?> value="NC">North Carolina</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "ND") echo 'selected="selected"';?> value="ND">North Dakota</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "OH") echo 'selected="selected"';?> value="OH">Ohio</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "OK") echo 'selected="selected"';?> value="OK">Oklahoma</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "OR") echo 'selected="selected"';?> value="OR">Oregon</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "PA") echo 'selected="selected"';?> value="PA">Pennsylvania</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "RI") echo 'selected="selected"';?> value="RI">Rhode Island</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "SC") echo 'selected="selected"';?> value="SC">South Carolina</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "SD") echo 'selected="selected"';?> value="SD">South Dakota</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "TN") echo 'selected="selected"';?> value="TN">Tennessee</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "TX") echo 'selected="selected"';?> value="TX">Texas</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "UT") echo 'selected="selected"';?> value="UT">Utah</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "VT") echo 'selected="selected"';?> value="VT">Vermont</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "VA") echo 'selected="selected"';?> value="VA">Virginia</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "WA") echo 'selected="selected"';?> value="WA">Washington</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "WV") echo 'selected="selected"';?> value="WV">West Virginia</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "WI") echo 'selected="selected"';?> value="WI">Wisconsin</option>
                                <option <?php if(isset($_POST['state']) && $_POST['state'] == "WY") echo 'selected="selected"';?> value="WY">Wyoming</option>
                            </select> </td></tr>
                        </table>
                        <br>
                        <div class = "buttons">
                            <input type = "submit" value = "Search" name = "btnSubmit" onclick = "return search();">
                            <input type = "button" value = "Clear" onclick = "clean();">
                        </div>
                    </div>
                    <div class="column" style="">
                        <div class = "vertical_line">
                            <div class = "current_location">
                                <input type = "hidden" id = "check_value" name = "check_value" value = "">
                                <input type="checkbox" id="current_location" name="current_location" onclick = "checkBox();" <?php if(isset($_POST['current_location']) && $_POST['current_location'] == "on") echo "checked='checked'"; ?>> 
                                <label>Current Location</label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php if(isset($_POST["btnSubmit"]) && ($_POST["zero_results"] == 'true')): ?>
    <div style="" id="input_error" class = "input_error">
        <p>Please check the input address.</p>
    </div>
    <?php endif;?>
    <div style="display:none;" id="input_error" class = "input_error">
        <p>Please check the input address.</p>
    </div>
    <?php if(isset($_POST["btnSubmit"]) && ($_POST["zero_results"] == 'false')): ?>
    <div class = "results_container">
        <div class = "card_container">
            <div class = "weather_container">
                <div class = "weather_card">
                    <span class = "card_city"><?php echo ucwords(strtolower($city)); ?></span><br>
                    <span class = "timezone"><?php echo $json_response->timezone; ?></span><br>
                    <div class = "temp_div">
                        <span class="temperature">
                        <?php echo round($json_response->currently->temperature); ?></span>
                        <img class = "degree" src = "https://cdn3.iconfinder.com/data/icons/virtual-notebook/16/button_shape_oval-512.png" width = "12" height = "12">
                        <span class = "temp_degree">F</span><br>
                    </div>
                    
                    <div class = "summary" data-title = "<?php echo $json_response->currently->summary; ?>"><?php echo $json_response->currently->summary; ?></div>
                    
                        <div class="item" title="Humudity">
                            <img class = "" src = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-16-512.png"><br>
                            <b><?php echo $json_response->currently->humidity; ?></b>
                        </div>
                    
                    
                        <div class="item" title="Pressure">
                            <img class = "" src = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-25-512.png"><br>
                            <b><?php echo $json_response->currently->pressure; ?></b>
                        </div>
                    
                        <div class="item" title="WindSpeed">
                            <img class = "" src = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-27-512.png"><br>
                            <b><?php echo $json_response->currently->windSpeed; ?></b>
                        </div>
                    
                        <div class="item" title="Visibility">
                            <img class = "" src = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-30-512.png"><br>
                            <b><?php echo $json_response->currently->visibility; ?></b>
                        </div>
                    
                        <div class="item" title="CloudCover">
                            <img class = "" src = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-28-512.png"><br>
                            <b><?php echo $json_response->currently->cloudCover; ?></b>
                        </div>
                    
                        <div class="item" title="Ozone">
                            <img class = "" src = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-24-512.png"><br>
                            <b><?php echo $json_response->currently->ozone; ?></b>
                        </div>
                    
                </div>
            </div>
        </div> 
        <div class = "table_container">
            <form id="summary_form" METHOD = "POST" ACTION = "">
                <table class = "weather_table">
                    <tr><th>Date</th><th>Status</th><th>Summary</th><th>TemperatureHigh</th><th>TemperatureLow</th><th>Wind Speed</th></tr>
                    <?php $latitude = $json_response->latitude; $longitude = $json_response->longitude; ?>
                    <?php for($i = 0; $i < count($json_response->daily->data); $i++): ?>
                        <?php $send_data = ''; $send_data = $latitude.','.$longitude.','.$json_response->daily->data[$i]->time ?>
                        <input type = "hidden" name = "street" value = "<?php echo isset($_POST['street']) ? $_POST['street'] : ''?>">
                        <input type = "hidden" name = "city" value = "<?php echo isset($_POST['city']) ? $_POST['city'] : ''?>">
                        <input type = "hidden" name = "state" value = "<?php echo isset($_POST['state']) ? $_POST['state'] : ''?>">
                        <input type = "hidden" name = "current_location" value = "<?php echo isset($_POST['current_location']) ? $_POST['current_location'] : ''?>">
                        <input type = "hidden" name = "postSubmit" value = "postSubmit">
                        <tr><td><?php echo gmdate("Y-m-d", $json_response->daily->data[$i]->time); ?></td><td><img src ="<?php echo $icon_link[$json_response->daily->data[$i]->icon]; ?>" width = 30px height = 30px></td><td><input type="hidden" name="extra_submit_param" value="extra_submit_value">
                            <button type="submit" name="submit_param" value="<?php echo $send_data; ?>" class="link-button"><?php echo $json_response->daily->data[$i]->summary; ?></button></td><td><?php echo $json_response->daily->data[$i]->temperatureHigh; ?></td><td><?php echo $json_response->daily->data[$i]->temperatureLow; ?></td><td><?php echo $json_response->daily->data[$i]->windSpeed; ?></td></tr>
                    <?php endfor; ?>
                </table>
            </div>
            </form>
        </div> 
    <?php endif; ?>
    <?php if(isset($_POST["postSubmit"])): ?>
    <?php $params = $_POST["submit_param"]; $json_response = printSummary($params); ?>
    
    <div class = "summary_container">
        <h1>Daily Weather Detail</h1>
        <div class = "summary_card">
            <div class="row1">
                <div class="column1">
                    <div class = "temp_summary">
                        <span class = "summary_2"><?php echo $json_response->currently->summary; ?></span><br>
                        <span class="temperature2"><?php echo round($json_response->currently->temperature); ?></span>
                        <img class = "degree" src = "https://cdn3.iconfinder.com/data/icons/virtual-notebook/16/button_shape_oval-512.png" width = "12" height = "12">
                        <span class = "temp_degree_2">F</span><br>
                    </div>
                </div>
                <div class="column1">
                    <div class = "summary_icon">
                        <img src = "<?php echo $summary_icon[$json_response->currently->icon]; ?>" width = "195px" height = "195px">
                    </div>
                </div>
            </div>
            
        
            <div class = "card_details">
                <table class = "card_details_table">
                    <tr>
                        <td class = "card_details_table_left"> Precipitation: </span></td>
                        <td class = "card_details_table_right"><span class = "card_details_text"> <?php 
                                        $prep_value = $json_response->currently->precipIntensity;
                                        $prep_result = '';
                                        if ($prep_value > 0.1) {
                                            $prep_result = 'Heavy';
                                        }
                                        if ($prep_value <= 0.1) {
                                            $prep_result = 'Moderate';
                                        }
                                        if ($prep_value <= 0.05) {
                                            $prep_result = 'Light';
                                        }
                                        if ($prep_value <= 0.015) {
                                            $prep_result = 'Very Light';
                                        }
                                        if ($prep_value <= 0.001) {
                                            $prep_result = 'N/A';
                                        }
                                        echo $prep_result; 
                                    ?></td>
                        
                    </tr>
                    <tr>
                        <td class = "card_details_table_left">Chance of Rain:</td>
                        <td class = "card_details_table_right"><span class = "card_details_text"> <?php echo round(($json_response->currently->precipProbability)*100); ?></span> %</td>
                    </tr>
                    <tr>
                        <td class = "card_details_table_left">Wind Speed:</td>
                        <td class = "card_details_table_right"><span class = "card_details_text"> <?php echo round($json_response->currently->windSpeed); ?></span> mph</td>
                    </tr>
                    <tr>
                        <td class = "card_details_table_left">Humidity:</td>
                        <td class = "card_details_table_right"><span class = "card_details_text"> <?php echo round(($json_response->currently->humidity)*100); ?></span> %</td>
                    </tr>
                    <tr>
                        <td class = "card_details_table_left">Visibility:</td>
                        <td class = "card_details_table_right"><span class = "card_details_text"> <?php echo round($json_response->currently->visibility); ?></span> mi</td>
                    </tr>
                    <?php 
                        $sunriseTime = (string)$json_response->daily->data[0]->sunriseTime;
                        $timezone = (string)$json_response->timezone;
                        date_default_timezone_set($timezone);
                        $str = date('d-m-Y H:i:s',$sunriseTime);
                        $newSunriseTime = date('g A', strtotime($str));
                        $newSunriseTime = explode(" ",$newSunriseTime);

                        $sunsetTime = $json_response->daily->data[0]->sunsetTime;
                        $str = date('d-m-Y H:i:s',$sunsetTime);
                        $newSunsetTime = date('g A', strtotime($str));
                        $newSunsetTime = explode(" ",$newSunsetTime);
                    ?>
                    <tr>
                        <td class = "card_details_table_left">Sunrise / Sunset:</td>
                        <td class = "card_details_table_right"><span class = "card_details_text"> <?php echo $newSunriseTime[0]; ?></span><span class = "card_details_text_ap"> <?php echo $newSunriseTime[1]; ?></span>/<span class = "card_details_text"><?php echo $newSunsetTime[0]; ?></span><span class = "card_details_text_ap"> <?php echo $newSunsetTime[1]; ?></span></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php 
            $temperature_array = array();
            for($i = 0; $i < sizeof($json_response->hourly->data); $i++) {
                array_push($temperature_array, $json_response->hourly->data[$i]->temperature);
            }
        ?>
        <br>
        <span style = "font-size: 32px;font-weight: bold;">Day's Hourly Weather</span>
        <br>
        <input type = "image" src = "https://cdn4.iconfinder.com/data/icons/geosm-e-commerce/18/point-down-512.png" name="toggle_chart" id="toggle_chart" style="width: 50px;height: 45px;" onclick = "toggleChart()"/>
        <script type="text/javascript">
            var temperature_array = <?php echo json_encode($temperature_array); ?>;
            displayChart(temperature_array);
        </script>
            
        </div>
        <div class = "chart_container">
            <div id = "chart_div" class = "chart_div"></div>
        </div>
    <?php endif; ?>

    </body>
</html>