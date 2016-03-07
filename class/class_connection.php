<?php
class DB_Connection {

	    public $dbh;

		function __construct()
		{
            // SICS OLD 
            // $hostname = 'localhost';
            // $username = 'root';
            // $password = 'Polo#321';
            // $dbname   = 'db_nuki';

            // SICS NEW
            // $hostname = 'localhost';
            // $username = 'sicsglob_hybrid';
            // $password = 'sics#321';
            // $dbname   = 'sicsglob_nuki';

            // LOCAL
            $hostname = 'localhost';
            $username = 'root';
            $password = '';
            $dbname   = 'sicsglob_nuki';

            try {
                $this->dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$username,$password);
                $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->dbh -> exec('SET NAMES utf8'); // FIX
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }

  		}
}
?>