<?php

$time_format = 24;


$value = $vars['value'];
if (is_numeric($value)) {
	$hour = floor($value/60);
	$minute = ($value -60*$hour);
	
	// add 1 to avoid pulldown 0 bug
	//$hour++;
	//$minute++;
} else {
	$hour = '0';
	$minute = '0';
}

$hours = array();

for($i=0;$i<$time_format;$i++) {
	$hours[$i] = $i;
}

$minutes = array();

for($i=0;$i<60;$i=$i+5) {
	$minutes[$i] = sprintf("%02d",$i);
}

echo elgg_view('input/dropdown',array('name'=>$vars['name'].'_h','value'=>$hour,'options_values'=>$hours));
echo elgg_view('input/dropdown',array('name'=>$vars['name'].'_m','value'=>$minute,'options_values'=>$minutes));

?>