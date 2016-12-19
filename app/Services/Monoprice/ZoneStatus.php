<?php

/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 11/14/2016
 * Time: 12:09 AM
 */

namespace App\Services\Monoprice;

class ZoneStatus
{
    public $Name;
    public $PowerOn;
    public $KeypadConnected;
    public $SelectedSource;
    public $Volume;
    public $Bass;
    public $Treble;
    public $Balance;

    function __construct()
    {
        if(func_num_args() == 1) {
            $data = func_get_arg(0);
            $this->Name = "Zone " . substr($data, 2, 1);
            $this->PowerOn = substr($data, 6, 1) == "1";
            $this->KeypadConnected = substr($data, 22, 1) == "1";
            $this->SelectedSource = substr($data, 19, 2) + 0;
            $this->Volume = substr($data, 11, 2) + 0;
            $this->Bass = substr($data, 15, 2) - 7;
            $this->Treble = substr($data, 13, 2) - 7;
            $this->Balance = substr($data, 17, 2) - 10;
        }
    }
}