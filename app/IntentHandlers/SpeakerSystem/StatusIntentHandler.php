<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 12:02 PM
 */

namespace App\IntentHandlers\SpeakerSystem;

class StatusIntentHandler extends AbstractSpeakerSystemIntentHandler
{
    private $lazy = array(
        "kitchen", "dining room", "office", "master bedroom", "patio", "pool"
    );

    public function HandleSpeakerIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        $status = $this->getService()->status();
        if(!isset($request->slots["Room"])) {
            $activeZones = array();
            foreach ($this->lazy as $room)
                if($status[$this->rooms[$room]]->PowerOn) array_push($activeZones, $room);

            if(count($activeZones) >= 6) {
                $response->respond("Everything doode... how's the party?")->endSession();
            }
            else if(count($activeZones) == 0) {
                $response->respond("All zones are currently turned off.")->endSession();
            }
            else {
                $activeZones = join(", ", $activeZones);
                $activeZones = str_replace_last(", ", " and ", $activeZones);
                $response->respond("The " . $activeZones . " speakers are currently turned on, while the rest are off.")->endSession();
            }
        }
        else {
            $zone = $this->rooms[$request->slots["Room"]];
            if($status[$zone]->PowerOn)
                $response->respond("The " . $request->slots["Room"] . " speakers are currently turned on with a volume of " . $status[$zone]->Volume)->endSession();
            else
                $response->respond("The " . $request->slots["Room"] . " speakers are currently turned off.")->endSession();
        }
    }
}