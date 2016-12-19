<?php

namespace App\Http\Controllers;

use App\Services\Sonos\SonosService;
use Laravel\Lumen\Routing\Controller;

class TestController extends Controller
{
    public function index() {
        $sonos = new SonosService();
        return response()->json($sonos->search("jingle bells"));
    }
}
