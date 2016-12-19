<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 11:01 PM
 */

namespace App\Http\Controllers;

class TempurPedicController extends AbstractAlexaController
{
    public function index(\Illuminate\Http\Request $request) {
        return $this->processRequest($request, "App\\IntentHandlers\\TempurPedic\\");
    }
}