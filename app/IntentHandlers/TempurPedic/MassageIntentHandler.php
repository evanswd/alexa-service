<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/19/2016
 * Time: 6:25 PM
 */

namespace App\IntentHandlers\TempurPedic;

use App\IntentHandlers\CanHandleIntent;
use App\Services\TempurPedic\TempurPedicService;

class MassageIntentHandler extends AbstractTempurPedicHandler implements CanHandleIntent
{
    private $massageModes = array(
        "constant" => 0,
        "wave" => 1,
        "simultaneous wave" => 2,
        "pulse" => 3
    );

    public function HandleIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        if($this->ValidateBed($request, $response) === false) return;
        if(!isset($request->slots["Mode"]) || !array_key_exists($request->slots["Mode"], $this->massageModes)) {
            $response->respond("I'm sorry, please select a valid massage mode. Valid options are constant, wave, simultaneous wave, or pulse.")->endSession()
                ->withCard($request->slots["Mode"] . " is an invalid massage mode. Please choose: constant, wave, simultaneous wave, or pulse.");
            return;
        }

        $tempur = new TempurPedicService($this->beds[$request->slots["Bed"]]);
        $tempur->massagePreset($this->massageModes[$request->slots["Mode"]]);

        $response->respond("OK! Find your happy place.")->endSession()
            ->withCard("Alexa turned on " . $request->slots["Mode"] . " massage mode for " . $request->slots["Bed"] . " bed.");
    }
}