<?php
	elgg_load_library('socialwire:questions');

	$answer = $vars['entity'];
	$question = get_entity($answer->container_guid);
	$owner = $answer->getOwnerEntity();

        //Cuerpo de la respuesta
        $answer_body ="";
        switch ($answer->answer_type) {
                case 'simple':
                    $answer_body .= elgg_view('output/longtext', array('value' => $answer->answer));
                    break;

                case 'simple_choice':
                    $question_options = unserialize($question->question_options);
                    foreach($question_options as $key => $val) {
                        $checked = ($answer->chosen_answer == $key) ? 'checked' : '';
                        $answer_body .= <<<EOT
                            <p>
                              <input name="question_answer_$answer->guid" value="$key" class="input-radio" type="radio" $checked DISABLED/>
                              $val
                            </p>
EOT;
                    }
                    $answer_body .= elgg_autop($answer->answer);
                    break;

                case 'file':
                    $urls = explode(',',$answer->urls_prepared);
                    $urls_preview = explode(',',$answer->urls);
                    for($i=0; $i<count($urls); $i++){
											if (elgg_is_active_plugin("sw_embedlycards"))
						                      {
						                        $answer_body .= "<div>
						                        <a class='embedly-card' href='$urls_preview[$i]'></a>
						                        </div>";
						                     }
						                   else if (elgg_is_active_plugin("hypeScraper"))
						                     $answer_body .= elgg_view('output/sw_url_preview', array('value' => $urls_preview[$i],));
						                   else
						                     $answer_body .= "<p>$urls[$i]</p>";
                    }

                    $files = elgg_get_entities_from_relationship(array( 'relationship' => 'answer_file_link',
                                                                    'relationship_guid' => $answer->guid,
                                                                    'inverse_relationship' => FALSE,
                                                                    'types' => 'object',
                                                                    'subtypes' => 'question_file'));

                    foreach($files as $file) {
								$icon = questions_set_icon_url($file, "small");
								$url_file = elgg_get_site_url()."mod/questions/download.php?question_file_guid=".$file->guid;
								$trozos = explode(".", $file->title);
								$ext = strtolower(end($trozos));
								if (($ext == 'jpg') || ($ext == 'png') || ($ext == 'gif') || ($ext == 'tif') || ($ext == 'tiff') || ($ext =='jpeg'))
									$answer_body .= "<p align=\"center\"><a href=\"".$url_file."\">"."<img src=\"" . $url_file . "\" width=\"600px\">"."</a></p>";
								else
									$answer_body .= "<p><a href=\"".$url_file."\">"."<img src=\"" . elgg_get_site_url(). $icon . "\">".$file->title."</a></p>";
								}
                    break;

        }

        //Valoración de la pregunta
        $rating = "";

        if (!$answer->teacher) { //los profes no juegan a esto
            $actual_rate = gamepoints_get_entity_points($answer->guid);
            //si soy profe veo formulario para dar puntos
            if (is_admin_or_teacher($question->container_guid)) {
                $rating = "<br /><b>" . elgg_echo('questions:body:rate:points') . "</b>&nbsp;";
                /*$rating_form_body .= elgg_view('input/hidden', array('name' => 'answer_guid', 'value' => $answer->guid));
                $rating_form_body .= elgg_view('input/text', array('name' => 'points'.$answer->getGUID(), 'value' => $actual_rate, 'class' => '')).'&nbsp;&nbsp;';
                $rating_form_body .= elgg_view('input/submit', array('value' => elgg_echo('questions:rate:submit_one')));
                $rating .= elgg_view('input/form', array('body' => $rating_form_body, 'action' => "{elgg_get_site_url()}action/rateanswer", 'enctype' => "multipart/form-data"));*/
                $rating .= elgg_view('input/text', array('name' => 'points'.$answer->getGUID(), 'value' => $actual_rate, 'class' => '')).'&nbsp;&nbsp;';
                $rating .= "<a  onclick=\"javascript:saveDraft(".$answer->getGUID().");return true;\">".elgg_echo('questions:rate:submit_one')."</a>";
            }
            //si no veo los puntos
            else {
                if (($actual_rate)||(is_numeric($actual_rate)))
                    $rating .=  "<br /><b>" .sprintf( elgg_echo('questions:body:rate:rated'),$actual_rate) . "</b>";
                else
                    $rating.= "<br /><b>" . elgg_echo('questions:body:rate:norated') . "</b><br/>";
            }
        }


        //Comentarios
        $comments = "";

	//Comentarios

	$annotations = $answer->getAnnotations(array('guid'=>"comment"));
	if ($annotations){
		foreach ($annotations as $annotation){
                        $comments .= "<div class=\"comment\" style=\"background-color: #C6C5C5; text-align: justify;  border: 1px black solid; margin: 5px 20px 5px 5px; padding:5px 5px 5px 5px\">";
			$comments .= $annotation->value;
			$comments .= " ".friendly_time($annotation->time_created).")";
			if (get_loggedin_userid() == $annotation->owner_guid || is_admin_or_teacher($question->container_guid)) {
				$comments .= "  ".elgg_view("output/url",
					array('href' => elgg_get_site_url() . "action/questions/deletecomment?id=".$annotation->id,
					'text' => elgg_echo('questions:body:deletecomment'),
					'confirm' => elgg_echo('questions:body:deletecommentconfirm')));
			}
			$comments .= "<br /></div>";
		}
	}

        //Controles
        if (!$question->hide_comments || is_answer_owner($answer) || is_question_owner($question) || is_admin_or_teacher($question->container_guid)){
	   $answer_num_comments = $answer->countComments();
	   $name_div = 'comments'.$answer->getGUID();
           $controls = "<a onClick = showComments($name_div);>" . elgg_echo('questions:body:commentanswer') . "(".$answer_num_comments.")</a>&nbsp;&nbsp;";
        }
        if (is_answer_owner($answer) && (is_admin_or_teacher($question->container_guid) || (question_status($question) == 'open')) && empty($actual_rate) && !(is_numeric($actual_rate))) {

                $controls .= "<a href=\"". elgg_get_site_url() . "questions/edit/" . $answer->guid . "\">" . elgg_echo('questions:body:editanswer') . "</a>&nbsp;&nbsp;";
        }
        if ((is_answer_owner($answer) && (is_admin_or_teacher($question->container_guid) || (question_status($question) == 'open')) && empty($actual_rate) && !(is_numeric($actual_rate))) || (is_admin_or_teacher($question->container_guid) && empty($actual_rate) && !(is_numeric($actual_rate)))) {
                $controls .= elgg_view("output/url", array(
                    'href' => elgg_get_site_url() . "action/questions/delete?guid=" . $answer->guid,
                    'text' => elgg_echo('questions:body:deleteanswer'),
                    'confirm' => elgg_echo('questions:body:deleteanswerconfirm'))). "&nbsp;&nbsp;";
        }
        if (is_admin_or_teacher($question->container_guid) && !$answer->teacher) {
                if (!$answer->featured)
                    $controls .= elgg_view("output/url", array(
                        'href' => elgg_get_site_url() . "action/questions/feature?guid=" . $answer->guid,
                        'text' => elgg_echo('questions:body:featureanswer'),
                        'confirm' => elgg_echo('questions:body:featureanswerconfirm')));
                else
                    $controls .= elgg_view("output/url", array(
                        'href' => elgg_get_site_url() . "action/questions/feature?guid=" . $answer->guid,
                        'text' => elgg_echo('questions:body:nofeatureanswer'),
                        'confirm' => elgg_echo('questions:body:nofeatureanswerconfirm')));
	}
