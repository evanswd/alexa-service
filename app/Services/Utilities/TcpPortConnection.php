<?php
/**
 * Created by PhpStorm.
 * User: Bill
 * Date: 11/13/2016
 * Time: 11:01 PM
 */

namespace App\Services\Utilities;

class TcpPortConnection
{
    private $ipAddress;
    private $port;
    private $Socket;
    //private $lock;

    function __construct($ipAddress, $port)
    {
        $this->ipAddress = $ipAddress;
        $this->port = $port;
        //$this->lock = new object();
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Unable to create socket\n");
        //TODO: Receive Timeout?
    }

    public function OpenConnection()
    {
        socket_connect($this->socket, $this->ipAddress, $this->port);
    }

    public function CloseConnection()
    {
        socket_close($this->socket);
    }

    public function WriteData($data, $expectedLinesOfResponse)
    {
        //Set the default value
        $expectedLinesOfResponse = $expectedLinesOfResponse ?: 0;
        //Set the check and result values
        $match = "/\\r\\n/";
        $response = "";

        $msg = utf8_encode($data . "\r");
        try {
            socket_send($this->socket, $msg, strlen($msg), 0); //MSG_EOR);
            $receiveBuffer = "";

            while($receivedBytes = socket_recv($this->socket, $receiveBuffer, 1, 0)) {
                $response .= $receiveBuffer;

                $totalMatches = preg_match_all($match, $response);
                if($totalMatches == $expectedLinesOfResponse + 1)
                    break;
            }
        } catch (Exception $ex) {
            //Timeout reached...
            return $ex;
        }

        //TODO: Ditch the hashtags...
        return str_replace("#", "", $response);
    }
}