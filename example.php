<?php require_once __DIR__.'/vendor/autoload.php';

use jpuck\calendar\EventSourceMySqlPdo;
use jpuck\calendar\Calendar;

$pdo = require __DIR__.'/cal_DLv92R_A.pdo.php';

$events = new EventSourceMySqlPdo($pdo);

$calendar = new Calendar($events);

echo $calendar;
