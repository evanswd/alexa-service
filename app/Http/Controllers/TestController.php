<?php

namespace App\Http\Controllers;

use App\Services\Denon\DenonService;
use App\Services\Sonos\SonosService;
use App\Services\TempurPedic\TempurPedicService;
use Laravel\Lumen\Routing\Controller;

class TestController extends Controller
{
    public function index() {
        /*$tempur = new TempurPedicService("192.168.1.100");
        $tempur->flat();*/

        $denon = new DenonService("192.168.1.151");
        $denon->powerOn();
    }
}
