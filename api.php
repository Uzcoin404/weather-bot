<?php
class Api {
    private $API_KEY;

    public function __construct($API_KEY){
        $this->API_KEY = $API_KEY;
    }

    public function getWeather($latitude, $longitude, $previous = null){
        $apiKey = $this->API_KEY;
        if ($previous == null) {
            if ($latitude != null && $longitude != null) {
                $url = "https://api.openweathermap.org/data/3.0/onecall?lat=$latitude&lon=$longitude&exclude=hourly,minutely&units=metric&appid=$apiKey";
                var_dump("URL:", $url);
            } else {
                return false;
            }
        } else {
            $url = "https://api.openweathermap.org/data/3.0/onecall/timemachine?lat=$latitude&lon=$longitude&dt=$previous&units=metric&appid=$apiKey";
        }

        $client = curl_init($url);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($client);
        curl_close($client);

        if ($error = curl_error($client)) {
            // It's better to log the error or return it, not just var_dump
            error_log("cURL Error in getWeather: " . $error);
            return false; // Indicate failure
        } else {
            var_dump("Respone:", $response, $this->API_KEY);
            $result = json_decode($response, true);
            if (isset($result['lat'])) {
                return $result;
            } else {
                // Handle API specific errors (e.g., 'cod' for error codes)
                error_log("OpenWeatherMap API Error: " . json_encode($result));
                return $result['cod'] ?? false; // Return code or false if not set
            }
        }
    }

    public function getCityLocation($city, $lat = null, $lon = null){
        $city = trim($city);
        if ($lat != null && $lon != null) {
            $url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&units=metric&appid=$this->API_KEY";
        } else if ($city != '') {
            $url = "https://api.openweathermap.org/data/2.5/weather?q=$city&units=metric&appid=$this->API_KEY";   
        } else {
            return false;
        }

        $client = curl_init($url);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($client);
        curl_close($client);

        if (!curl_error($client)) {
            $result = json_decode($response, true);
            if ($result['cod'] == 200) {
                return $result;
            } else {
                return $result['cod'];
            }
        }
    }
}
?>