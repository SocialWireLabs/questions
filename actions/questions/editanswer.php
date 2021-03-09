<?php

	gatekeeper();

	elgg_load_library('labredes:subgroups');
	elgg_load_library('socialwire:questions');

        $answer_guid = get_input('answer_guid', false);

	$the_answer = get_input('answer');
        if ($answer_guid) {
            $answer = get_entity($answer_guid);
            $question_guid = $answer->container_guid;
        }
        else
            $question_guid = get_input('question_guid');
        $question = get_entity($question_guid);
        $urls = get_input('urls');
        $file_counter = count($_FILES['upload']['name']);
        $answer_type = $question->answer_type;
        if ($question->answer_type == 'simple_choice') {
            $chosen_answer = get_input('question_answer', null);    
            if (!$chosen_answer) {
                register_error(elgg_echo("questions:error:answer:empty_choice"));
                forward($_SERVER['HTTP_REFERER']);
            }
        }

	if ($answer_type == 'simple' && empty($the_answer)) {
            register_error(elgg_echo("questions:error:answer:empty"));
            forward($_SERVER['HTTP_REFERER']);
	}

        if ($answer_guid) {
            $answer->edited = true;

            switch ($new_answer_type) {
                case 'image':
                        $files[0]->delete();
                        break;
                case 'audio':
                        $files[0]->delete();
                        break;
            }

            //Esto va fuera ya que no implica que sea de tipo file que antes lo fuera : borramos los archivos ligados anteriormente a esta pregunta
            $files = elgg_get_entities_from_relationship(array( 'relationship' => 'answer_file_link',
                                                                'relationship_guid' => $answer->guid,
                                                                'inverse_relationship' => FALSE,
                                                                'types' => 'object',
                                                                'subtypes' => 'question_file'));

            foreach($files as $single_file) {
                $value = get_input($single_file->guid);
                if($value == '1')
                {
                    $file1 = get_entity($single_file->guid);
                    $file1->delete();
                }
            }
        }
	else {
		// Initialise a new ElggObject to be the answer
		$answer = new ElggObject();
		$answer->subtype = "answer";
                $answer->access_id = $question->access_id;
                if ($question->who_answers == 'subgroup'){
                    $group_guid = $question->container_guid;
                    $subgroups = elgg_get_entities_from_relationship(array(
                        'type_subtype_pairs' => array('group' => 'lbr_subgroup'),
                        'container_guids' => $group_guid,
                        'relationship' => 'member',
                        'inverse_relationship' => false,
                        'relationship_guid' => elgg_get_logged_in_user_guid(),
                    ));
                    if (is_array($subgroups) && lbr_subgroups_count_membership(get_entity($group_guid), elgg_get_logged_in_user_entity()) == 1) {
                        $answer->owner_guid = $subgroups[0]->getGUID();
                        $answer->who_answers = 'subgroup';
                    }
                    else {
                        system_message(elgg_echo('questions:error:answer:not_subgroup_member'));
                        $answer->delete();
                        forward($_SERVER['HTTP_REFERER']);
                    }
                }
                else {
                    $answer->owner_guid = elgg_get_logged_in_user_guid();
                    $answer->who_answers = 'member';
                }

	        $answer->question_guid = $question->guid;

		$saved = $answer->save();
                if (!$saved) {
                   register_error(elgg_echo("questions:error:answer:edit"));
                   forward($_SERVER['HTTP_REFERER']);
                }
		
                $answer->featured = false;
                $answer->container_guid = $question->guid;
                $answer->edited = false;

                $question_container = get_entity($question->container_guid);
                if ($question_container instanceof ElggGroup) {
                    if ($answer->owner_guid == $question_container->owner_guid || (check_entity_relationship($answer->owner_guid,'group_admin', $question_container->guid)))
                        $answer->teacher = true;
                    else
                        $answer->teacher = false;
                }
    }

    $answer->answer_type = $answer_type;

    switch ($answer_type){
        case 'simple':
            $answer->answer = $the_answer;
            break;
        case 'simple_choice':
            $answer->answer = $the_answer;
            $answer->chosen_answer = $chosen_answer;
            break;

        case 'file':
            $answer->urls = implode(',',$urls);
            foreach ($urls as $url){
                $xss_test = "<a rel=\"nofollow\" href=\"$url\" target=\"_blank\">$url</a>";
                if ($xss_test != filter_tags($xss_test)) {
                        register_error(elgg_echo('questions:error:url:failed'));
                        forward(REFERER);
                }
                $new_urls[] = $xss_test;
            }

            $answer->urls_prepared = implode(',',$new_urls);

            break;
    }

    $saved = $answer->save();

    if (!$saved) {
        register_error(elgg_echo("questions:error:answer:edit"));
        forward($_SERVER['HTTP_REFERER']);
    }
    elseif (!$answer_guid) {
        $question->answers_number++;
        if ($answer->who_answers != 'subgroup')
            add_entity_relationship($question->guid, 'answered_by', $answer->owner_guid);
        else {
            $group_members = get_group_members($answer->owner_guid);
            foreach($group_members as $member)
                add_entity_relationship($question->guid, 'answered_by', $member->guid);
        }
    }

    if($file_counter > 0 && $_FILES['upload']['name'][0] != '')
    {
        for($i=0; $i<$file_counter; $i++)
        {
            // create the file object
            $file_new[$i] = new QuestionsPluginFile();

            // set the subtype for a resource "file"
            $file_new[$i]->subtype = "question_file";

            $prefix = "file/";

                    $filestorename = elgg_strtolower(time().$_FILES['upload']['name'][$i]);

                    $file_new[$i]->setFilename($prefix.$filestorename);
                    $file_new[$i]->setMimeType($_FILES['upload']['type'][$i]);
                    $file_new[$i]->originalfilename = $_FILES['upload']['name'][$i];
                    $file_new[$i]->simpletype = elgg_get_file_simple_type($_FILES['upload']['type'][$i]);

                    $file_new[$i]->open("write");
                    $file_new[$i]->write(get_uploaded_question_file('upload',$i));
                    $file_new[$i]->close();

                    $file_new[$i]->title = $_FILES['upload']['name'][$i];

                    $file_new[$i]->access_id = $answer->access_id;
                    $file_guid[$i] = $file_new[$i]->save();

                    if($file_guid[$i]) {
                          add_entity_relationship($answer->guid,'answer_file_link',$file_new[$i]->getGUID());
                    }
        }
    }

    if (!$answer_guid) {
        add_entity_relationship($question->guid,'answer',$answer->guid);

        notify_question_owner($question,$answer);
    }

    // Success message
    system_message(elgg_echo("questions:alert:answer:submitted"));

    forward($question->getURL());

    function notify_question_owner($question, $answer)
    {
    	$question_owner = get_user($question->owner_guid);
    	$answer_owner = get_user($answer->owner_guid);

    $site_guid = elgg_get_config('site_guid');
	$site = get_entity($site_guid);
	$sitename = $site->name;
        $link = $question->getURL();
	
        $subject = sprintf(elgg_echo("questions:email:subject"), $answer_owner->name, $sitename);
	$message = sprintf(elgg_echo('questions:email:mailbody'), $question_owner->name, $answer_owner->name, $sitename, $question->question, $answer->answer, $link);
	
    $config_site = elgg_get_config('site');	
	notify_user($question->owner_guid,$answer->owner_guid,$subject,$message,array('action'=>'update', 'object'=>$answer));
    }
?>
