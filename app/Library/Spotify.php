<?php namespace App\Library;

use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\Session;

/**
 * @property Session session
 * @property SpotifyWebAPI api
 */
class Spotify
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Api
     */
    private $api;

    /**
     * Spotify constructor.
     *
     * @param Session $session
     * @param SpotifyWebAPI $spotifyWebAPI
     */
    public function __construct(Session $session, SpotifyWebAPI $spotifyWebAPI)
    {
        // This will already have the consumer/secret per the AppServiceProvider
        $this->session = $session;
        $this->api = $spotifyWebAPI;

        // Always set the access token
        $this->accessToken();
    }

    /**
     * Generate and set an access token for the API
     */
    private function accessToken()
    {
        // Request a access token with optional scopes
        $scopes = [
            'playlist-read-private',
            'user-read-private',
        ];

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
        return $this->trimPlaylists($playlists);
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