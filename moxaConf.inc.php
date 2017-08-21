<?php

define('MOXA_PORT_DATA', 950);
define('MOXA_PORT_COMMAND', 966);

// Byte 0 - 2  Zacatek pakeru

define("PACKET_START",sprintf("%c%c%c",0x1,0x13, 0x54));
define("PACKET_END",sprintf("%c",0x4));
define("PACKET_LENGTH",37);
define("PACKET_BUF_LENGTH",PACKET_LENGTH*2);



$MOXA_STADIUMS = array(

    'zlin' => array(
        'name' => 'zlin',
        'shortcut' => 'ZLN',
        'ip' => '90.182.148.236',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com11',
        'comProtocol' => 'RS485',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'liberec' => array(
        'name' => 'liberec',
        'shortcut' => 'LIB',
        'ip' => '90.182.149.60',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com12',
        'comProtocol' => 'RS485',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'boleslav' => array(
        'name' => 'boleslav',
        'shortcut' => 'MBL',
        'ip' => '90.183.149.53',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com13',
        'comProtocol' => 'RS485',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'pardubice' => array(
        'name' => 'pardubice',
        'shortcut' => 'PAR',
        'ip' => '90.182.147.196',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com14',
        'comProtocol' => 'RS485',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'hradec' => array(
        'name' => 'hradec',
        'shortcut' => 'MHK',
        'ip' => '80.188.42.44',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com16',
        'comProtocol' => 'RS485',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'trinec' => array(
        'name' => 'trinec',
        'shortcut' => 'TRI',
        'ip' => '80.188.65.180',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com17',
        'comProtocol' => 'RS485',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'olomouc' => array(
        'name' => 'olomouc',
        'shortcut' => 'OLO',
        'ip' => '90.183.130.116',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com18',
        'comProtocol' => 'RS485',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'plzen' => array(
        'name' => 'plzen',
        'shortcut' => 'PLZ',
        'ip' => '90.182.147.204',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com19',
        'comProtocol' => 'RS232',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'sparta' => array(
        'name' => 'sparta',
        'shortcut' => 'SPA',
        'ip' => '90.182.147.228',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com20',
        'comProtocol' => 'RS485',
        'comSpeed' => '11920',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'litvinov' => array(
        'name' => 'litvinov',
        'shortcut' => 'LIT',
        'ip' => '90.183.140.51',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com21',
        'comProtocol' => 'RS485',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'vitkovice' => array(
        'name' => 'vitkovice',
        'shortcut' => 'VIT',
        'ip' => '90.182.150.68',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com22',
        'comProtocol' => 'RS485',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'brno' => array(
        'name' => 'brno',
        'shortcut' => 'KOM',
        'ip' => '90.182.147.180',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com23',
        'comProtocol' => 'RS485',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'chomutov' => array(
        'name' => 'chomutov',
        'shortcut' => 'CHM',
        'ip' => '90.182.147.180',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com24',
        'comProtocol' => 'RS485',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'jihlava' => array(
        'name' => 'jihlava',
        'shortcut' => 'JIH',
        'ip' => 'xx.xx.xx.xx',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com25',
        'comProtocol' => 'RS485',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    ),

    'vary' => array(
        'name' => 'vary',
        'shortcut' => 'vary',
        'ip' => '80.188.196.204',
        'portData' => MOXA_PORT_DATA,
        'portCommand' => MOXA_PORT_COMMAND,
        'comPort' => 'com15',
        'comProtocol' => 'RS485',
        'comSpeed' => '9600',
        'timeProtocolVendor' => 'NISA',
        'timeProtocolType' => "001"
    )
);

?>