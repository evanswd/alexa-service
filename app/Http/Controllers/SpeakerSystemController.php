<?php

namespace App\Http\Controllers;

use Exception;
use Alexa\Request\IntentRequest;
use Laravel\Lumen\Routing\Controller;

class SpeakerSystemController extends Controller
{
    public function index(\Illuminate\Http\Request $request) {
        $jsonDataAsArray = $request->json()->all();
        $alexaRequest = \Alexa\Request\Request::fromData($jsonDataAsArray);
        $response = new \Alexa\Response\Response;

        if ($alexaRequest instanceof IntentRequest) {
            try {
                $intentHandlerType = "App\\IntentHandlers\\SpeakerSystem\\" . $alexaRequest->intentName . "Handler";
                $intentHandler = new $intentHandlerType;
                $intentHandler->HandleIntent($alexaRequest, $response);
            }
            catch(Exception $ex) {
                $response->respond("An error occurred. " . $ex->getMessage());
            }
            finally {
                return response()->json($response->render());
            }
        }
        else {
            $response->respond("I'm sorry. The speaker system didn't understand your request.")
                     ->reprompt("What did you want to do?");
            return response()->json($response->render());
        }
    }
}
