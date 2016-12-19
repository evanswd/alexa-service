<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 11/14/2016
 * Time: 11:10 PM
 */

namespace App\Services\Monoprice;

use App\Services\Utilities\TcpPortConnection;

class MonopriceService
{
    private $conn;

    public function __construct()
    {
        $this->conn = new TcpPortConnection("192.168.1.250", "4001");
        $this->conn->OpenConnection();
    }

    public function dispose() {
        $this->conn->CloseConnection();
    }

    private function sendData($data, $expectedLinesOfResponse = 0) {
        try {
            $data = $this->conn->WriteData($data, $expectedLinesOfResponse);
            usleep(100000); //Add a little sleep time because of weird keypad issue
            return $data;
        }
        catch(Exception $ex) {
            //Make sure the exception closes the connection just in case...
            $this->conn->CloseConnection();
            throw $ex;
        }
    }

    public function status() {
        $data = $this->sendData("?10", 6);

        $lines = explode("\r\n", $data);
        $zones[1] = new ZoneStatus($lines[1]);
        $zones[2] = new ZoneStatus($lines[2]);
        $zones[3] = new ZoneStatus($lines[3]);
        $zones[4] = new ZoneStatus($lines[4]);
        $zones[5] = new ZoneStatus($lines[5]);
        $zones[6] = new ZoneStatus($lines[6]);

        return $zones;
    }

    public function powerOn($zones) {
        if(!is_array($zones)) $zones = array($zones);
        foreach($zones as $zone) {
            $this->sendData("<1{$zone}PR01");
        }
    }

    public function powerOff($zones) {
        if(!is_array($zones)) $zones = array($zones);
        foreach($zones as $zone)
            $this ->sendData("<1{$zone}PR00");
    }

    public function volume($zones, $volume) {
        $volume = sprintf('%02d', $volume);

        if(!is_array($zones)) $zones = array($zones);
        foreach($zones as $zone)
            $this ->sendData("<1{$zone}VO{$volume}");
    }

    public function source($zones, $source) {
        if(!is_array($zones)) $zones = array($zones);
        foreach($zones as $zone)
            $this ->sendData("<1{$zone}CH{$source}");
    }

    public function balance($zones, $balance) {
        $balance = sprintf('%02d', $balance + 10);
        if(!is_array($zones)) $zones = array($zones);
        foreach($zones as $zone)
            $this ->sendData("<1{$zone}BL{$balance}");
    }

    public function bass($zones, $bass) {
        $bass = sprintf('%02d', $bass + 7);
        if(!is_array($zones)) $zones = array($zones);
        foreach($zones as $zone)
            $this ->sendData("<1{$zone}BS{$bass}");
    }

    public function treble($zones, $treble) {
        $treble = sprintf('%02d', $treble + 7);
        if(!is_array($zones)) $zones = array($zones);
        foreach($zones as $zone)
            $this ->sendData("<1{$zone}TR{$treble}");
    }
}