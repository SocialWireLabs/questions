<?php
        gatekeeper();
        $question = get_entity(get_input('guid'));

        if ($question->closing != 'date')
            $question->status = 'open';
        else {
            $question->closing = 'never';
            $question->status = 'open';
            system_message (elgg_echo('questions:warning:question_open'));
        }
        
        forward(REFERER);
?>