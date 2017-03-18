<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 3/18/2017
 * Time: 5:42 PM
 */

namespace App\Services\Denon;


class DenonService
{
    private $ipAddress;
    private $baseUrl;

    function __construct($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        $this->baseUrl = "http://" . $this->ipAddress . "/MainZone/index.put.asp?cmd0=";
    }

    private $sources = array(
        "SAT/CBL",
        "BD",
        "GAME",
        "CD",
        "DVD",
        "AUX1",
        "AUX2",
        //"Online Music",
        "MPLAY",
        //"Tuner",
        "PHONO",
        /*"Media Server",
        "TV Audio",
        "Bluetooth",
        "iPod/USB",
        "Internet Radio"*/
    );

    /* gets the data from a URL */
    private function getData($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    private function sendRequest($action) {
        return $this->getData($this->baseUrl . str_replace("/", "%2F", str_replace(" ", "%20", $action)));
    }

    public function powerOn() {
        $this->sendRequest("PutZone_OnOff/ON");
    }

    public function powerOff() {
        $this->sendRequest("PutZone_OnOff/OFF");
    }

    public function muteOn() {
        $this->sendRequest("PutVolumeMute/ON");
    }

    public function muteOff() {
        $this->sendRequest("PutVolumeMute/OFF");
    }

    public function setVolume($volume) {
        if(!is_numeric($volume) || $volume < 0 || $volume > 80)
            throw new \Exception("The volume must be between 0 and 80.");

        //The volume is actually a negative number offset from 80.
        $volume = $volume - 80;
        $this->sendRequest("PutMasterVolumeSet/" . $volume);
    }

    public function setSource($source) {
        if(!in_array($source, $this->sources))
            throw new \Exception("The source '" . $source . "' is an invalid option.'");

        $this->sendRequest("PutZone_InputFunction/" . $source);
    }
}