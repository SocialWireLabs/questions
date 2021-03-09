<?php
	
gatekeeper();

$guid = (int) get_input('guid');
$entity = get_entity($guid);

if ($entity->getSubtype() == 'question') {
   $question = $entity;
   $question_guid = $question->getGUID();
   $container = get_entity($question->container_guid);
   $options = array('relationship' => 'answer', 'relationship_guid' => $question_guid);
   $answers = elgg_get_entities_from_relationship($options);

   $files = elgg_get_entities_from_relationship(array( 'relationship' => 'question_file_link','relationship_guid' => $question_guid,'inverse_relationship' => FALSE,'types' => 'object','subtypes' => 'question_file'));

   foreach($files as $file) {
      if(!$file->delete()) {
         register_error(elgg_echo('questions:error:file:delete'));
         forward($question->getURL());
      }
   }

   $question->deleteRelationships();

   foreach($answers as $answer){
      if(!$answer->delete()){
         register_error(elgg_echo('questions:error:answer:delete'));
         forward($question->getURL());
      }
   }

   $game_points = gamepoints_get_entity($question_guid);
   if ($game_points)
      gamepoints_update($game_points->guid,"");

   $question->delete();

   // Success message
   system_message(elgg_echo("questions:alert:question:deleted"));

   forward(elgg_get_site_url() . 'questions/group/'.$container->username);

} elseif ($entity->getSubtype() == 'answer') {

   $answer = $entity;
   $question = get_entity($answer->container_guid);
   $question_guid = $question->getGUID();
   $question->answers_number--;   

   if ($answer->who_answers != 'subgroup')
      remove_entity_relationship($question_guid, 'answered_by', $answer->owner_guid);
   else {
      $group_members = get_group_members($answer->owner_guid);
      foreach($group_members as $member)
         remove_entity_relationship($question_guid, 'answered_by', $member->guid);
   }

   if(!$answer->delete()) {
      register_error(elgg_echo('questions:error:answer:delete'));
      forward($question->getURL());
   }

   // Success message
   system_message(elgg_echo("questions:alert:answer:deleted"));

   forward($question->getURL());

}
?>