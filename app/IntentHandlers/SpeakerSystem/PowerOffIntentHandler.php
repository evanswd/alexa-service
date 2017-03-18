<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 11:57 AM
 */

namespace App\IntentHandlers\SpeakerSystem;

use App\Services\Denon\DenonService;
use App\Services\Sonos\SonosService;

class PowerOffIntentHandler extends AbstractSpeakerSystemIntentHandler
{
    public function HandleSpeakerIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        if($this->ValidateRoom($request, $response) === false) return;

        //Special override for Man Cave
        if($request->slots["Room"] == "man cave" || $request->slots["Room"] == "the man cave") {
            $denonSvc = new DenonService($this->denonIP);
            $denonSvc->powerOff();
        }
        else {
            $this->getService()->powerOff($this->rooms[$request->slots["Room"]]);

            //Now, test if everything is off... if so, stop Sonos too...
            $allZonesOff = true;
            $statuses = $this->getService()->status();
            foreach ($statuses as $status)
                if($status->PowerOn) $allZonesOff = false;
            if($allZonesOff === true)
                (new SonosService())->pause();
        }

        $response->respond("No problem!")->endSession()
            ->withCard("Alexa turned off the " . $request->slots["Room"] . " speakers.");
    }
}