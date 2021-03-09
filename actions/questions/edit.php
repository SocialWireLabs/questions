<?php

	// Make sure we're logged in (send us to the front page if not)
		gatekeeper();

	// Get input data
                $question_guid = (int) get_input('question_guid', false);


		$closing = get_input ('closing','never');
                $now = time();

                if ($closing != 'never') {
                    $closing_time_h = get_input ('closing_time_h');
                    $closing_time_m = get_input ('closing_time_m');
                    $closing_time = $closing_time_h * 60 + $closing_time_m;
                    $closing_date = get_input ('closing_date');
		    $closing_date_text = date("Y-m-d",$closing_date);
	            $closing_date = strtotime($closing_date_text." ".date_default_timezone_get());
		    $closing_date_array = explode('-',$closing_date_text);
                    $closing_date_y = trim($closing_date_array[0]);
                    $closing_date_m = trim($closing_date_array[1]);
                    $closing_date_d = trim($closing_date_array[2]);
                    $closing_timestamp = mktime($closing_time_h,$closing_time_m,0,$closing_date_m,$closing_date_d,$closing_date_y);
                }
	

                $title = get_input('title');
                $the_question = get_input('question');
                $tags = get_input('tags');
                $access_id = get_input('access_id');
                $container_guid = (int) get_input('container_guid');
                $points = get_input('points');
                $pending = get_input ('pending');
                $question_type = get_input('question_type');
                $urls = get_input("urls");
                $file_counter = count($_FILES['upload']['name']);
                $hide_answers = get_input('hide_answers');
                $hide_comments = get_input('hide_comments');
                $answer_type = get_input('answer_type');
                $creator_points = get_input('creator_points');
                $notify_members = get_input('notify_members');
                $who_answers = get_input('who_answers');
                $tags_array = string_to_tag_array($tags);

                if ($question_type == 'simple_choice') {
                    $question_fields = get_input('question_options');

                    foreach ($question_fields as $key => $value){
                        if($value != '') {
                            $question_options[$key] = $value;
                        }
                    }
                }


                // Make sure the title / description aren't blank
		if (empty($title)) {
                    register_error(elgg_echo("questions:error:title:empty"));
                    forward($_SERVER['HTTP_REFERER']);
		}
                elseif (!$pending && $closing != 'never' && $closing_timestamp < $now) {
                    register_error(elgg_echo("questions:error:closing_date"));
                    forward($_SERVER['HTTP_REFERER']);
                }

                if ($question_guid) {
                    $question = get_entity($question_guid);
                    if ($pending) {
                        //dirigida y editada por alumno
                        $question->status = 'pending';
                    }
		    else {
                        //dirigida y editada por profesor. abrir pregunta y dar puntos al alumno
                        gamepoints_add($question->owner_guid, $creator_points, $question->guid, $question->container_guid);
                        $question->status = 'open';
                    }

                    //Esto va fuera ya que no implica que sea de tipo file que antes lo fuera : borramos los archivos ligados anteriormente a esta pregunta
                    $files = elgg_get_entities_from_relationship(array( 'relationship' => 'question_file_link',
                                                                        'relationship_guid' => $question->guid,
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

                    switch ($question_type) {
                            case 'image':
                                if ($files)
                                    $files[0]->delete();
                                    break;
                            case 'audio':
                                if ($files)
                                    $files[0]->delete();
                                    break;
                    }
                }
                else {
                    // Initialise a new ElggObject to be the question
                    $question = new ElggObject();
                    $question->subtype = "question";
                    $question->owner_guid = elgg_get_logged_in_user_guid();
                    $question->container_guid = $container_guid;
                    $question->answers_number = 0;
                    $question->featured = false;

                    if ($pending) {
                        $question->status = 'pending';
                        $question->directed = true;
                    }
                    else
                        $question->status = 'open';
                }

                $question->title = $title;
                $question->access_id = $access_id;
                $question->question_type = $question_type;
                $question->tags = $tags_array;
                $question->points = $points;
                $question->creator_points = $creator_points;

                if ($hide_answers == 'on')
                    $question->hide_answers = true;
                else
                    $question->hide_answers = false;
                if ($hide_comments == 'on')
                    $question->hide_comments = true;
                else
                    $question->hide_comments = false;

                $question->answer_type = $answer_type;
                $question->who_answers = $who_answers;
                $question->closing = $closing;

                if ($closing != 'never') {
                    $question->closing_time = $closing_time;
                    $question->closing_date = $closing_date;
                    $question->closing_timestamp = $closing_timestamp;
                }
                else {
                    $question->closing_time = null;
                    $question->closing_date = null;
                    $question->closing_timestamp = null;
                }

                switch ($question_type){
                    case 'simple':
                        $question->question = $the_question;
                        break;
                    case 'simple_choice':
                        $question->answer_type = "simple_choice";
                        $question->question = $the_question;
                        $question->question_options = serialize($question_options);
                        break;
                    case 'file':
                        $question->question = $the_question;
                        $question->urls = implode(',',$urls);
                        foreach ($urls as $url){
                            $xss_test = "<a rel=\"nofollow\" href=\"$url\" target=\"_blank\">$url</a>";
                            if ($xss_test != filter_tags($xss_test)) {
                                    register_error(elgg_echo('questions:error:url:failed'));
                                    forward(REFERER);
                            }
                            $new_urls[] = $xss_test;
                        }

                        $question->urls_prepared = implode(',',$new_urls);
                        break;
                    case 'audio':
                        $question->question = $the_question;
                    case 'image':
                        $question->question = $the_question;
                }

                if (!$question->save()) {
                    register_error(elgg_echo("questions:error:question:save"));
                    forward($_SERVER['HTTP_REFERER']);
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
                        //$file_new[$i]->simpletype = get_general_file_type($_FILES['upload']['type'][$i]);
                        $file_new[$i]->simpletype = elgg_get_file_simple_type($_FILES['upload']['type'][$i]);

                        $file_new[$i]->open("write");

                        if (isset($_FILES['upload']) && isset($_FILES['upload']['error'][$i])) {
                            $uploaded_file = file_get_contents($_FILES['upload']['tmp_name'][$i]);
                        }
                        else
                            $uploaded_file = false;

                        $file_new[$i]->write($uploaded_file);
                        $file_new[$i]->close();

                        $file_new[$i]->title = $_FILES['upload']['name'][$i];

                        $file_new[$i]->access_id = $question->access_id;
                        $file_guid[$i] = $file_new[$i]->save();

                        if($file_guid[$i]) {
                              add_entity_relationship($question->guid,'question_file_link',$file_new[$i]->guid);
                        }
                    }
                }

                // Success message
                system_message(elgg_echo("questions:alert:question:submitted"));

                $container = get_entity($question->container_guid);
                if ($question_guid) {
                    if ($notify_members == 'on' && $container_guid != null && $question->access_id != 0 && $question->owner_guid != elgg_get_logged_in_user_guid()){
                        notify_group_members_approve($question,$container_guid);
                    }
                    
                    if ($question->access_id != 0) {
                    	elgg_create_river_item(array(
							'view'=>'river/object/question/update',
							'action_type'=>'update',
							'subject_guid'=>elgg_get_logged_in_user_guid(),
							'object_guid'=>$question->getGUID(),
							'access_id'=>$question->access_id
						));
					}
                }
                else {
                    elgg_create_river_item(array(
                            'view'=>'river/object/question/create',
                            'action_type'=>'create',
                            'subject_guid'=>$question->owner_guid,
                            'object_guid'=>$question->getGUID()
                    ));

                    if ($container instanceof ElggGroup && $question->access_id != 0){
                        if ($question->directed){
                            notify_group_owners($question,$container_guid);
                        }
                        else if ($notify_members == 'on'){
                            $options = array('type_subtype_pairs' => array('group' => 'lbr_subgroup'), 'metadata_case_sensitive' => false,
                                'metadata_name_value_pairs' => array('name' => 'group_acl', 'value' => $access_id));
                            $subgroups = elgg_get_entities_from_metadata($options);
                            if (is_array($subgroups) && $subgroups[0] != null){
                                notify_group_members($question,$subgroups[0]->getGUID());
                            }
                            else
                                notify_group_members($question,$container_guid);
                        }
                    }
                }
				$vars_url = elgg_get_site_url();
                forward($vars_url . 'questions/view/'.$question->getGUID());


                function notify_group_members_approve($question, $group_guid) {

                    $site_guid = elgg_get_config('site_guid');
                    $site = get_entity($site_guid);
                    $sitename = $site->name;
                    $link = $question->getURL();
                    $group = get_entity($group_guid);
                    $group_name = $group->name;
                    $group_members = $group->getMembers(array('limit'=>1000));
                    $teacher = elgg_get_logged_in_user_entity();

                    $subject = sprintf(elgg_echo("questions:group_approve:email:subject"), $teacher->name, $sitename, $group_name);

                    foreach ($group_members as $member){
                        if ($member->getGUID() != $question->owner_guid) {
                               $message = sprintf(elgg_echo('questions:group_approve:email:mailbody'), $member->name, $teacher->name, $sitename, $group_name, $question->question, $link);
                               notify_user($member->getGUID(),elgg_get_logged_in_user_guid(),$subject,$message, array('action' => 'update','object' => $question));
                        }
                    }
                }

                function notify_group_owners($question, $group_guid) {
                    $question_owner = get_user($question->owner_guid);

                    $site_guid = elgg_get_config('site_guid');
                    $site = get_entity($site_guid);
                    $sitename = $site->name;
                    $link = $question->getURL();
                    $group = get_entity($group_guid);
                    $group_name = $group->name;

                    if (elgg_is_active_plugin('group_tools')){
                        //$operators = get_entities_from_relationship('group_admin',$group_guid,true,'user','',0,'',10);
                        $operators = elgg_get_entities_from_relationship(array(
                            'relationship'=> 'group_admin',
                            'relationship_guid'=> $group_guid,
                            'inverse_relationship'=> true,
                            'type'=> 'user',
                            'subtype'=>'',
                            'owner_guid'=> 0,
                            'order_by'=> '',
                            'limit'=>10
                        ));

                        $subject = sprintf(elgg_echo("questions:group:email:subject:pending"), $question_owner->name, $sitename,$group_name);

                        foreach ($operators as $operator){
                            $message = sprintf(elgg_echo('questions:group:email:mailbody:pending'), $operator->name, $question_owner->name, $sitename, $group_name, $question->question, $link);
                            notify_user($operator->getGUID(),$question->owner_guid,$subject,$message, array('action'=> 'create', 'object'=>$question));
                        }
                    } else {
                        $group_owner = $group->owner_guid;

                        $subject = sprintf(elgg_echo("questions:group:email:subject:pending"), $question_owner->name, $sitename,$group_name);
                        $message = sprintf(elgg_echo('questions:group:email:mailbody:pending'), get_entity($group_owner)->name, $question_owner->name, $sitename, $group_name, $question->question, $link);

                        notify_user($group_owner,$question->owner_guid,$subject,$message,array('action'=> 'create', 'object'=>$question));
                    }
                }

                function notify_group_members($question, $group_guid) {
                    $question_owner = get_user($question->owner_guid);

                    $site_guid = elgg_get_config('site_guid');
                    $site = get_entity($site_guid);
                    $sitename = $site->name;
                    $link = $question->getURL();
                    $group = get_entity($group_guid);
                    $group_name = $group->name;
                    $group_members = $group->getMembers(array('limit'=>1000));

                    $subject = sprintf(elgg_echo("questions:group:email:subject"), $question_owner->name, $sitename, $group_name);

                    foreach ($group_members as $member){
                        if ($member->getGUID() != $question->owner_guid) {
                               $message = sprintf(elgg_echo('questions:group:email:mailbody'), $member->name, $question_owner->name, $sitename, $group_name, $question->question, $link);
                               notify_user($member->getGUID(),$question->owner_guid,$subject,$message,array('action'=> 'create', 'object'=>$question));
                        }
                    }

                }


?>
