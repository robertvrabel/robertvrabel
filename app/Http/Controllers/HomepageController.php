<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use GuzzleHttp;

class HomepageController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display the homepage.
     *
     * @return Response
     */
    public function index() {
        return view('pages.index');
    }
}