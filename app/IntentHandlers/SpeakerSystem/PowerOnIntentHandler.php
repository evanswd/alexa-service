<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 11:50 AM
 */

namespace App\IntentHandlers\SpeakerSystem;

class PowerOnIntentHandler extends AbstractSpeakerSystemIntentHandler
{
    public function HandleSpeakerIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        if($this->ValidateRoom($request, $response) === false) return;

        $volume = 12; //12 is a safe start volume...
        if(isset($request->slots["Volume"]))
            $volume = $request->slots["Volume"];

        $this->getService()->powerOn($this->rooms[$request->slots["Room"]]);
        $this->getService()->volume($this->rooms[$request->slots["Room"]], $volume);
        $response->respond("Sure thing!")->endSession()
            ->withCard("Alexa turned on the " . $request->slots["Room"] . " speakers.");
    }
}