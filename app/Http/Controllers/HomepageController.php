<?php
namespace App\Http\Controllers;

use App\Library\Untappd;
use App\Library\Tumblr;
use App\Library\Spotify;

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
    public function __construct(Untappd $untappd, Tumblr $tumblr, Spotify $spotify)
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
            'limit' => 6,
        ]);

        // Get Spotify Playlists
        $playlists = $this->spotify->playlists([
            'username' => 'robertvrabel',
            'limit' => 10,
        ]);

        return view('pages.index', compact(
            'user_activity',
            'brewery_activity',
            'untappd_username',
            'untappd_brewery',
            'posts',
            'posts_quotes',
            'playlists'
        ));
    }
}