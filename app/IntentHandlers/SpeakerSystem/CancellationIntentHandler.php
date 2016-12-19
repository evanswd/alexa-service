<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 12:02 PM
 */

namespace App\IntentHandlers\SpeakerSystem;

use App\IntentHandlers\CanHandleIntent;

class CancellationIntentHandler implements CanHandleIntent
{
    public function HandleIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        $response->respond("OK")->endSession();
    }
}