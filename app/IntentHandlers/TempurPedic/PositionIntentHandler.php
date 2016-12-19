<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 11:05 PM
 */

namespace App\IntentHandlers\TempurPedic;

use App\IntentHandlers\CanHandleIntent;
use App\Services\TempurPedic\TempurPedicService;

class PositionIntentHandler extends AbstractTempurPedicHandler implements CanHandleIntent
{
    public function HandleIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        if($this->ValidateBed($request, $response) === false) return;

        $tempur = new TempurPedicService($this->beds[$request->slots["Bed"]]);
        $tempur->moveToMemoryPosition($request->slots["Position"]);

        $response->respond("Sure thing!")->endSession()
            ->withCard("Alexa adjusted " . $request->slots["Bed"] . " bed to position " . $request->slots["Position"] . ".");
    }
}