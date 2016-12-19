<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 4:33 PM
 */

namespace App\IntentHandlers\SpeakerSystem;

use App\IntentHandlers\CanHandleIntent;

class PowerPlayIntentHandler implements CanHandleIntent
{
    public function HandleIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        $powerOnHandler = new PowerOnIntentHandler();
        $powerOnHandler->HandleIntent($request, $response);
        $playHandler = new PlayIntentHandler();
        $playHandler->HandleIntent($request, $response);
    }
}