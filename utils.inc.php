<?php


function strpos_all($haystack, $needle)
{

    $offset = 0;
    $allpos = array();

    while (($pos = strpos($haystack, $needle, $offset)) !== FALSE) {
        $offset = $pos + 1;
        $allpos[] = $pos;
    }
    return $allpos;
}



$fh = fopen("./dataxxxx.txt", "wb");

$i = 1;

function timeParse($npacket)
{

    global $fh;

    echo $npacket;
    fwrite($fh, $npacket);

}

?>


