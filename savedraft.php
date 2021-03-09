<?php
	
// Load engine
require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');
gatekeeper();

// Get input data
$answer_guid = $_POST['guid'];
$points = $_POST['points'];

$answer = get_entity($answer_guid);
$question = get_entity($answer->container_guid);

//Update_annotation
if (get_entity($question->container_guid) instanceof ElggGroup) {
   gamepoints_add($answer->owner_guid, $points, $answer->guid, $question->container_guid);
} else { 
   gamepoints_add($answer->owner_guid, $points, $answer->guid);
}
?>