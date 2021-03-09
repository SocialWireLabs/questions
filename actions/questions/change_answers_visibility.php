<?php

gatekeeper();

$question_guid = get_input('question_guid');
$question = get_entity($question_guid);

if ($question->getSubtype() == "question" && $question->canEdit()) {

   if ($question->hide_answers) {
      $question->hide_answers = false;
   } else {
      $question->hide_answers = true;
   }

   //Forward
   forward($_SERVER['HTTP_REFERER']);
}
		
?>
