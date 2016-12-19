<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 12:01 PM
 */

namespace App\IntentHandlers\SpeakerSystem;

class ConfirmationIntentHandler extends AbstractSpeakerSystemIntentHandler
{
    public function HandleSpeakerIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        if($request->sessionAttributes["Intent"] == "VolumeIntent") {
            $volume = $request->sessionAttributes["Volume"];
            $zone = $this->rooms[$request->sessionAttributes["Room"]];
            $this->getService()->volume($zone, $volume);
            $response->respond("Party Time!")->endSession()
                ->withCard("Alexa set the volume to " . $volume . " for the " . $request->sessionAttributes["Room"]);
        } else {
            $response->respond("I'd love to agree with you, but I am not sure what you are confirming.")->endSession();
        }
    }
}