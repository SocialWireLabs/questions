<?php

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('questions/all');
}

elgg_push_breadcrumb($owner->name, "questions/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

elgg_register_title_button();

$title = elgg_echo('questions:title:friends');

$content = elgg_get_entities_from_relationship(array(
	'relationship_guid' => $owner->guid, 
	'type' => 'object',
	'subtype'=> 'question', 
	'limit'=> 10,
	'pagination' => true,
    'relationship' => 'friend',
    'relationship_join_on' => 'container_guid',
	'full_view'=> false
));
if (!$content) {
	$content = elgg_echo('questions:none');
}

$params = array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
