<?php

require '../vendor/autoload.php'; // Assuming you're using Composer

use GuzzleHttp\Client;

function getIndianCitiesGuzzle($state = null) {
    $client = new Client(['base_uri' => 'https://indian-cities-api-nocbegfhqg.now.sh/']);
    $query = $state ? ['State' => $state] : [];
    $cache_key = 'indian_cities_' . ($state ? $state : 'all');

    $cached_data = apcu_fetch($cache_key);

    if ($cached_data) {
        return $cached_data;
    }

    try {
        $response = $client->request('GET', 'cities', ['query' => $query]);
        $data = json_decode($response->getBody(), true);
        apcu_store($cache_key, $data, 3600); // Cache for 1 hour
        return $data;
    } catch (GuzzleHttp\Exception\RequestException $e) {
        error_log("Guzzle Error: " . $e->getMessage());
        return null;
    } catch (Exception $e) {
        error_log("General Error: " . $e->getMessage());
        return null;
    }
}

// Example usage:
$cities = getIndianCitiesGuzzle();

if ($cities) {
    echo "<h2>All Indian Cities (first 5)</h2>";
    echo "<ul>";
    for ($i = 0; $i < min(5, count($cities)); $i++) {
        echo "<li>" . htmlspecialchars($cities[$i]['City']) . ", " . htmlspecialchars($cities[$i]['State']) . "</li>";
    }
    echo "</ul>";

    // Get cities in a specific state
    $maharashtraCities = getIndianCitiesGuzzle("Maharashtra");
    if ($maharashtraCities) {
        echo "<h2>Maharashtra Cities (first 5)</h2>";
        echo "<ul>";
        for ($i = 0; $i < min(5, count($maharashtraCities)); $i++) {
            echo "<li>" . htmlspecialchars($maharashtraCities[$i]['City']) . ", " . htmlspecialchars($maharashtraCities[$i]['State']) . "</li>";
        }
        echo "</ul>";
    }
} else {
    echo "<p>Failed to retrieve city data.</p>";
}

function createTable($cityList) {
    if (!$cityList) {
        return "<p>No cities to display.</p>";
    }

    $html = '<table class="table table-striped"><thead><tr><th>City</th><th>State</th></tr></thead><tbody>';
    foreach ($cityList as $city) {
        $html .= '<tr><td>' . htmlspecialchars($city['City']) . '</td><td>' . htmlspecialchars($city['State']) . '</td></tr>';
    }
    $html .= '</tbody></table>';

    return $html;
}

// Example of table creation.
if ($cities) {
    $tableHtml = createTable(array_slice($cities, 0, 10)); //create table of the first 10 cities
    echo $tableHtml;
}
?>