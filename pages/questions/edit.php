<?php
gatekeeper();

$guid = (int) get_input('guid');
$entity = get_entity($guid);
//$tutorial_guid = (int) get_input('tutorial');

if ($entity->getSubtype() == 'question') {
        $question = $entity;

        $container_guid = get_entity($guid)->container_guid;
        $container = get_entity($container_guid);

        $page_owner = $container;
        if (elgg_instanceof($container, 'object')) {
                $page_owner = $container->getContainerEntity();
        }

        elgg_set_page_owner_guid($page_owner->getGUID());

        elgg_push_breadcrumb($question->title, $question->getURL());
        elgg_push_breadcrumb(elgg_echo('edit'));

        $vars['entity'] = $question;
        $form_vars = array('enctype' => 'multipart/form-data');
        $content = elgg_view_form('questions/edit', $form_vars, $vars);

        $title = elgg_echo('questions:edit');

        $body = elgg_view_layout('content', array(
                'filter' => '',
                'content' => $content,
                'title' => $title,
        ));

        echo elgg_view_page($title, $body);
}
elseif ($entity->getSubtype() == 'answer') {
        $answer = $entity;
        $question = get_entity($answer->container_guid);
        $page_owner = get_entity($question->container_guid);

        elgg_set_page_owner_guid($page_owner->getGUID());

        elgg_push_breadcrumb($question->title, $question->getURL());
        elgg_push_breadcrumb(elgg_echo('questions:title:editanswer'));

        $vars['entity'] = $answer;
        $vars['question'] = $question;
        $form_vars = array('enctype' => 'multipart/form-data');
        $content = elgg_view_form('questions/editanswer', $form_vars, $vars);

        $title = elgg_echo('questions:title:editanswer');

        $body = elgg_view_layout('content', array(
                'filter' => '',
                'content' => $content,
                'title' => $title,
        ));

        echo elgg_view_page($title, $body);
}