<?php namespace App\Library;

use Remic\GuzzleCache\Facades\GuzzleCache;
use Illuminate\Support\Collection;

class Untappd
{
    /**
     * Untappd constructor.
     */
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
        $beers = collect([]);

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
            $beers = $this->manipulateValues(collect($results['response']['checkins']['items']));
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
        $beers = collect([]);

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
            $beers = $this->manipulateValues(collect($results['response']['checkins']['items']));

            // Filter out the checkins by this user
            $beers = $this->filterCheckinsByUser($beers, getenv('UNTAPPD_USERNAME'));

            // Limit the beers since we filtered after the API call
            $beers = $beers->slice(0, isset($options['limit']) ? $options['limit'] : 5);
        }

        return $beers;
    }

    /**
     * Manipulate values of the API data for the view
     *
     * @param Collection $beers
     * @return mixed
     */
    public function manipulateValues(Collection $beers)
    {
        // Change some of the values coming from the API
        $beers = $beers->map(function ($item) {
            $item['created_at'] = date('F jS, Y h:i:sa', strtotime($item['created_at']));
            $item['beer']['url'] = 'http://untappd.com/b/' . $item['beer']['beer_slug'] . '/' . $item['beer']['bid'];

            return $item;
        });

        return $beers;
    }

    /**
     * Filter the checkins by username
     *
     * @param Collection $beers
     * @param string $username
     * @return array
     */
    public function filterCheckinsByUser(Collection $beers, $username = '')
    {
        $beers = $beers->filter(function ($value, $key) use($username) {
            return $value['user']['user_name'] != $username ? true : false;
        });

        return $beers;
    }
}
