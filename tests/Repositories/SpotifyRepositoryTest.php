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

    /**
     * @covers App\Repositories\SpotifyRepository::playlists
     * @test
     */
    public function playlists_should_return_users_playlists()
    {
        $playlists = $this->spotifyRepository->playlists([
            'username' => 'robertvrabel'
        ]);

        $this->assertTrue(count($playlists) > 0);
    }
}
