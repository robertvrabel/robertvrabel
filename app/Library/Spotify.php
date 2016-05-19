<?php namespace App\Library;

use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\Session;

class Spotify
{
    /**
     * Spotify constructor.
     */
    public function __construct()
    {
        $this->session = new Session(
            getenv('SPOTIFY_CLIENT_ID'),
            getenv('SPOTIFY_CLIENT_SECRET'),
            'http://robertvrabel.app/'
        );
        $this->api = new SpotifyWebAPI();

        $this->accessToken();
    }

    /**
     * Generate and set an access token for the API
     */
    private function accessToken()
    {
        // Request a access token with optional scopes
        $scopes = array(
            'playlist-read-private',
            'user-read-private'
        );

        $this->session->requestCredentialsToken($scopes);
        $accessToken = $this->session->getAccessToken();

        // Set the token on the API wrapper
        $this->api->setAccessToken($accessToken);
    }

    /**
     * Get spotify playlists
     *
     * @return array|object
     */
    public function playlists($options = [])
    {
        // Get playlists
        $playlists = $this->api->getUserPlaylists($options['username'], array(
            'limit' => isset($options['limit']) ? $options['limit'] : 10
        ));

        // Trim the playlists down to the data we need for the view
        $playlists = $this->trimPlaylists($playlists);

        return $playlists;
    }

    /**
     * Trim a playlist down to only what the view needs
     *
     * @param $playlists
     * @return array
     */
    public function trimPlaylists($playlists)
    {
        // Trimmed info to return
        $trimmed = [];

        foreach ($playlists->items as $list) {
            $trimmed[] = [
                'playlist' => $list->name,
                'url' => $list->external_urls->spotify,
                'total_tracks' => $list->tracks->total,
            ];
        }

        return $trimmed;
    }
}