?>

<div class="questions">
    <div class="answer_questions">
        <!-- display the user icon -->
        <div class="questions_icon">
            <?php
            echo elgg_view_entity_icon($owner, 'tiny');
            ?>
        </div>
        <div class="questions_body">
                <?php
                        echo $answer_body;
                 ?>
        </div>
        <?php if ($answer->featured) { ?><img src="<?php echo elgg_get_config('wwwroot')?>mod/questions/graphics/star.gif"><?php } ?>
        <?php if ($answer->teacher) { ?><img src="<?php echo elgg_get_config('wwwroot')?>mod/questions/graphics/teacher.gif"><?php } ?>
        <p class="strapline">
                <?php
                        $ownertxt = "<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>";
                        echo sprintf(elgg_echo("questions:body:submittedby_not_friendly"), date('Y/m/d',$answer->time_created), date('G:i:s',$answer->time_created), $ownertxt);
                        if ($answer->edited) echo "<br />".sprintf(elgg_echo("questions:body:editedby"), date('Y/m/d',$answer->time_updated), date('G:i:s',$answer->time_udpated));
                ?>
        </p>
        <!-- display tags -->
        <p class="tags">
                <?php	echo elgg_view('output/tags', array('tags' => $answer->tags));	?>
        </p>
        <div class="clearfloat"></div>

		<div class="questions_rate">
                    <p><?php echo $rating; ?></p>
                </div>
        <div class="clearfloat"></div>
        <!-- display edit options if it is the answer owner -->
        <p class="options">
        <?php echo $controls; ?>
        </p>
	<?php
	   $name_div='comments'.$answer->getGUID();
	   echo "<div id=" . "\"$name_div\"" . " style=\"display:none\">";
           echo elgg_view_comments($answer);
	   echo "</div>";
	?>
    </div>
</div>
<script type="application/javascript">
	function saveDraft(guid) {
		var answer_guid = guid;

		var drafturl = "<?php echo elgg_get_site_url(); ?>mod/questions/savedraft.php";
		var temppoints = $("input[name='points"+answer_guid+"']").val();

		var postdata = { guid: answer_guid, points: temppoints };
                $.post(drafturl, postdata);
                alert("<?php echo elgg_echo('questions:alert:points'); ?>");
	}

        function showComments(guid) {
                if (document.getElementById(guid).style.display == 'none')
                    document.getElementById(guid).style.display = 'block';
                else
                    document.getElementById(guid).style.display = 'none';
	}
</script>
