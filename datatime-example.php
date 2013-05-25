<?php
$tz = 'Europe/Amsterdam';
$ts = date_create( "31-12-2013 23:59 $tz" )->format( 'U' );

$d = new DateTime( "@$ts" );
$d->setTimeZone( new DateTimeZone( $tz ) );

echo $d->format( 'U d-m-Y H:i' ), "\n";

date_default_timezone_set('Europe/Amsterdam');
$date = date('d-m-Y h:i:s a', time());

$ts2 = date_create($date)->format('U');

echo("<br/>" . $date . " " . $ts2);
?>