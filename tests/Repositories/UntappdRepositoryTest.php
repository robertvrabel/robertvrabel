<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;
use App\Repositories\UntappdRepository;

class UntappdRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /** @var UntappdRepository */
    protected $untappdRepository;

    public function setUp()
    {
        parent::setUp();

        $this->untappdRepository = new UntappdRepository(new Carbon);
    }

    /**
     * Initialize the constructor
     *
     * @covers App\Repositories\UntappdRepository::__construct
     * @test
     */
    public function initialize_constructor()
    {
        $untappdRepository = new UntappdRepository(new Carbon);

        $this->assertEquals(get_class($untappdRepository), UntappdRepository::class);
    }

    /**
     * @covers App\Repositories\UntappdRepository::activityFeed
     * @test
     */
    public function user_activity_feed_should_return_checkins()
    {
        // Get the users activity
        $user_activity = $this->untappdRepository->activityFeed([
            'limit' => 5,
            'untappd_username' => getenv('UNTAPPD_USERNAME'),
        ]);

        $this->assertCount(5, $user_activity);
    }

    /**
     * @covers App\Repositories\UntappdRepository::breweryActivityFeed
     * @test
     */
    public function brewery_activity_feed_should_return_brewery_checkins()
    {
        // Get the brewery activity
        $brewery_activity = $this->untappdRepository->breweryActivityFeed([
            'limit' => 5,
            'untappd_brewery_id' => getenv('UNTAPPD_BREWERY_ID'),
        ]);

        $this->assertCount(5, $brewery_activity);
    }

    /**
     * @covers App\Repositories\UntappdRepository::filterCheckinsByUser
     * @test
     */
    public function brewery_activity_feed_should_not_show_me()
    {
        // Get the brewery activity
        $this->untappdRepository->breweryActivityFeed([
            'limit' => 5,
            'untappd_brewery_id' => getenv('UNTAPPD_BREWERY_ID'),
        ])->each(function($item) {
            $item['user']['user_name'] == getenv('UNTAPPD_USERNAME') ? $this->assertTrue(false) : $this->assertTrue(true);
        });
    }
}
