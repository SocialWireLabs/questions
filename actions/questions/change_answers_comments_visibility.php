<?php

gatekeeper();

$question_guid = get_input('question_guid');
$question = get_entity($question_guid);

if ($question->getSubtype() == "question" && $question->canEdit()) {

   if ($question->hide_comments) {
      $question->hide_comments = false;
   } else {
      $question->hide_comments = true;
   }

   //Forward
   forward($_SERVER['HTTP_REFERER']);
}
		
?>
