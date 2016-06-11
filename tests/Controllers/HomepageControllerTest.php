<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\HomepageController;
use App\Repositories\UntappdRepository;
use App\Repositories\TumblrRepository;
use App\Repositories\SpotifyRepository;
use Carbon\Carbon;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\Session;
use Tumblr\API\Client;

class HomepageControllerTest extends TestCase
{
    /**
     * Initialize the constructor
     *
     * @covers App\Http\Controllers\HomepageController::__construct
     * @test
     */
    public function initialize_constructor()
    {
        $homepageController = new HomepageController(
            new UntappdRepository(new Carbon),
            new TumblrRepository(new Client(
                getenv('TUMBLR_CONSUMER_KEY'),
                getenv('TUMBLR_SECRET_KEY')
            )),
            new SpotifyRepository(
                new Session(getenv('SPOTIFY_CLIENT_ID'), getenv('SPOTIFY_CLIENT_SECRET')),
                New SpotifyWebAPI
            )
        );

        $this->assertEquals(get_class($homepageController), HomepageController::class);
    }

    /**
     * @covers App\Http\Controllers\FirstCheckinController::index
     * @test
     */
    public function index_should_show_homepage()
    {
        $this->visit('/')
            ->see('ROBERTVRABEL.COM');
    }
}
