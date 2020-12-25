<?php

include('dump.class.php');

//set host
$sql->setDBHost('127.0.0.1');

//set host
$sql->setDBPort('3306');

//set username
$sql->setDBUsername('root');

//set password
$sql->setDBPassword('');

//set DB name
$sql->setDBName('trello');

//set sql file name
$sql->setSQLFileName('peregrin');

//set sql dump path, make sure the path has read and write privileges
$sql->setDumpPath('C:\Users/Name/Desktop/DB');

//Incase there is no mysqldump in your path or its not installed
$sql->setMySQLDump(false);

//set download, if you wanna run it through a browser and download the sql file
$sql->setDownloadSQLFile(true);

// dump
$sql->dumpDB(true);

