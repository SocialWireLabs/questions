<?php

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get the guid
$question_file_guid = get_input("question_file_guid");

// Get the file
$question_file = new QuestionsPluginFile($question_file_guid);

if ($question_file) {
   $mime = $question_file->mimetype;
   if (!$mime) {
      $mime = "application/octet-stream";
   }
   $filename = $question_file->originalfilename;
   header("Pragma: public");
   header("Content-type: $mime");
   header("Content-Disposition: attachment; filename=\"$filename\"");
   $contents = $question_file->grabFile();
   $splitString = str_split($contents, 8192);
   foreach($splitString as $chunk)
      echo $chunk;
      exit;
} else {
   register_error(elgg_echo("resourcefiles:downloadfailed"));
   forward();
}
?>