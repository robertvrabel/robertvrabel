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
}
