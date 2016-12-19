<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 2:28 PM
 */

namespace App\IntentHandlers\SpeakerSystem;

use App\IntentHandlers\CanHandleIntent;
use App\Services\Sonos\SonosService;

class PauseIntentHandler implements CanHandleIntent
{
    public function HandleIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        (new SonosService())->pause();
        $response->respond("No problem!")->endSession()
            ->withCard("Alexa paused the music.");
    }
}