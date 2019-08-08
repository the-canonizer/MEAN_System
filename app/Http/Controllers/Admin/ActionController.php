<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class ActionController extends Controller
{
  
   public function copydatabase(){
   		ini_set('max_execution_time', 0);
   		$dbhost = 'canoniser-db.czjgaatug9jz.us-east-2.rds.amazonaws.com';
		$dbuser = 'dbinstanceuser';
		$dbpass = '!C+4niZ3rDB!2329#';
		$dbname = 'production';
		$dbport = '3306';
		$dbexportPath = "$dbname.sql";
		if(function_exists('exec')) {
			  $mysqldump =  (PHP_OS_FAMILY =='Windows') ? exec("where mysqldump") : exec("which mysqldump");
   			try{
				 $command = "$mysqldump -P $dbport -h $dbhost -u$dbuser -p$dbpass $dbname > $dbexportPath"; 
				exec($command,$output=array(),$worked);
			}catch(\Exception $e){
					echo "<pre>"; print_r($e->getMessage()); die;
			}
		   
		   switch($worked){
			case 0:
			 $this->importDatabase($dbexportPath);
			break;
			case 1:
			echo 'An error occurred when exporting <b>' .$dbname .'</b>  '.getcwd().'/' .$dbexportPath .'</b>';
			break;
			case 2:
			echo 'An export error has occurred, please check the following information: <br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$dbname .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$dbuser .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$dbhost .'</b></td></tr></table>';
			break;
			}
		}
		
	}

	public function importDatabase($file){
		$dbhost = 'canoniser-db.czjgaatug9jz.us-east-2.rds.amazonaws.com';
		$dbuser = 'dbinstanceuser';
		$dbpass = '!C+4niZ3rDB!2329#';
		$dbname = 'staging';
		$dbport = '3306';
		$query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  '$dbname'";
        $db = DB::select($query);
        $link = mysqli_connect($dbhost, $dbuser, $dbpass);
         $mysql =  (PHP_OS_FAMILY =='Windows') ? exec("where mysql") : exec("which mysql");
        $command = "$mysql -P $dbport -h $dbhost -u$dbuser -p$dbpass  $dbname < $file"; 
        if(empty($db)){
        	$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
			if (mysqli_query($link,$sql)) {
			   exec($command,$output = array(), $worked);
			} else {
			    echo 'Error dropping database: ' . mysqli_error($link) . "\n";
			}
        }else{
        	if (!$link) {
			    die('Could not connect: ' . mysqli_error($link));
			}

			$sql = "DROP DATABASE $dbname";
			if (mysqli_query($link,$sql)) {
				$sql1 = "CREATE DATABASE IF NOT EXISTS $dbname";
				if (mysqli_query($link,$sql1)) {
				   exec($command,$output = array(), $worked);
				} else {
				    echo 'Error dropping database: ' . mysqli_error($link) . "\n";
				}
			} else {
			    echo 'Error dropping database: ' . mysqli_error($link) . "\n";
			}
        }
        switch($worked){
			case 0:
			echo "SUCCESS";
			break;
			case 1:
			echo 'An error occurred when importing <b>' .$dbname .'</b>  '.getcwd().'/' .$file .'</b>';
			break;
			case 2:
			echo 'An export error has occurred, please check the following information: <br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$dbname .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$dbuser .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$dbhost .'</b></td></tr></table>';
			break;
			}
        exit;
            
	}

	public function archievefiles(){
		try{
			if(function_exists('exec')){
				exec('zip -r archive.zip "files/"',$output,$worked);
			}else{
				echo "ERROR";
			}
			
			if($worked == 0){
				echo "SUCCESS";
			}else{
				echo "ERROR";
			}
			exit;
		}catch(\Exception $e){
				echo "<pre> my message "; print_r($e->getMessage()); die;
			}
	}
	
	public function copyfiles(){
			ini_set('max_execution_time', 0);
			try{
				  $url = 'https://canonizer.com/archive.zip';
				  $flag = copy($url,"files1.zip");
				  if($flag){
				  	$file = "files1.zip";				  	
				  	exec('unzip -r files1.zip "files1/"',$output,$worked);
				  	switch($worked){
						case 0:
						echo 'Copy Pase Worked';
						break;
						case 1:
						echo 'An error occurred Copying data';
						break;
						case 2:
						echo 'An export error has occurred';
						break;
						}
				  }
				 

			  }catch(\Exception $e){
				echo "<pre> my message "; print_r($e->getMessage()); die;
			}

exit;
		
			
	}

}
