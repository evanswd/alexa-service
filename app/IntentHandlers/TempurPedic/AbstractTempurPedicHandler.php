<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 11:06 PM
 */

namespace App\IntentHandlers\TempurPedic;


abstract class AbstractTempurPedicHandler
{
    private $ipBill = "192.168.1.100";
    private $ipIsabella = "192.168.1.115";

    protected $beds = array();

    function __construct() {
        $this->beds = array(
            "bill" => $this->ipBill,
            "bills" => $this->ipBill,
            "bill's" => $this->ipBill,
            "hillside" => $this->ipBill,
            "isabella" => $this->ipIsabella,
            "isabellas" => $this->ipIsabella,
            "isabella's" => $this->ipIsabella,
            "Isabella's" => $this->ipIsabella,
            "love bug" => $this->ipIsabella,
            "love bugs" => $this->ipIsabella,
            "love bug's" => $this->ipIsabella,
            "lovebugs" => $this->ipIsabella,
        );
    }

    protected function ValidateBed(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response) {
        //Validate our slots...
        $errorMsg = "";
        if(!isset($request->slots["Bed"]))
            $errorMsg = "I'm sorry. Please specify a bed and try again.";
        else if(!array_key_exists($request->slots["Bed"], $this->beds))
            $errorMsg = "I'm sorry. There is no bed named " . $request->slots["Bed"];

        if($errorMsg != "") {
            $response->respond($errorMsg)->withCard($errorMsg)->endSession();
            return false;
        }
        return true;
    }
}