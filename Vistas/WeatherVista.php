<?php

if (isset($this->weather)) {
//echo '<h2>' . $this->weather->locality . '</h2>';
//echo '<h3>The Weather Today at ' . $this->weather->weather_now['weatherTime'] . '</h3>';

    echo '<div class="weather_now">';
    echo '<span style="float:right;"><img src="' . $this->weather->weather_now['weatherIcon'] .
    '" alt="' . $this->weather->weather_now['weatherDesc'] . '" class="img_weather" /></span>';

//echo '<strong>DESCRIPTION: </strong>' . $this->weather->weather_now['weatherDesc'] . '<br />';

    echo '<strong>T: </strong>' . $this->weather->weather_now['weatherTemp'] . '<br />';

//echo '<strong>WIND SPEED: </strong>' . $this->weather->weather_now['windSpeed'] . '<br />';
//echo '<strong>PRECIPITATION: </strong>' . $this->weather->weather_now['precipitation'] . '<br />';

    echo '<strong>H: </strong>' . $this->weather->weather_now['humidity'] . '<br />';

//echo '<strong>VISIBILITY: </strong>' . $this->weather->weather_now['visibility'] . '<br />';

    echo '<strong>P: </strong>' . $this->weather->weather_now['pressure'] . '<br />';

//echo '<strong>CLOUD COVER: </strong>' . $this->weather->weather_now['cloudcover'] . '<br />';

    echo '</div>';
}