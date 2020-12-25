## Usage

include class 
```sh
    include('dump.class.php');
```

set host
```sh
    $sql->setDBHost('127.0.0.1');
```

set port
```sh
    $sql->setDBPort('3306');
```


set username
```sh
    $sql->setDBUsername('root');
```


set password
```sh
    $sql->setDBPassword('temasek209!');
```

set sql file name
```sh
    $sql->setSQLFileName('peregrin');
```

set sql dump path, make sure the path has read and write privileges
```sh
    $sql->setDumpPath('/var/www/');
```

Incase there is no mysqldump in your path or its not installed
```sh
$sql->setMySQLDump(false);
```

set download, if you wanna run it through a browser and download the sql file
```sh
$sql->setDownloadSQLFile(true);
```

set download, if you wanna run it through a browser and download 
```sh
    $sql->setDownloadSQLFile(true);
```

dump MySQL File
```sh
$sql->dumpDB(true);
```





