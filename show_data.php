<?php
ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 600);
include_once("db_connect.php");
$db=new db();
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<canvas id="both-hourly" width="600" height="300"></canvas>
<canvas id="canvas"></canvas>

<?php

// calc intermediate line
$sum=$db->fetch("select sum(cpu) as sum from data where id=$_GET[id]");
$amount=$db->fetch("select count(cpu) as count from data where id=$_GET[id]");
$average=round($sum->sum/$amount->count);

// get data from DB
$set=$db->fetch("select * from datasets where id=$_GET[id]");
$data=$db->fetch("select * from data where id=$set->id");
$labels="";
$vals="";
$vals2="";
$vals3="";
$x=0;
foreach ($data as $res)
{
	//$labels="$set->date,".$labels;
	$labels=",".$labels;
	$val=$res->cpu;
	$vals="\"$val\",".$vals;
	$val2=$res->ram;
	$vals2="\"$val2\",".$vals2;
	$vals3="\"$average\",".$vals3;
}
?>

<script>
new Chart(document.getElementById("both-hourly"),
{
	type: 'line',
  	data:
	{
    	labels: [<?php echo $labels; ?>],
    	datasets:
		[
            {
                data: [<?php echo $vals2; ?>],
                label: "RAM usage in GB",
                borderColor: "orange",
                fill: false
            },
			{
                data: [<?php echo $vals3; ?>],
                label: "Average CPU usage is <?php echo $average; ?>%",
                borderColor: "green",
                fill: false
            },
			{
        		data: [<?php echo $vals; ?>],
        		label: "CPU Total in % (Cores: <?php echo $set->cores; ?>)",
	        	borderColor: "#3e95cd",
    	    	fill: false
      		}
		]
  	},
	options:
	{
    	title:
		{
      		display: true,
      		text: 'Performance Data of User <?php echo "$set->user on day $set->date"; ?>'
    	},
		responsive: true,
		elements:
		{
			point:
			{
				radius: 0
			}
		},
		scales:
		{
        	yAxes:
			[
				{
            		display: true,
            		ticks:
					{
						suggestedMin: 4,
						suggestedMax: 13,
		                //beginAtZero: true   // minimum value will be 0.
        		    }
        		}
			]
    	}
  	},
});
</script>

