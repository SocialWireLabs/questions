<?php

$full = elgg_extract('full_view', $vars, FALSE);
$question = elgg_extract('entity', $vars, FALSE);

elgg_load_library('socialwire:questions');

if (!$question) {
	return TRUE;
}

$owner = $question->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = elgg_view('output/url', array(
	'href' => "questions/owner/$owner->username",
	'text' => $owner->name,
	'is_trusted' => true,
));
$author_text = elgg_echo('byline', array($owner_link));
$tags = elgg_view('output/tags', array('tags' => $question->tags));
//$date = elgg_view_friendly_time($question->time_created);
$date = date('Y/m/d',$question->time_created) . " " . date('G:i:s',$question->time_created);

//$metadata = elgg_view_menu('entity', array('entity' => $question,'handler' => 'questions','sort_by' => 'priority','class' => 'elgg-menu-hz'));

$subtitle = "$author_text $date $comments_link $categories";

switch(question_status($question)) {
        case 'open':
                $status = 'open';
                $action = 'close';
                break;
        case 'closed':
                $status = 'closed';
                $action = 'open';
                break;
        case 'pending':
                $status = 'closed';
                break;
        default:break;
}

//Candado (control de apertura y cierre)
$lock = '<div class="question_' . $status . '">';

if (is_admin_or_teacher($question->container_guid) && question_status($question) != 'pending')
        $lock .= elgg_view("output/url", array(
                    'href' => elgg_get_site_url() . "action/questions/" . $action . "question?guid=" . $question->guid,
                    'text' => " ",
                    'is_action' => true));
else
        $lock .='<img src="' . elgg_get_site_url() . 'mod/questions/graphics/' . $status . 'question_20.png" align="left" style="padding-top:3px;" />';

$lock .= '</div>';

$question_status = question_status($question);
$title = <<<EOT
                <div class="questions_title">
                    {$lock}
                    <a class="{$question_status}_title_questions" href="{$question->getURL()}">{$question->title}</a>
                </div>
EOT;

 //Answers visibility
 if (is_admin_or_teacher($question->container_guid))  {
      if (!$question->hide_answers){
         $word_answers_visibility = elgg_echo("questions:enable_answers_visibility");
      } else {
         $word_answers_visibility = elgg_echo("questions:disable_answers_visibility");
      }
      $url_answers_visibility = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/questions/change_answers_visibility?question_guid=" . $question->getGUID());
      $link_answers_visibility = "<a href=\"{$url_answers_visibility}\">{$word_answers_visibility}</a>";

   //Answers comments visibility

     if (!$question->hide_comments){
         $word_answers_comments_visibility = elgg_echo("questions:enable_answers_comments_visibility");
      } else {
         $word_answers_comments_visibility = elgg_echo("questions:disable_answers_comments_visibility");
      }
      $url_answers_comments_visibility = elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/questions/change_answers_comments_visibility?question_guid=" . $question->getGUID());
      $link_answers_comments_visibility = "<a href=\"{$url_answers_comments_visibility}\">{$word_answers_comments_visibility}</a>";
   }


$controls = "";

