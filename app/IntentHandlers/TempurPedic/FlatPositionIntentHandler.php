<?php

/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 11:04 PM
 */
namespace App\IntentHandlers\TempurPedic;

use App\IntentHandlers\CanHandleIntent;
use App\Services\TempurPedic\TempurPedicService;

class FlatPositionIntentHandler extends AbstractTempurPedicHandler implements CanHandleIntent
{
    public function HandleIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        if($this->ValidateBed($request, $response) === false) return;

        $tempur = new TempurPedicService($this->beds[$request->slots["Bed"]]);
        $tempur->flat();

        $response->respond("Sure thing!")->endSession()
            ->withCard("Alexa adjusted " . $request->slots["Bed"] . " bed to flat.");
    }
}