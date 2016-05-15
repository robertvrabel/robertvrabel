<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Library\Untappd;

class HomepageController extends Controller
{
    /**
     * HomepageController constructor.
     * @param Untappd $untappd
     */
    public function __construct(Untappd $untappd)
    {
        $this->untappd = $untappd;
    }

    /**
     * Display the homepage.
     *
     * @return Response
     */
    public function index() {
        // Get the untappd username
        $untappd_username = getenv('UNTAPPD_USERNAME');

        // Get the untappd brewery
        $untappd_brewery_id = getenv('UNTAPPD_BREWERY_ID');

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

        return view('pages.index', compact('user_activity', 'brewery_activity', 'untappd_username'));
    }
}