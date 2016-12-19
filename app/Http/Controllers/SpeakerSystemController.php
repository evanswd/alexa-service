<?php

namespace App\Http\Controllers;

class SpeakerSystemController extends AbstractAlexaController
{
    public function index(\Illuminate\Http\Request $request) {
        return $this->processRequest($request, "App\\IntentHandlers\\SpeakerSystem\\");
    }
}
