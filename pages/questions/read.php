<?php
	$question_guid = get_input('guid');
        $question = get_entity($question_guid);
        if (!$question) {
                forward();
        }

        elgg_set_page_owner_guid($question->getContainerGUID());

        group_gatekeeper();

        $container = elgg_get_page_owner_entity();

        $title = $question->title;

        if (elgg_instanceof($container, 'group')) {
                elgg_push_breadcrumb($container->name, "questions/group/$container->guid/all");
        } else {
                elgg_push_breadcrumb($container->name, "questions/owner/$container->username");
        }

        elgg_push_breadcrumb($title);

        $content = elgg_view_entity($question, array('full_view' => true));
        $content .= elgg_view('questions/viewanswers', array('entity' => $question));
        $content .= '<div id="comments">'.elgg_view_comments ($question).'</div>';

        $body = elgg_view_layout('content', array(
                'filter' => '',
                'content' => $content,
                'title' => $title,
        ));

        echo elgg_view_page($title, $body);

?>