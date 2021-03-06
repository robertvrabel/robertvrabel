<?php namespace App\Repositories;

use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\Session;
use Illuminate\Support\Collection;
use App\Contracts\Repositories\SpotifyRepositoryContract;

class SpotifyRepository implements SpotifyRepositoryContract
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
     * 
     * @codeCoverageIgnore
     */
    private function accessToken()
    {
        // Request a access token with optional scopes
        $this->session->requestCredentialsToken([
            'playlist-read-private',
            'user-read-private',
        ]);

        // Set the token on the API wrapper
        $this->api->setAccessToken($this->session->getAccessToken());
    }

    /**
     * Get spotify playlists
     *
     * @param array $options
     * @return array|object
     */
    public function playlists($options = [])
    {
        return collect($this->api->getUserPlaylists(
            $options['username'], [
                'limit' => isset($options['limit']) ? $options['limit'] : 10,
            ]
        )->items)->map(function ($item) {
            return [
                'playlist' => $item->name,
                'url' => $item->external_urls->spotify,
                'total_tracks' => $item->tracks->total,
            ];
        });
    }
}
