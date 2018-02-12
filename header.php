<?php

include_once("db_connect.php");
$db=new db();

$sets=$db->fetch("select * from datasets");
foreach ($sets as $set)
{
	echo "<a href=\"show_data.php?id=$set->id\" target=body>$set->user $set->date</a><br>";
}
