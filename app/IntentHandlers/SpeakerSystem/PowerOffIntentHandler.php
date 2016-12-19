<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 11:57 AM
 */

namespace App\IntentHandlers\SpeakerSystem;

class PowerOffIntentHandler extends AbstractSpeakerSystemIntentHandler
{
    public function HandleSpeakerIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        if($this->ValidateRoom($request, $response) === false) return;

        $this->getService()->powerOff($this->rooms[$request->slots["Room"]]);
        $response->respond("No problem!")->endSession()
            ->withCard("Alexa turned off the " . $request->slots["Room"] . " speakers.");
    }
}