<?php
$owner = $vars['owner'];

$questions_string = elgg_echo('questions:submenu:group');
$mine_questions_string = elgg_echo('questions:submenu:mine');
$open_questions_string = elgg_echo('questions:submenu:open');
$answered_questions_string = elgg_echo('questions:submenu:answered');
$not_answered_questions_string = elgg_echo('questions:submenu:not_answered');
$pending_questions_string = elgg_echo('questions:submenu:pending');
// If I am an admin or a teacher, I have the possibility to lock and unlock the possibility of making questions
if (is_admin_or_teacher($owner->guid, elgg_get_logged_in_user_guid()) && $owner->q_locked == true){
	$lock_questions_string = elgg_echo('questions:submenu:enable');
	$lock_questions_output_text = "open";
}
if (is_admin_or_teacher($owner->guid, elgg_get_logged_in_user_guid()) && $owner->q_locked == false){
	$lock_questions_string = elgg_echo('questions:submenu:disable');
	$lock_questions_output_text = "close";
}
$wwwroot = elgg_get_config('wwwroot');

$links .= <<<EOT
<div class="elgg-module elgg-owner-block">
<div class="elgg-head">
    <div class="elgg-image-block clearfix">
        <div class="elgg-body">
    <h3><a href="{$wwwroot}questions/group/{$owner->guid}/all">$questions_string</a></h3></div></div>
</div>
<div class="elgg-body">
    <ul class="elgg-menu elgg-menu-owner-block elgg-menu-owner-block-default">
        <li><a href="{$wwwroot}questions/group/{$owner->guid}/all&filter_by=mine">$mine_questions_string</a></li>
        <li><a href="{$wwwroot}questions/group/{$owner->guid}/all&filter_by=open">$open_questions_string</a></li>
        <li><a href="{$wwwroot}questions/group/{$owner->guid}/all&filter_by=answered">$answered_questions_string</a></li>
        <li><a href="{$wwwroot}questions/group/{$owner->guid}/all&filter_by=not_answered">$not_answered_questions_string</a></li>
        <li><a href="{$wwwroot}questions/group/{$owner->guid}/all&filter_by=pending">$pending_questions_string</a></li>
       	<li><a href="{$wwwroot}questions/group/{$owner->guid}/all&q_locked={$lock_questions_output_text}">$lock_questions_string</a></li>
    </ul>
</div>
</div>
EOT;

echo $links;