<?php

include('dump.class.php');

//set host
$sql->setDBHost('127.0.0.1');

//set username
$sql->setDBUsername('root');

//set password
$sql->setDBPassword('Foolishguy08');

//set sql file name
$sql->setSQLFileName('peregrin');

//set sql dump path, make sure the path has read and write privileges
$sql->setDumpPath('/var/www/');

//set download, if you wanna run it through a browser and download the sql file
$sql->setDownloadSQLFile(true);

