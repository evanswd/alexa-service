<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 5:14 PM
 */

namespace App\IntentHandlers\SpeakerSystem;

use App\IntentHandlers\CanHandleIntent;
use App\Services\Sonos\SonosService;

class SearchIntentHandler implements CanHandleIntent
{
    private $searchType = array("album, artist, song");

    public function HandleIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        /*if(!isset($request->slots["SearchType"]) || !in_array($request->slots["SearchType"], $this->searchType)) {
            $errorMsg = "I'm sorry, " . $request->slots["SearchType"] . " is not valid. Please request an artist, album or song and try again.";
            $response->respond($errorMsg)->withCard($errorMsg)->endSession();
            return;
        }*/

        $sonos = new SonosService();
        //$searchType = $request->slots["SearchType"];
        //if($searchType === "artist") $searchType = "station"; //annoying workaround for artists...
        $sonos->search($request->slots["SearchTerms"]); //, $searchType);
        $msg = "Alexa is attempting to search for " . $request->slots["SearchTerms"];
        $response->respond("Let's see what I can find.")->withCard($msg)->endSession();
    }
}