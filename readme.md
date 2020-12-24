## Usage
### make sure mysqldump is installed and in your file path

include class 
```sh
    include('dump.class.php');
```

set host
```sh
    $sql->setDBHost('127.0.0.1');
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
set download, if you wanna run it through a browser and download 
```sh
    $sql->setDownloadSQLFile(true);
```





