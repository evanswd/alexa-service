<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 1:56 PM
 */

namespace App\Services\Sonos;


class SonosService
{
    private $baseUrl = "http://192.168.1.10:5005/";
    private $zones = array(
        "Audio Source 1",
        "Man Cave",
        "Den"
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
        return json_decode($this->getData($this->baseUrl . str_replace(" ", "%20", $action)));
    }

    private function sendZoneRequest($action, $zone) {
        if($zone === null || $zone === "")
             $zone = $this->zones[0];
        return $this->sendRequest($zone . "/" . $action);
    }

    public function getFavorites() {
        return $this->sendRequest("favorites");
    }

    public function play($zone = null) {
        return $this->sendZoneRequest("play", $zone);
    }

    public function pause($zone = null) {
        return $this->sendZoneRequest("pause", $zone);
    }

    public function favorite($favorite, $zone = null) {
        return $this->sendZoneRequest("favorite/" . $favorite, $zone);
    }

    public function next($zone = null) {
        return $this->sendZoneRequest("next", $zone);
    }

    public function previous($zone = null) {
        return $this->sendZoneRequest("previous", $zone);
    }

    public function search($terms, $type = "song", $service = "spotify", $zone = null) {
        $terms = str_replace(" ", "+", $terms);
        return $this->sendZoneRequest("musicsearch/$service/$type/$terms", $zone);
    }
}