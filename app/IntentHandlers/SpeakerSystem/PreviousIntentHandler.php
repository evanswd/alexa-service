<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 7:41 PM
 */

namespace App\IntentHandlers\SpeakerSystem;

use App\IntentHandlers\CanHandleIntent;
use App\Services\Sonos\SonosService;

class PreviousIntentHandler implements CanHandleIntent
{
    public function HandleIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        (new SonosService())->previous();
        $response->respond("No problem!")->endSession()
            ->withCard("Alexa rewound to the previous song.");
    }
}