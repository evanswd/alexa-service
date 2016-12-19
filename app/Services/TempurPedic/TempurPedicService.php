<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 12/18/2016
 * Time: 9:07 PM
 */

namespace App\Services\TempurPedic;


class TempurPedicService
{
    private $ipAddress;
    private $port;
    private $socket;

    function __construct($ipAddress, $port = 50007)
    {
        $this->ipAddress = $ipAddress;
        $this->port = $port;
    }

    private function sendData($data) {
        try {
            $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP) or die("Unable to create socket\n");
            socket_sendto($this->socket, $data, strlen($data), 0, $this->ipAddress, $this->port);
        }
        finally {
            socket_close($this->socket);
        }
    }


    public function flat() {
        $this->moveToMemoryPosition(4);
    }

    public function moveToMemoryPosition($position) {
        if($position < 0 || $position > 4)
            throw new \Exception("Invalid TempurPedic Memory Position");

        $checksum = $position ^ 200;
        $message = pack("C9", $this->fpc_in_leave, 5, $this->fpc_in_initialize_x, 10,
            148, //This was -108 in the decompiled code but 148 is its inverse...
            $this->cSBPathSeparator,
            $position,
            0, //Index 7 is ignored...
            $checksum);
        $this->sendData($message);
    }

    public function massagePreset($preset) {
        if($preset < 0 || $preset > 3)
            throw new \Exception("Invalid TempurPedic Massage Preset");

        $checksum = ($preset ^ 25) ^ 120;
        $message = pack("C9", $this->fpc_in_leave, 5, $this->fpc_in_initialize_x, 3,
            148, //This was -108 in the decompiled code but 148 is its inverse...
            141, //This was -115 in the decompiled code but 141 is its inverse...
            $preset,
            $this->fpc_in_trunc_real,
            $checksum);
        $this->sendData($message);
    }

    public function stopMassage() {
        $message = pack("C9", $this->fpc_in_leave, 5, $this->fpc_in_initialize_x, 10,
            148, //This was -108 in the decompiled code but 148 is its inverse...
            $this->SB_ASN1_A6_PRIMITIVE, 0, 0,
            $this->vtUnicodeString);
        $this->sendData($message);
    }

    //weird TempurPedic constants... (Looks like this was somehow ported from Pascal???
    private $cSBPathSeparator = 92;
    private $fpc_in_initialize_x = 50;
    private $fpc_in_leave = 51;
    private $fpc_in_trunc_real = 120;
    private $SB_ASN1_A6_PRIMITIVE = 134; //was -122;
    private $vtUnicodeString = 18;
}