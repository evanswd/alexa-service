<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 12:04 PM
 */

namespace App\IntentHandlers\SpeakerSystem;

use App\Services\Sonos\SonosService;

class PartyTimeIntentHandler extends AbstractSpeakerSystemIntentHandler
{
    public function HandleSpeakerIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        $zones = $this->rooms["1st floor"];
        $this->getService()->powerOn($zones);
        $this->getService()->volume($zones, 20);
        (new SonosService())->favorite("Sirius XM Chill");

        $response->respond("Scotch Drinkers? Start'chur Engines!")->endSession()
            ->withCard("Alexa is ready to party and turned on the 1st floor zones!");
    }
}