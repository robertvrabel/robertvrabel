<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\Session;
use App\Repositories\SpotifyRepository;

class SpotifyRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /** @var SpotifyRepository */
    protected $spotifyRepository;

    public function setUp()
    {
        parent::setUp();

        $this->spotifyRepository = new SpotifyRepository(
            new Session(getenv('SPOTIFY_CLIENT_ID'), getenv('SPOTIFY_CLIENT_SECRET')),
            New SpotifyWebAPI
        );
    }

    /**
     * Initialize the constructor
     *
     * @covers App\Repositories\SpotifyRepository::__construct
     * @test
     */
    public function initialize_constructor()
    {
        $spotifyRepository = new SpotifyRepository(
            new Session(getenv('SPOTIFY_CLIENT_ID'), getenv('SPOTIFY_CLIENT_SECRET')),
            New SpotifyWebAPI
        );

        $this->assertEquals(get_class($spotifyRepository), SpotifyRepository::class);
    }
}
