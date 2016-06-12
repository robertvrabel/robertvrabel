<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tumblr\API\Client;
use App\Repositories\TumblrRepository;

class TumblrRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /** @var TumblrRepository */
    protected $TumblrRepository;

    public function setUp()
    {
        parent::setUp();

        $this->tumblrRepository = new TumblrRepository(new Client(
            getenv('TUMBLR_CONSUMER_KEY'),
            getenv('TUMBLR_SECRET_KEY')
        ));
    }

    /**
     * Initialize the constructor
     *``
     * @covers App\Repositories\TumblrRepository::__construct
     * @test
     */
    public function initialize_constructor()
    {
        $tumblrRepository = new TumblrRepository(new Client(
            getenv('TUMBLR_CONSUMER_KEY'),
            getenv('TUMBLR_SECRET_KEY')
        ));

        $this->assertEquals(get_class($tumblrRepository), TumblrRepository::class);
    }

    /**
     * @covers App\Repositories\TumblrRepository::getPosts
     * @test
     */
    public function getting_posts_should_return_trimmed_posts_and_limit()
    {
        $posts = $this->tumblrRepository->getPosts([
            'account' => 'nathanvrabel.tumblr.com',
            'limit' => 3,
        ]);

        $this->assertCount(3, $posts);
    }
}
