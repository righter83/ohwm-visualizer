#!/usr/bin/php
<?php

// User 
$user="Valente";
// Folder with CSVs
$folder="OpenHardwareMonitor_Valente";

include_once("db_connect.php");
$db=new db();


$files = scandir($folder);
foreach ($files as $file)
{
	if (preg_match("/\.csv/", $file))
	{
		$row = 1;
		if (($handle = fopen("$folder/$file", "r")) !== FALSE)
		{
   			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
			{
				// Get headers and infos
				if ($row == 2)
				{
    		   		$num = count($data);
       				for	($c=0; $c < $num; $c++)
					{
						if ($data[$c] == "CPU Total")
						{
							$cpu=$c;
							$cores=$data[$c-1];
						}
						if ($data[$c] == "Used Memory")
	                	{
							$ram=$c;
						}
    	   			}
					$tcore=explode("#", $cores);
				}
				if ($row == 3)
				{
					$datum=explode(" ", $data[0]);
					$db->query("insert into datasets (user, date, cores) values ('$user', '$datum[0]', $tcore[1]) ");
					$id=$db->fetch("select id from datasets where user='$user' and date='$datum[0]'");
				}
				if ($row > 3)
		        {
					$data[$cpu]=round($data[$cpu]);
					$data[$ram]=round($data[$ram], 1);
					//echo "CPU $data[$cpu], RAM $data[$ram]\n";
					//echo "insert into data (date,cores,cpu,ram,name) values ('$data[0]', $tcore[1], $data[$cpu], '$data[$ram]', '$user') ";
					$db->query("insert into data (cpu,ram,id) values ($data[$cpu], '$data[$ram]', $id->id) ");
				}
		   		$row++;
			}
	    	fclose($handle);
		}
	}
}

echo "CPU Field is $cpu
Amount of Cores=$tcore[1]
RAM Field is $ram
";
