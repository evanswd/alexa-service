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
        if(!isset($request->slots["Position"]) || $request->slots["Position"] < 1 || $request->slots["Position"] > 4) {
            $response->respond("I'm sorry, please select a position between one and four.")->endSession()
                ->withCard($request->slots["Position"] . " is an invalid bed position. Pleas choose a position between 1 and 4.");
            return;
        }

        $position = $request->slots["Position"] - 1; //The bed starts with a 0 array...
        $tempur = new TempurPedicService($this->beds[$request->slots["Bed"]]);
        $tempur->moveToMemoryPosition($position);

        $response->respond("Sure thing!")->endSession()
            ->withCard("Alexa adjusted " . $request->slots["Bed"] . " bed to position " . $request->slots["Position"] . ".");
    }
}