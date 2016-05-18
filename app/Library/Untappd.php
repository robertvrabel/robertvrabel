<?php namespace App\Library;

use GuzzleHttp\Client;
use Remic\GuzzleCache\Facades\GuzzleCache;

class Untappd
{
    public function __construct()
    {

    }

    /**
     * Get the users activity feed
     *
     * @param array $options
     * @return mixed
     */
    public function activityFeed($options = [])
    {
        // Data to return
        $beers = [] ;

        // Build the query
        $endpoint = 'https://api.untappd.com/v4';
        $method = '/user/checkins/' . $options['untappd_username'];
        $params = [
            'client_id' => getenv('UNTAPPD_CLIENT_ID'),
            'client_secret' => getenv('UNTAPPD_CLIENT_SECRET'),
            'limit' => isset($options['limit']) && $options['limit'] != '' ? $options['limit'] : 10,
        ];

        // Query for the results
        $client = GuzzleCache::client();

        $response = $client->get($endpoint . $method, [
            'timeout' => 10,
            'exceptions' => false,
            'connect_timeout' => 10,
            'query' => $params,
        ]);

        // If guzzle is successful
        if($response->getStatusCode() == 200) {
            // Get the results
            $results = $response->json();

            // Manipulate values for the view
            $beers = $this->manipulateValues($results['response']['checkins']['items']);
        }

        return $beers;
    }

    /**
     * Get the breweries activity feed
     *
     * @param array $options
     * @return mixed
     */
    public function breweryActivityFeed($options = [])
    {
        // Data to return
        $beers = [] ;

        // Verify we have the correct options
        if (!isset($options['untappd_brewery_id'])) {
            // Throw exception
        }

        // Build the query
        $endpoint = 'https://api.untappd.com/v4';
        $method = '/brewery/checkins/' . $options['untappd_brewery_id'];
        $params = [
            'client_id' => getenv('UNTAPPD_CLIENT_ID'),
            'client_secret' => getenv('UNTAPPD_CLIENT_SECRET'),
            'limit' => 35,
        ];

        // Query for the results
        $client = GuzzleCache::client();

        $response = $client->get($endpoint . $method, [
            'timeout' => 10,
            'exceptions' => false,
            'connect_timeout' => 10,
            'query' => $params,
        ]);

        // If guzzle is successful
        if($response->getStatusCode() == 200) {
            // Get the results
            $results = $response->json();

            // Manipulate values for the view
            $beers = $this->manipulateValues($results['response']['checkins']['items']);

            // Filter out the checkins by this user
            $beers = $this->filterCheckinsByUser($beers, getenv('UNTAPPD_USERNAME'));

            // Limit the beers since we filtered after the API call
            $beers = array_slice($beers, 0, isset($options['limit']) ? $options['limit'] : 5);
        }

        return $beers;
    }

    /**
     * Manipulate values of the API data for the view
     *
     * @param $beers
     * @return mixed
     */
    public function manipulateValues($beers)
    {
        foreach ($beers as $key => $beer) {
            $beers[$key]['created_at'] = date('F jS, Y h:i:sa', strtotime($beer['created_at']));
            $beers[$key]['beer']['url'] = 'http://untappd.com/b/' . $beer['beer']['beer_slug'] . '/' . $beer['beer']['bid'];
        }

        return $beers;
    }

    /**
     * Filter the checkins by a certain user
     *
     * @param $beers
     * @param string $username
     * @return array
     */
    public function filterCheckinsByUser($beers, $username = '')
    {
        // Filtered checkins
        $filtered = [];

        foreach ($beers as $key => $beer) {
            if($beer['user']['user_name'] != $username) {
                $filtered[] = $beer;
            }
        }

        return $filtered;
    }
}
?>