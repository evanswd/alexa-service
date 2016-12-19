<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/19/2016
 * Time: 6:21 PM
 */

namespace App\IntentHandlers\TempurPedic;

use App\IntentHandlers\CanHandleIntent;
use App\Services\TempurPedic\TempurPedicService;

class StopMassageIntentHandler extends AbstractTempurPedicHandler implements CanHandleIntent
{
    public function HandleIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        if($this->ValidateBed($request, $response) === false) return;

        $tempur = new TempurPedicService($this->beds[$request->slots["Bed"]]);
        $tempur->stopMassage();

        $response->respond("No problem!")->endSession()
            ->withCard("Alexa stopped massage on " . $request->slots["Bed"] . " bed.");
    }
}