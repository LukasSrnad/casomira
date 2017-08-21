<?php


require_once dirname(__FILE__) . '/moxaConf.inc.php';

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

define('ST', 'trinec');
define('DEBUG_LOG', 1);



class c_timeTable
{

    private $packet;
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


    function __construct($type=null)
    {
        $this->seq = 0;
        $this-> timeTableType = $type;

        $this->gameTime = new DateTime();

    }


    function setData($npacket, $nseq)
    {

        $this->packet = $npacket;

        $this->seq = ++$nseq;
        $this->realtime = time();

        call_user_func(array($this,'parseData_'.$this->timeTableType));

    }

    // *********** Casomira Trinec**********************

    function parseData_NISA_001() {

        $min = $this->packet[15] . $this->packet[16];
        $sec = $this->packet[17] .$this->packet[18];

        $this->gameTime->setTime(0,$min,$sec);



////       echo $this->packet;

    }


    function printData($format = 0)
    {
        switch ($format) {
            case 0:
                $data = $this->seq . "-" . $this->realtime . "-" .$this->gameTime->format('i-s-u' ) . "-" . $this->packet;
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

            $this->fDebug = fopen($this->stadium['name']. ".txt", "wb") or die("Unable to open file!");
        } else $this->fDebug = null;


    }

    function setData($packet)
    {

        if ($this->timeActual->seq != 0) {

            $p = $this->timeActual;

            $this->timeActual = $this->timeLast;
            $this->timeLast = $p;

        }

        $this->timeActual->setData($packet, $this->timeLast->seq);

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

        while (1) {

            unset($buf);
            $buf = "";
            $buf_size = 0;


            $buf = $buf . socket_read($this->socket, PACKET_BUF_LENGTH, PHP_BINARY_READ);
            $buf_size = strlen($buf);


            if (($posStart = strpos($buf, PACKET_START)) !== FALSE && $buf_size >= PACKET_LENGTH) {

                $packet = substr($buf, $posStart, PACKET_LENGTH);

                if ($packet[PACKET_LENGTH - 1] == PACKET_END) {

                    $this->setData($packet);
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
