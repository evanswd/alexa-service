<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 11:50 AM
 */

namespace App\IntentHandlers\SpeakerSystem;

use App\Services\Denon\DenonService;
use App\Services\Denon\DenonSources;
use App\Services\Sonos\SonosService;

class PowerOnIntentHandler extends AbstractSpeakerSystemIntentHandler
{
    public function HandleSpeakerIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        if($this->ValidateRoom($request, $response) === false) return;

        //Special override for Man Cave
        if($request->slots["Room"] == "man cave" || $request->slots["Room"] == "the man cave") {
            $denonSvc = new DenonService($this->denonIP);
            $denonSvc->powerOn();
            //Why doesn't my constant work? Oh well...
            $denonSvc->setSource("CD");
        }
        else {
            $volume = 12; //12 is a safe start volume...
            if(isset($request->slots["Volume"]))
                $volume = $request->slots["Volume"];

            $this->getService()->powerOn($this->rooms[$request->slots["Room"]]);
            $this->getService()->volume($this->rooms[$request->slots["Room"]], $volume);
            //Resume whatever was last playing...
            (new SonosService())->play();
        }
        $response->respond("Sure thing!")->endSession()
            ->withCard("Alexa turned on the " . $request->slots["Room"] . " speakers.");
    }
}