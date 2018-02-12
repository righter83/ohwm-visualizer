<?php
// DB Settings
class db
{
	function connectDb()
	{
		// DB Settings Change this to you MySQL Login
		$host="localhost";
		$user="hwmon";
		$pass="123pass";
		$dbname="hwmon";

		// DB Connector
		$this->db = new mysqli($host, $user, $pass, $dbname);
		if (mysqli_connect_errno())
		{
    			printf("Can't connect to MySQL Server. Errorcode: %s\n", mysqli_connect_error());
		    	exit;
		}
		$this->db->set_charset("utf8");
	}

	// executes a query directly or prepare for multiple results for fetch()
        function query($sql)
        {
        $this->connectDb();

                // check if update or select statement
                if (preg_match('/^update/', $sql))
                {
                        // update,insert or delete
                        if ($this->db->query($sql))
                        {
                                return true;
                        }
                        else
                        {
                                echo mysqli_error();
                                return false;
                        }
                }
                else
                {
                        $this->s=$this->db->query($sql) or die ("Wrong query    ".mysqli_error());
            }
        $this->closedb();
    }


	// get results from a query above
    // fetch the results and return the single row or an array if multiple results
    function fetch($sql)
    {
        $i=0;
        $this->connectDb();
        $query=$this->db->query($sql) or die ("Wrong query  $sql  ".mysqli_error());
        while ($row=$query->fetch_object())
        {
        	$rows[]=$row;
            $i++;
        }

        // if only 1 result transorm the object to an array
        if ($i == 1)
        {
            return $rows[0];

        }
        return $rows;
        $this->closedb();
	}

	function closedb()
	{
		$this->db->close();
	}

}
?>
