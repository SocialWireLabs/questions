<?php

	gatekeeper();

	$guid = (int) get_input('guid');
        $entity = get_entity($guid);

        if ($entity->getSubtype() == 'question') {
            $question = $entity;
            if ($question->featured){
                $question->featured = false;
                system_message(elgg_echo("questions:alert:question:nofeatured"));
            }
            else {
                $question->featured = true;
                system_message(elgg_echo("questions:alert:question:featured"));
            }
        }
        elseif ($entity->getSubtype() == 'answer') {
            $answer = $entity;
            if ($answer->featured){
                $answer->featured = false;
                system_message(elgg_echo("questions:alert:answer:nofeatured"));
            }
            else {
                $answer->featured = true;
                system_message(elgg_echo("questions:alert:answer:featured"));
            }
	}
        

	forward(REFERER);

?>