<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 12:59 PM
 */

namespace App\IntentHandlers\SpeakerSystem;


class PanIntentHandler extends AbstractSpeakerSystemIntentHandler
{
    private $panDirections = array(
        "left",
        "right",
        "center"
    );

    protected function HandleSpeakerIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        if($this->ValidateRoom($request, $response) === false) return;
        if($this->ValidatePan($request, $response) === false) return;

        $balance = 0; //Default center...

        if($request->slots["Pan"] === "left")
            $balance = -10;
        else if($request->slots["Pan"] === "right")
            $balance = 10;

        $this->getService()->balance($this->rooms[$request->slots["Room"]], $balance);
        $response->respond("Alright!")->endSession()
            ->withCard("Alexa paned the " . $request->slots["Room"] . " speakers to the " . $request->slots["Pan"] . ".");
    }

    protected function ValidatePan(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response) {
        //Validate our slots...
        $errorMsg = "";
        if(!isset($request->slots["Pan"]))
            $errorMsg = "I'm sorry. Please specify a pan direction and try again.";
        else if(!in_array($request->slots["Pan"], $this->panDirections))
            $errorMsg = "I'm sorry, " . $request->slots["Pan"] . " is not a valid pan direction.";

        if($errorMsg != "") {
            $response->respond($errorMsg)->withCard($errorMsg)->endSession();
            return false;
        }
        return true;
    }
}