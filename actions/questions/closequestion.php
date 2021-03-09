<?php

        gatekeeper();

	$question = get_entity(get_input('guid'));

        $question->closing = 'never';
	$question->status = 'closed';

	forward(REFERER);
?>