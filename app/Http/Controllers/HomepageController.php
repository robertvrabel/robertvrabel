<?php
namespace App\Http\Controllers;

use App\Contracts\Repositories\UntappdRepositoryContract;
use App\Contracts\Repositories\TumblrRepositoryContract;
use App\Contracts\Repositories\SpotifyRepositoryContract;

/**
 * @property Untappd untappd
 * @property Tumblr tumblr
 * @property Spotify spotify
 */
class HomepageController extends Controller
{
    private $untappd;
    private $tumblr;
    private $spotify;

    /**
     * HomepageController constructor.
     *
     * @param Untappd $untappd
     * @param Tumblr $tumblr
     * @param Spotify $spotify
     */
    public function __construct(UntappdRepositoryContract $untappd, TumblrRepositoryContract $tumblr, SpotifyRepositoryContract $spotify)
    {
        $this->untappd = $untappd;
        $this->tumblr = $tumblr;
        $this->spotify = $spotify;
    }

    /**
     * Display the homepage.
     *
     * @return Response
     */
    public function index()
    {
        // Get the untappd username
        $untappd_username = getenv('UNTAPPD_USERNAME');

        // Get the untappd brewery id
        $untappd_brewery_id = getenv('UNTAPPD_BREWERY_ID');

        // Get the untappd brewery
        $untappd_brewery = getenv('UNTAPPD_BREWERY');

        // Get the users activity
        $user_activity = $this->untappd->activityFeed([
            'limit' => 5,
            'untappd_username' => $untappd_username,
        ]);

        // Get the brewery activity
        $brewery_activity = $this->untappd->breweryActivityFeed([
            'limit' => 5,
            'untappd_brewery_id' => $untappd_brewery_id,
        ]);

        // Get tumblr posts
        $posts = $this->tumblr->getPosts([
            'account' => 'nathanvrabel.tumblr.com',
            'limit' => 3,
        ]);

        // Get tumblr posts
        $posts_quotes = $this->tumblr->getPosts([
            'account' => 'mylifenathanvrabel.tumblr.com',
            'limit' => 4,
        ]);

        // Get the spotify user
        $spotify_username = getenv('SPOTIFY_USERNAME');

        // Get spotify playlists
        $playlists = $this->spotify->playlists([
            'username' => $spotify_username,
            'limit' => 10,
        ]);

        return view('pages.index', compact(
            'user_activity',
            'brewery_activity',
            'untappd_username',
            'untappd_brewery',
            'posts',
            'posts_quotes',
            'playlists',
            'spotify_username'
        ));
    }
}