$options = array('relationship' => 'answer', 'relationship_guid' => $question->guid, 'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'answer');
$number_of_answers = elgg_get_entities_from_relationship(array_merge($options, array ('count' => true)));
if (!$number_of_answers) $number_of_answers = 0;

$my_answer = elgg_get_entities_from_relationship(array_merge($options, array ('owner_guid' => elgg_get_logged_in_user_guid())));
if ($my_answer) $answeredbyme = true;

$points = $question->creator_points;
if ($points) {
   if (strcmp($points,"1")==0)
      $text_points = sprintf(elgg_echo("questions:point"),$points);
   else
      $text_points = sprintf(elgg_echo("questions:points"),$points);
}

if ($full) {

        //Cuerpo de la pregunta
        switch ($question->question_type) {
                case 'simple':
                    $question_body .= elgg_view('output/longtext', array('value' => $question->question));
                    break;

                case 'simple_choice':
                    $question_body .= elgg_autop($question->question);
                    $question_options = unserialize($question->question_options);
                    $search_options = array('type' => 'object', 'subtype' => 'answer', 'container_guid' => $question->guid, 'count' => true);
                    $num_answers = elgg_get_entities_from_metadata($search_options);

                    foreach($question_options as $key => $val) {
                        $num_answers_this_option = elgg_get_entities_from_metadata(array_merge($search_options,
                                array ('metadata_name_value_pairs' => array('name' => 'chosen_answer','value' => $key))));
			if ((is_admin_or_teacher($question->container_guid)) || (is_question_owner($question)) || (!$question->hide_answers)) {
                        $percentage = number_format($num_answers_this_option*100/$num_answers, 2) . "%";
                        $of = elgg_echo('questions:body:simple_choice:of',array($num_answers_this_option, $num_answers));
			} else {
			   $percentage = "";
			   $of = "";
			}
                        $url = $question->getURL().'?chosen_option='.$key;
                        $question_body .= <<<EOT
                              <input name="question_answer" value="{$key}" class="input-radio" type="radio" DISABLED/>
                                 <a href={$url}>{$val}</a>    {$percentage}  {$of}

                              <br /><br />
EOT;
                    }
                    break;

                case 'file':
                    $question_body .= elgg_autop($question->question);
                    $urls = explode(',',$question->urls_prepared);
                    $urls_preview = explode(',',$question->urls);
                    for($i=0; $i<count($urls); $i++){
											if (elgg_is_active_plugin("sw_embedlycards"))
						                      {
						                        $question_body .= "<div>
						                        <a class='embedly-card' href='$urls_preview[$i]'></a>
						                        </div>";
						                     }
						                   else if (elgg_is_active_plugin("hypeScraper"))
						                     $question_body .= elgg_view('output/sw_url_preview', array('value' => $urls_preview[$i],));
						                   else
						                     $question_body .= "<p>$urls[$i]</p>";
										}
                    $files = elgg_get_entities_from_relationship(array( 'relationship' => 'question_file_link',
                                                                    'relationship_guid' => $question->guid,
                                                                    'inverse_relationship' => FALSE,
                                                                    'types' => 'object',
                                                                    'subtypes' => 'question_file'));

                    foreach($files as $file) {
								$icon = questions_set_icon_url($file, "small");
								$url_file = elgg_get_site_url()."mod/questions/download.php?question_file_guid=".$file->guid;
								$trozos = explode(".", $file->title);
								$ext = strtolower(end($trozos));
								if (($ext == 'jpg') || ($ext == 'png') || ($ext == 'gif') || ($ext == 'tif') || ($ext == 'tiff') || ($ext =='jpeg'))
									$question_body .= "<p align=\"center\"><a href=\"".$url_file."\">"."<img src=\"" . $url_file . "\" width=\"600px\">"."</a></p>";
								else
									$question_body .= "<p><a href=\"".$url_file."\">"."<img src=\"" . elgg_get_site_url(). $icon . "\">".$file->title."</a></p>";
                }
                    break;

		}
        //Info de la pregunta
        if ($container instanceof ElggGroup && ($question->directed) && ($question->status != 'pending'))
                $question_points .= "<br/>".sprintf(elgg_echo('questions:body:question_points:creator_points'),$question->creator_points);


        if ($question->closing == 'date')
            if ($question->closing_timestamp < time())
                $question_closing = sprintf(elgg_echo('questions:body:question_closing:date_past'),my_friendly_time($question->closing_timestamp));
            else
                $question_closing = sprintf(elgg_echo('questions:body:question_closing:date_future'),my_friendly_time($question->closing_timestamp));
        else
            $question_closing = elgg_echo('questions:body:question_closing:never');


        //Controles
            //Ver Respuestas
        if (is_question_owner($question) || is_admin_or_teacher($question->container_guid) || (!$question->hide_answers)){
            $controls .= "<a href=\"" . $question->getURL() . "\">" . sprintf(elgg_echo('questions:body:viewanswers_number'),$number_of_answers) ."</a>&nbsp;&nbsp;";
        }
        else {
            $controls .= "<a href=\"" . $question->getURL() . "\">" . elgg_echo('questions:body:viewanswers') ."</a>&nbsp;&nbsp;";
        }
	    // Responder (los profesores pueden responder sus propias preguntas, alumnos no)
        if ((question_status($question) == 'open' && (!(is_question_owner($question)) || is_admin_or_teacher($question->container_guid)) && !$answeredbyme)) {
            $controls .= "<a href=\"" . $question->getURL() . "\">" . elgg_echo('questions:body:answer') . "</a>&nbsp;&nbsp;";
        }

        //Comentarios
        $options = array('type' => 'object', 'subtype' => 'comment', 'container_guid' => $question->getGUID(), 'count' => true);
        $comments_number = elgg_get_entities($options);

        $controls .= "<a href=\"" . $question->getURL() ."#comments\">" . sprintf(elgg_echo('questions:body:comment'),$comments_number) . "</a>&nbsp;&nbsp;";

        //Edición, borrado y destacado
        if ((is_question_owner($question) && question_status($question) == 'pending' && $question->directed) || (is_question_owner($question) && !$question->directed) || is_admin_or_teacher($question->container_guid)) {
            if (question_status($question) == 'pending' && is_admin_or_teacher($question->container_guid))
                $controls .= "<a href=\"". elgg_get_site_url() . "questions/edit/" . $question->guid . "\">" . elgg_echo('questions:body:editquestion_pending') . "</a>&nbsp;&nbsp;";
            else
                $controls .= "<a href=\"". elgg_get_site_url() . "questions/edit/" . $question->guid . "\">" . elgg_echo('questions:body:editquestion') . "</a>&nbsp;&nbsp;";

	        if ($number_of_answers==0) {
                $controls .= elgg_view("output/url", array(
                        'href' => elgg_get_site_url() . "action/questions/delete?guid=" . $question->guid,
                        'text' => elgg_echo('questions:body:deletequestion'),
                        'confirm' => elgg_echo('questions:body:deletequestionconfirm')
                )). "&nbsp;&nbsp;";
            } else {
	            $controls .= "<a onclick=\"javascript:questions_not_delete();return true;\">".elgg_echo('questions:body:deletequestion')."</a>" . "&nbsp;&nbsp;";
	        }
	   }

        if (is_admin_or_teacher($question->container_guid)) {
            if (!$question->featured)
                $controls .= elgg_view("output/url", array(
                    'href' => elgg_get_site_url() . "action/questions/feature?guid=" . $question->guid,
                    'text' => elgg_echo('questions:body:featurequestion'),
                    'confirm' => elgg_echo('questions:body:featurequestionconfirm')));
            else
                $controls .= elgg_view("output/url", array(
                    'href' => elgg_get_site_url() . "action/questions/feature?guid=" . $question->guid,
                    'text' => elgg_echo('questions:body:nofeaturequestion'),
                    'confirm' => elgg_echo('questions:body:nofeaturequestionconfirm')));
        }


        if ($question->status == 'pending')
                $subtitle .= "<br/>".elgg_echo('questions:body:pending');
        $subtitle .= "$question_points<br/>$question_closing";

	$params = array(
		'entity' => $question,
		'title' => $title,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);

	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

        if ($question->featured) {
            $wwwroot = elgg_get_config('wwwroot');
            $body .= <<<EOT
                <img src="{$wwwroot}mod/questions/graphics/star.gif">
EOT;
        }

	if ($text_points)
	   $body .= "<br>".$text_points;

        $body .= <<<EOT
            <br />
            <div class="questions_body">
                {$question_body}
            </div>
            <div class="clearfloat"></div>
            <!-- display question controls -->
            <p class="options">
	        {$link_answers_visibility} {$link_answers_comments_visibility}
		<br>
                {$controls}
            </p>
EOT;
        // También mostramos formulario de respuesta para los profesores en sus propias preguntas (is_admin_or_teacher añadido))
        if ((!$answeredbyme && question_status($question) == 'open' && !(is_question_owner($question))) ||
                (!$answeredbyme && question_status($question) == 'open' &&is_admin_or_teacher($question->container_guid)))
            $body .= elgg_view('forms/questions/editanswer', array('question' => $question));


	echo elgg_view('object/elements/full', array(
		'summary' => $summary,
		'icon' => $owner_icon,
		'body' => $body,
	));
}
 else {



    //Controles
            //Ver Respuestas
        if (is_question_owner($question) || is_admin_or_teacher($question->container_guid) || (!$question->hide_answers)){
            $controls .= "<a href=\"" . $question->getURL() . "\">" . sprintf(elgg_echo('questions:body:viewanswers_number'),$number_of_answers) ."</a>&nbsp;&nbsp;";
        }
        else {
            $controls .= "<a href=\"" . $question->getURL() . "\">" . elgg_echo('questions:body:viewanswers') ."</a>&nbsp;&nbsp;";
        }
	   //Responder (los profesores pueden responder sus propias preguntas, alumnos no)
        if (question_status($question) == 'open' && (!(is_question_owner($question)) || is_admin_or_teacher($question->container_guid)) && !$answeredbyme) {
            $controls .= "<a href=\"" . $question->getURL() . "\">" . elgg_echo('questions:body:answer') . "</a>&nbsp;&nbsp;";
        }

        //Comentarios
        $options = array('type' => 'object', 'subtype' => 'comment', 'container_guid' => $question->getGUID(), 'count' => true);
        $comments_number = elgg_get_entities($options);

        $controls .= "<a href=\"" . $question->getURL() ."#comments\">" . sprintf(elgg_echo('questions:body:comment'),$comments_number) . "</a>&nbsp;&nbsp;";

            //Edición, borrado y destacado
        if ((is_question_owner($question) && question_status($question) == 'pending' && $question->directed) || (is_question_owner($question) && !$question->directed) || is_admin_or_teacher($question->container_guid)) {
            if (question_status($question) == 'pending' && is_admin_or_teacher($question->container_guid)) {
                $controls .= "<a href=\"". elgg_get_site_url() . "questions/edit/" . $question->guid . "\">" . elgg_echo('questions:body:editquestion_pending') . "</a>&nbsp;&nbsp;";
            } else {
                $controls .= "<a href=\"". elgg_get_site_url() . "questions/edit/" . $question->guid . "\">" . elgg_echo('questions:body:editquestion') . "</a>&nbsp;&nbsp;";
	    }
	    if ($number_of_answers==0) {
               $controls .= elgg_view("output/url", array('href' => elgg_get_site_url() . "action/questions/delete?guid=" . $question->guid, 'text' => elgg_echo('questions:body:deletequestion'),'confirm' => elgg_echo('questions:body:deletequestionconfirm'))). "&nbsp;&nbsp;";
           } else {
	       $controls .= "<a onclick=\"javascript:questions_not_delete();return true;\">".elgg_echo('questions:body:deletequestion')."</a>" . "&nbsp;&nbsp;";

	   }
        }

        if (is_admin_or_teacher($question->container_guid)) {
            if (!$question->featured)
                $controls .= elgg_view("output/url", array(
                    'href' => elgg_get_site_url() . "action/questions/feature?guid=" . $question->guid,
                    'text' => elgg_echo('questions:body:featurequestion'),
                    'confirm' => elgg_echo('questions:body:featurequestionconfirm')));
            else
                $controls .= elgg_view("output/url", array(
                    'href' => elgg_get_site_url() . "action/questions/feature?guid=" . $question->guid,
                    'text' => elgg_echo('questions:body:nofeaturequestion'),
                    'confirm' => elgg_echo('questions:body:nofeaturequestionconfirm')));
        }



	// brief view
	$params = array(
		'entity' => $question,
            'title' => $title,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

        if ($question->featured) {
            $wwwroot = elgg_get_config('wwwroot');
            $list_body .= <<<EOT
                <img src="{$wwwroot}mod/questions/graphics/star.gif">
EOT;
        }

	if ($text_points)
           $list_body .= $text_points;

	if (is_admin_or_teacher($question->container_guid))  {
	    $list_body .= "<br>" . $link_answers_visibility . " " . $link_answers_comments_visibility;
	}

        $list_body .= <<<EOT
            <br />
            <div class="questions_body">
                {$question_body}
            </div>
            <div class="clearfloat"></div>
            <!-- display question controls -->
            <p class="options">
                {$controls}
            </p>
EOT;

	echo elgg_view_image_block($owner_icon, $list_body);
}

?>

<script type="text/javascript">

function questions_not_delete(){
   alert ("<?php echo elgg_echo('questions:body:notdeletequestion');?>");
}

</script>
