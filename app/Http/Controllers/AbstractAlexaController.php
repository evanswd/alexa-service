<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 10:57 PM
 */

namespace App\Http\Controllers;

use Alexa\Request\IntentRequest;
use Exception;
use Laravel\Lumen\Routing\Controller;

abstract class AbstractAlexaController extends Controller
{
    protected function processRequest(\Illuminate\Http\Request $request, $intentNamespace) {
        $jsonDataAsArray = $request->json()->all();
        $alexaRequest = \Alexa\Request\Request::fromData($jsonDataAsArray);
        $response = new \Alexa\Response\Response;

        if ($alexaRequest instanceof IntentRequest) {
            try {
                $intentHandlerType = $intentNamespace . $alexaRequest->intentName . "Handler";
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
            $response->respond("I'm sorry. The system didn't understand your request.")
                ->reprompt("What did you want to do?");
            return response()->json($response->render());
        }
    }
}