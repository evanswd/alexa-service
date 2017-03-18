<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 11:51 AM
 */

namespace App\IntentHandlers\SpeakerSystem;

use App\IntentHandlers\CanHandleIntent;
use App\Services\Monoprice\MonopriceService;

abstract class AbstractSpeakerSystemIntentHandler implements CanHandleIntent
{
    protected $denonIP = "192.168.1.151";

    private $_service = null;
    protected function getService() {
        if($this->_service == null)
            $this->_service = new MonopriceService();
        return $this->_service;
    }

    //Room <==> Zome Mapping
    protected $rooms = array(
        "kitchen" => 1,
        "dining room" => 2,
        "office" => 3,
        "master" => 4,
        "master bedroom" => 4,
        "outdoor" => 5,
        "patio" => 5,
        "pool" => 6,
        "kitchen dining room" => array(1,2),
        "kitchen and dining room" => array(1,2),
        "1st floor" => array(1,2,3),
        "all rooms" => array(1,2,3,4,5,6),
        "all speakers" => array(1,2,3,4,5,6),
        "all zones" => array(1,2,3,4,5,6),
        "all outdoor speakers" => array(5,6),
        "all outdoor zones" => array(5,6),
        "all indoor speakers" => array(1,2,3,4),
        "all indoor zones" => array(1,2,3,4),
        //The Man Cave is special!
        "man cave" => null,
        "the man cave" => null
    );

    private $specialIntents = array(
        "ConfirmationIntent",
        "PartyTimeIntent",
        "StatusIntent"
    );

    protected abstract function HandleSpeakerIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response);

    public function HandleIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response) {
        try {
            $this->HandleSpeakerIntent($request, $response);
        }
        finally {
            $this->getService()->dispose();
        }
    }

    protected function ValidateRoom(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response) {
        //Validate our slots...
        $errorMsg = "";
        if(in_array($request->intentName, $this->specialIntents))
        { /* It's special! For now... do nothing and skip validation... */ }
        else if(!isset($request->slots["Room"]))
            $errorMsg = "I'm sorry. Please specify a room and try again.";
        else if(!array_key_exists($request->slots["Room"], $this->rooms))
            $errorMsg = "I'm sorry. There aren't any speakers in the " . $request->slots["Room"];

        if($errorMsg != "") {
            $response->respond($errorMsg)->withCard($errorMsg)->endSession();
            return false;
        }
        return true;
    }
}