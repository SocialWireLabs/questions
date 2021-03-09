<?php
gatekeeper();

$container_guid = (int) get_input('guid');
$container = get_entity($container_guid);

$page_owner = $container;
if (elgg_instanceof($container, 'object')) {
	$page_owner = $container->getContainerEntity();
}

elgg_set_page_owner_guid($page_owner->getGUID());

$title = elgg_echo('questions:title:new');

$vars['entity'] = null;
$vars['container_guid'] = $container_guid;
$form_vars = array('enctype' => 'multipart/form-data');
$content = elgg_view_form('questions/edit', $form_vars, $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
