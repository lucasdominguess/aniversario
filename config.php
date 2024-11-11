<?php 

$GLOBALS['TZ'] = new \DateTimeZone( 'America/Sao_Paulo');

$GLOBALS['datefull'] = (new DateTime('now', $GLOBALS['TZ']))->format('Y-m-d H:i:s');
$GLOBALS['datefull2'] = (new DateTime('now', $GLOBALS['TZ']))->format('d-m-Y H:i:s');
$GLOBALS['Ymd'] = (new DateTime('now',$GLOBALS['TZ']))->format('Y-m-d');
$GLOBALS['dmY'] = (new DateTime('now',$GLOBALS['TZ']))->format('d-m-Y');
$GLOBALS['hours'] = (new DateTime('now',$GLOBALS['TZ']))->format('H:i:s');
$GLOBALS['day']=(new DateTime('now',$GLOBALS['TZ']))->format('d');
$GLOBALS['dayWeek']=(new DateTime('now',$GLOBALS['TZ']))->format('D');


define('DATE_FULL', $GLOBALS['datefull']); //2024-11-08 14:04:45
define('DATE_FULL2', $GLOBALS['datefull2']);// 08-11-2024 14:04:45
define('DATE_dmY', $GLOBALS['dmY']); //08-11-2024
define('DATE_Ymd', $GLOBALS['Ymd']);//2024-11-08
define('DAY', $GLOBALS['day']); //08
define('DAY_WEEK', $GLOBALS['dayWeek']); //fri 
define('HOURS', $GLOBALS['hours']); //14:04:05;

const COOKIE_OPTIONS = array(
    'expires' => 3600,
    'path' => '/',
    'secure' => false,
    'httponly' => false,
    'SameSite' => 'Lax'
);