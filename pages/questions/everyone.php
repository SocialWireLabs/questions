<?php
	        $title = elgg_echo('questions:title:allquestions');

                elgg_pop_breadcrumb();
                elgg_push_breadcrumb(elgg_echo('questions'));

                elgg_register_title_button();

                $content = elgg_list_entities(array(
                        'types' => 'object',
                        'subtypes' => 'question',
                        'full_view' => false,
                ));
                if (!$content) {
                        $content = '<p>' . elgg_echo('questions:none') . '</p>';
                }

                $body = elgg_view_layout('content', array(
                        'filter_context' => 'all',
                        'content' => $content,
                        'title' => $title,
                ));

                echo elgg_view_page($title, $body);

?>