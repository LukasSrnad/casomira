<?php



require_once realpath(dirname(__FILE__) . '//moxaConf.inc.php');

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

define('ST', 'trinec');
define('DEBUG_LOG', 1);

define('PACKET_STATUS_OK', 1);
define('PACKET_STATUS_NODATA', 2);
define('PACKET_STATUS_ERROR', 3);


class c_timeTable
{

    private $packet;
    private $packetStatus;
    private $timeTableType;

    public $realtime;
    public $seq;

    public $gameTime; // obj c_time
    public $gamePeriode;

    public $scoreHome;
    public $scoreAvay;

    public $penaltyHome1;
    public $penaltyHome2;

    public $penaltyAvay1;
    public $penaltyAvay2;


    function __construct($type = null)
    {
        $this->seq = -1;
        $this->timeTableType = $type;
        $this->gameTime = new DateTime();
    }


    function setData($npacket, $nseq, $npackeStatus)
    {

        $this->packet = $npacket;
        $this->seq = ++$nseq;
        $this->realtime = time();
        $this->packetStatus = $npackeStatus;

        if ($npackeStatus == PACKET_STATUS_OK) {

            call_user_func(array($this, 'parseData_' . $this->timeTableType));

        }
    }



    // *********** Casomira Trinec**********************

    function parseData_NISA_001()
    {
        $min = $this->packet[15] . $this->packet[16];
        $sec = $this->packet[17] . $this->packet[18];

        $this->gameTime->setTime(0, $min, $sec);
    }



    function printPacketStatus()
    {
        switch ($this->packetStatus) {
            case PACKET_STATUS_OK:
                $r = 'PACKET_STATUS_OK';
                break;
            case PACKET_STATUS_NODATA:
                $r = 'PACKET_STATUS_NO_DATA';
                break;
            case PACKET_STATUS_ERROR:
                $r = 'PACKET_STATUS_ERROR';
                break;

        }

        return $r;

    }




function printData($format = 0)
{
    switch ($format) {
        case 0:

            $data = $this->seq . "-" . $this->realtime . "-" . $this->gameTime->format('i-s-u') . " :" . $this->packet . "- " . $this->printPacketStatus();
            break;
        case 1:
            $data = $this->packet;
            break;

    }

    return $data;

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
        global $MOXA_STADIUMS;


        $this->stadium = $MOXA_STADIUMS[$nstadium];

        $type = $this->stadium['timeProtocolVendor'] . "_" . $this->stadium['timeProtocolType'];

        $this->timeActual = new c_timeTable($type);
        $this->timeLast = new c_timeTable($type);

        if (DEBUG_LOG) {

            $this->fDebug = fopen($this->stadium['name'] . ".txt", "wb") or die("Unable to open file!");
        } else $this->fDebug = null;
    }




    function setData($npacket, $npacketStatus)
    {

        if ($this->timeActual->seq != -1) {

            $p = $this->timeActual;
            $this->timeActual = $this->timeLast;
            $this->timeLast = $p;
        }

        $this->timeActual->setData($npacket, $this->timeLast->seq, $npacketStatus);

        echo $this->timeActual->printData() . "\n";

        if (DEBUG_LOG) {
            fwrite($this->fDebug, $this->timeActual->printData(1));
        }


    }


    function initMoxaSocket()
    {


        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ($this->socket === false) {
            echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
        } else {
            echo "OK.\n";
        }

        $result = socket_connect($this->socket, $this->stadium['ip'], $this->stadium['portData']);
        if ($result === false) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($this->socket)) . "\n";
        } else {
            echo "OK.\n";
        }
    }


    function getMoxaData()
    {

        unset($buf);
        $buf = "";
        $buf_size = 0;
        $count = 0;
        $readSocketData = true;


        while (1) {


            //if ($readSocketData &&  ( $buf_size <= $this->stadium['packetLength'] * PACKET_BUF_LENGTH)) {

            if ($readSocketData) {

                $buf = $buf . socket_read($this->socket, $this->stadium['packetLength'] * PACKET_BUF_LENGTH, PHP_BINARY_READ);
                $buf_size = strlen($buf);
                //echo "Precteno: " . $buf_size . "xxxx: " . $buf. "\n";

            }


            if (($posStart = strpos($buf, $this->stadium['packetStart'])) !== FALSE && $buf_size >= $this->stadium['packetLength']) {

                $packet = substr($buf, $posStart, $this->stadium['packetLength']);

                if ($packet[$this->stadium['packetLength'] - 1] == $this->stadium['packetEnd']) {

                    $this->setData($packet, PACKET_STATUS_OK);
                } else {
                    $this->setData($packet, PACKET_STATUS_ERROR);
                }

                $buf = substr($buf, $posStart + $this->stadium['packetLength']);
                $buf_size = strlen($buf);

                if ($buf_size >= $this->stadium['packetLength']) {
                    $readSocketData = false;
                } else $readSocketData = true;

            } else {

                if (($buf_size >= $this->stadium['packetLengthNoData']) && (($posStart = strpos($buf, $this->stadium['packetNoData'])) !== FALSE)) {

                    $packet = substr($buf, $posStart, $this->stadium['packetLengthNoData']);
                    $this->setData($packet, PACKET_STATUS_NODATA);
                    $buf = substr($buf, $posStart + $this->stadium['packetLengthNoData']);
                    $buf_size = strlen($buf);
                    if ($buf_size >= $this->stadium['packetLengthNoData']) $readSocketData = false;
                    else $readSocketData = true;

                } else {
                    $this->setData($buf, PACKET_STATUS_ERROR);
                    $buf = '';
                    $readSocketData = true;
                    echo "Error \n";


                }
            }
            $count++;

        }

    }


}


if ($argc > 1) {

    $st = $argv[1];
    //$st = 'litvinov';

    $c = new c_timeKeeping($st);

    echo "Startuji casomiru pro: " . $argv[1];


    $c->initMoxaSocket();
    $c->getMoxaData();

}


exit;


socket_close($socket);
?>
