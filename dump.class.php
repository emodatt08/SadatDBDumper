<?php 

class SadatDBDumper{
    private $username;
    private $password;
    private $dbname;
    private $dbhost;
    private $dumpPath;
    private $sqlFileName;
    private $downloadSQLFile;

    /**
     * Constructor
     */
    public function __construct(){

    }

    public function setDBUsername($username){
        $this->username = $username;
    }

    private function getDBUsername(){
       return $this->username;
    }

    public function setDBPassword($password){
        $this->password = $password;
    }
    
    private function getDBPassword(){
       return $this->password;
    }

    public function setDBName($dbname){
        $this->dbname = $dbname;
    }
    
    private function getDBName(){
       return $this->dbname;
    }

    public function setDBHost($dbhost){
        $this->dbhost = $dbhost;
    }
    
    private function getDBHost(){
       return $this->dbhost;
    }


    public function setDumpPath($dumpPath){
        $this->dumpPath = $dumpPath;
    }
    
    private function getDumpPath(){
       return $this->dumpPath;
    }

    public function setSQLFileName($sqlFileName){
        $this->sqlFileName = $sqlFileName;
    }
    
    private function getSQLFileName(){
       return $this->sqlFileName;
    }


    public function setDownloadSQLFile($downloadSQLFile){
        $this->downloadSQLFile = $downloadSQLFile;
    }
    
    private function getDownloadSQLFile(){
       return $this->downloadSQLFile;
    }

    private function downloadFile($fileName){
        Header('Content-type: application/octet-stream');
        Header("Content-Disposition: attachment; filename=$fileName");
    }



    public function dumpDB(){
        $path = $this->dumpPath."/".$this->getSQLFileName.".sql";
        $user = $this->username;
        $password = $this->password;
        $dbname = $this->dbname;
        $dump = shell_exec("mysqldump --routines -u $user -p$password $dbname > ". $path);    
        if(file_exists($path)) {
            if(isset($this->downloadSQLFile)){
                return $this->downloadFile($path);
            }else{
                header('Content-Type:application/json');
                return json_encode(['responseCode' => '404', 'responseMessage' => 'failure']);      
            }
            
        }else{
            header('Content-Type:application/json');
            return json_encode(['responseCode' => '404', 'responseMessage' => 'failure']);
        }
    }


}


$sql = new SadatDBDumper();