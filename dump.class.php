<?php 

class SadatDBDumper{
    private $username;
    private $password;
    private $dbname;
    private $dbhost;
    private $dumpPath;
    private $sqlFileName;
    private $downloadSQLFile;
    private $mysqlDump;
    private $path;
    private $conn;
    private $dbport;

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

    private function getDBPort(){
        return $this->dbport;
     }
 
     public function setDBPort($dbport){
         $this->dbport = $dbport;
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

    public function setMySQLDump($mysqlDump){
        $this->mysqlDump = $mysqlDump;
    }
    
    private function getMySQLDump(){
       return $this->mysqlDump;
    }

    private function downloadFile($path, $fileName){
        //var_dump($path, $fileName); die;
        echo file_get_contents($path."/".$fileName);
        header('Content-Description: File Transfer');    
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-Disposition: attachment; filename=\"" . basename($fileName) . "\";");
        //showing the path to the server where the file is to be download
          
    }

    private function dumpType($host, $user, $password,$dbname,$port, $path){
        $options = $this->connect($host, $user, $password,$dbname,$port, $path);
        switch($this->mysqlDump){
            case true;
            $dump = shell_exec("mysqldump --routines -u $user -p$password $dbname > ". $path);
            break;
            default: $this->rawDump($options);
        }
    }

    public function connect($host, $user, $pass,$dbname,$port, $path){  
        $options = array(
            'db_host'=> $host,  //mysql host
            'db_uname' => $user,  //user
            'db_port' => $port,
            'db_password' => $pass, //pass
            'db_to_backup' => $dbname, //database name
            'db_backup_path' => $path, //where to backup
            'db_exclude_tables' => array() //tables to exclude
        );
        return $options;    
    }

    private function rawDump($options){
        ini_set('max_execution_time', 300);
        $mtables = array(); $contents = "-- Database: `".$options['db_to_backup']."` --\n";

            $mysqli = new mysqli($options['db_host'], $options['db_uname'], $options['db_password'], $options['db_to_backup'], $options['db_port']);
            if ($mysqli->connect_error) {
                die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
            }

            $results = $mysqli->query("SHOW TABLES");

            while($row = $results->fetch_array()){
                if (!in_array($row[0], $options['db_exclude_tables'])){
                    $mtables[] = $row[0];
                }
            }

            foreach($mtables as $table){
                $contents .= "-- Table `".$table."` --\n";

                $results = $mysqli->query("SHOW CREATE TABLE ".$table);
                while($row = $results->fetch_array()){
                    $contents .= $row[1].";\n\n";
                }

                $results = $mysqli->query("SELECT * FROM ".$table);
                $row_count = $results->num_rows;
                $fields = $results->fetch_fields();
                $fields_count = count($fields);

                $insert_head = "INSERT INTO `".$table."` (";
                for($i=0; $i < $fields_count; $i++){
                    $insert_head  .= "`".$fields[$i]->name."`";
                        if($i < $fields_count-1){
                                $insert_head  .= ', ';
                            }
                }
                $insert_head .=  ")";
                $insert_head .= " VALUES\n";        

                if($row_count>0){
                    $r = 0;
                    while($row = $results->fetch_array()){
                        if(($r % 400)  == 0){
                            $contents .= $insert_head;
                        }
                        $contents .= "(";
                        for($i=0; $i < $fields_count; $i++){
                            $row_content =  str_replace("\n","\\n",$mysqli->real_escape_string($row[$i]));

                            switch($fields[$i]->type){
                                case 8: case 3:
                                    $contents .=  $row_content;
                                    break;
                                default:
                                    $contents .= "'". $row_content ."'";
                            }
                            if($i < $fields_count-1){
                                    $contents  .= ', ';
                                }
                        }
                        if(($r+1) == $row_count || ($r % 400) == 399){
                            $contents .= ");\n\n";
                        }else{
                            $contents .= "),\n";
                        }
                        $r++;
                    }
                }
            }

            // if (!is_dir ( $options['db_backup_path'] )) {
            //         mkdir ( $options['db_backup_path'], 0777, true );
            // }

            $backup_file_name = $options['db_to_backup'];
            //var_dump($options['db_backup_path'] . '/' . $backup_file_name); die;
            $fp = fopen($options['db_backup_path'],'w+');
            if (($result = fwrite($fp, $contents))) {
                //echo "Backup file created '--$backup_file_name' ($result)"; 
            }
            fclose($fp);
            return $backup_file_name;
         }
              
    



    public function dumpDB(){
        $path = $this->getDumpPath()."/".$this->getSQLFileName().".sql";
        $user = $this->getDBUsername();
        $host = $this->getDBHost();
        $password = $this->getDBPassword();
        $dbname = $this->getDBName();
        $dbport = $this->getDBPort();
        $dump = $this->dumpType($host, $user, $password,$dbname,$dbport,$path);    
        if(file_exists($path)) {
            if($this->getDownloadSQLFile()){
                return $this->downloadFile($this->getDumpPath(), $this->getSQLFileName().".sql");
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