<?php
	gatekeeper();

	$question_guid = get_input('question_guid');
	$question = get_entity($question_guid);
    $options = array('container_guid' => $question->guid, 'type' => 'object', 'subtype' => 'answer', 'limit' => null);
    $answers = elgg_get_entities_from_metadata($options);

    foreach ($answers as $answer){
        $points	= get_input('points'.$answer->guid);
		$game_points = gamepoints_get_entity($answer->guid);
		if ($points != NULL) {
	   		if ($game_points) {
                  gamepoints_update($game_points->guid, $points);
            } else {
	      		if ($question->who_answers=='member')
	         		$subgroups=false;
	      		else
	         		$subgroups=true;
	      		$description = $question->title;
	      		if (get_entity($question->container_guid) instanceof ElggGroup)
	         		gamepoints_add($answer->owner_guid,$points,$answer->guid,$question->container_guid,$subgroups,$description);
	      		else
	         		gamepoints_add($answer->owner_guid,$points,$answer->guid,null,$subgroups,$description);
	   		}
		} else {
	   		if ($game_points) {
                gamepoints_update($game_points->guid,$points);
            }
		}
    }

	forward(REFERER);
?>