<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 11:59 AM
 */

namespace App\IntentHandlers\SpeakerSystem;

class VolumeIntentHandler extends AbstractSpeakerSystemIntentHandler
{
    public function HandleSpeakerIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        if($this->ValidateRoom($request, $response) === false) return;

        if($request->slots["Volume"] > 25) {
            $response->sessionAttributes["Intent"] = "VolumeIntent";
            $response->sessionAttributes["Room"] = $request->slots["Room"];
            $response->sessionAttributes["Volume"] = $request->slots["Volume"];
            $response->respond("That's pretty loud. Are you sure?")->reprompt("");
        } else {
            $zone = $this->rooms[$request->slots["Room"]];
            $this->getService()->volume($zone, $request->slots["Volume"]);
            $response->respond("Alright!")->endSession()
                ->withCard("Alexa set the volume to " . $request->slots["Volume"] . " for the " . $request->slots["Room"]);
        }
    }
}