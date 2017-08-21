<?php


require_once dirname(__FILE__) . './moxaConf.inc.php';

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

define('ST', 'trinec');
define('DEBUG_LOG', 1);



class c_time
{

    public $time;

    function __construct()
    {

    }
}


class c_timeTable
{

    public $packet;

    public $realtime;
    public $seq;

    public $gameTime; // obj c_time
    public $gamePperiode;

    public $scoreHome;
    public $scoreAvay;

    public $penaltyHome1;
    public $penaltyHome2;

    public $penaltyAvay1;
    public $penaltyAvay2;

    function __construct()

    {
        $this->seq = 0;

    }


    function setData($npacket, $nseq)
    {

        $this->packet = $npacket;

        $this->seq = ++$nseq;
        $this->realtime = time();

        echo $this->seq . "-" . $this->realtime . "-" . $npacket . ".\n";

    }


}


class  c_timeKeeping
{

    private $socket;
    private $stadium;


    public $timeActual;
    public $timeLast;

    public $fDebug;


    function __construct($nstadium)
    {
        $this->stadium = $nstadium;

        $this->timeActual = new c_timeTable();
        $this->timeLast = new c_timeTable();

        if (DEBUG_LOG) {

            
        }
    }

    function setData($npacket,$npacketStatus)
    {

        if ($this->timeActual->seq != 0) {

            $p = $this->timeActual;

            $this->timeActual = $this->timeLast;
            $this->timeLast = $p;

        }

        $this->timeActual->setData($npacket, $this->timeLast->seq, $npacketStatus);


    }


    function initMoxaSocket()
    {

        global $MOXA_STADIUMS;

        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ($this->socket === false) {
            echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
        } else {
            echo "OK.\n";
        }

        $result = socket_connect($this->socket, $MOXA_STADIUMS[$this->stadium]['ip'], $MOXA_STADIUMS[$this->stadium]['portData']);
        if ($result === false) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($this->socket)) . "\n";
        } else {
            echo "OK.\n";
        }
    }


    function getMoxaData()
    {

        while (1) {

            unset($buf);
            $buf = "";
            $buf_size = 0;


            $buf = $buf . socket_read($this->socket, PACKET_BUF_LENGTH, PHP_BINARY_READ);
            $buf_size = strlen($buf);


            if (($posStart = strpos($buf, PACKET_START)) !== FALSE && $buf_size >= PACKET_LENGTH) {

                $packet = substr($buf, $posStart, PACKET_LENGTH);

                if ($packet[PACKET_LENGTH - 1] == PACKET_END) {

                    $this->setData($packet, PACKET_STATUS_OK);
                    $buf = substr($buf, $posStart + PACKET_LENGTH);


                } else echo "Nemam data \n";
            }

        }

    }

}


$c = new c_timeKeeping(ST);

$c->initMoxaSocket();
$c->getMoxaData();


exit;


socket_close($socket);
?>
