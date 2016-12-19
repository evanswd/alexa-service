<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 2:17 PM
 */

namespace App\IntentHandlers\SpeakerSystem;

use App\IntentHandlers\CanHandleIntent;
use App\Services\Sonos\SonosService;

class PlayIntentHandler implements CanHandleIntent
{
    public function HandleIntent(\Alexa\Request\IntentRequest $request, \Alexa\Response\Response $response)
    {
        $sonos = new SonosService();

        if(isset($request->slots["Favorite"]) && $request->slots["Favorite"] !== null && $request->slots["Favorite"] !== "") {
            $favorites = array_map('strtolower', $sonos->getFavorites());
            if(!in_array(strtolower($request->slots["Favorite"]), $favorites)) {
                $errorMsg = "I'm sorry, " . $request->slots["Favorite"] . " is not a valid music selection.";
                $response->respond($errorMsg)->withCard($errorMsg)->endSession();
                return;
            }

            //If we got here, the "favorite" selection is valid...
            $sonos->favorite($request->slots["Favorite"]);
            $response->respond("Coming Right Up!")->endSession()
                ->withCard("Alexa played " . $request->slots["Favorite"] . " on Sonos.");
        }
        //If a favorite wasn't specified... just play whatever was last queued...
        else {
            $sonos->play();
            $response->respond("Alright!")->endSession()
                ->withCard("Alexa resumed music playback on Sonos.");
        }
    }
}