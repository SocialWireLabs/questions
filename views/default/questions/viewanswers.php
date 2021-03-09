<?php
        elgg_load_library('socialwire:questions');

        $question = $vars['entity'];
        $owner = $question->getOwnerEntity();

        $chosen_option = get_input('chosen_option', null);
        
        $options = array('relationship' => 'answer', 'relationship_guid' => $question->guid, 'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'answer');
        $count_answers = elgg_get_entities_from_relationship(array_merge($options, array ('count' => true)));
        if (!$count_answers) $count_answers = 0;
?>

	<h3>
		<?php   echo elgg_echo('questions:viewanswers:answers');
                        if (is_question_owner($question) || is_admin_or_teacher($question->container_guid) || (!$question->hide_answers))
                            echo " (".$count_answers.")";
                ?>
	</h3>

<?php
            if (is_question_owner($question) || is_admin_or_teacher($question->container_guid)  || (!$question->hide_answers)){
                if ($count_answers > 0){
                    $options = array('relationship' => 'answer', 'relationship_guid' => $question->guid, 'inverse_relationship' => false, 'type' => 'object', 'subtype' => 'answer', 'limit' => null);
                    if ($question->question_type == 'simple_choice' && $chosen_option) {
                        $operator_answers = elgg_get_entities_from_relationship(array_merge($options,array('metadata_name_value_pairs' => array(array('name'=> 'teacher', 'value' => true), array('name'=> 'chosen_answer', 'value' => $chosen_option)), 'metadata_case_sensitive' => false)));
                        $featured_answers = elgg_get_entities_from_relationship(array_merge($options,array('metadata_name_value_pairs' => array(array('name'=> 'featured', 'value' => true), array('name'=> 'chosen_answer', 'value' => $chosen_option)), 'metadata_case_sensitive' => false)));
                        $normal_answers = elgg_get_entities_from_relationship(array_merge($options,array('metadata_name_value_pairs' => array(array('name'=>'teacher','value' => 0), array('name'=> 'featured', 'value' => 0), array('name'=> 'chosen_answer', 'value' => $chosen_option)), 'metadata_case_sensitive' => false)));
                    }
                    else {
                        $operator_answers = elgg_get_entities_from_relationship(array_merge($options,array('metadata_name_value_pairs' => array(array('name'=> 'teacher', 'value' => true)), 'metadata_case_sensitive' => false)));
                        $featured_answers = elgg_get_entities_from_relationship(array_merge($options,array('metadata_name_value_pairs' => array(array('name'=> 'featured', 'value' => true)), 'metadata_case_sensitive' => false)));
                        $normal_answers = elgg_get_entities_from_relationship(array_merge($options,array('metadata_name_value_pairs' => array(array('name'=>'teacher','value' => 0), array('name'=> 'featured', 'value' => 0)), 'metadata_case_sensitive' => false)));
                    }
                    //Quedan ordenadas. Primero las de profesores, luego las destacadas por los mismos, después el resto
                    $answers = array();
                    if (!empty ($operator_answers))
                        $answers = $operator_answers;
                    if (!empty ($featured_answers))
                        $answers = array_merge ($answers,$featured_answers);
                    if (!empty ($normal_answers))
                       $answers = array_merge ($answers,$normal_answers);

                    //si se necesita formulario, se crea y se envia como hidden el array de ids de respuestas para procesar en el action
                    if (is_admin_or_teacher($question->container_guid) && $question->status != 'pending') {
                            $action = "questions/rateanswers";
                            $options = array('count' => $count_answers, 'offset' => 0, 'limit' => null, 'full_view' => true);
                            $form .= elgg_view_entity_list($answers, $options);
                            $form .= elgg_view('input/hidden', array('name' => 'question_guid', 'value' => $question->guid));
                            $form .= elgg_view('input/submit', array('value' => elgg_echo('questions:rate:submit_all')));
                            echo elgg_view('input/form', array('action' => elgg_get_site_url() . "action/$action", 'body' => $form, 'name' => "form", 'enctype' => "multipart/form-data"));
                    }
                    else {
                        $options = array('count' => $count_answers, 'offset' => 0, 'limit' => null, 'full_view' => true);
                        echo elgg_view_entity_list($answers, $options);
                    }
                }
                else{
                    echo elgg_echo('questions:body:noanswers');
                }
            }
            else  {
                if ($question->who_answers != 'subgroup'){
                    $my_answer = elgg_get_entities_from_relationship(array(
                        'type_subtype_pairs' => array('object' => 'answer'),
                        'container_guids' => $group_guid,
                        'relationship' => 'answer',
                        'inverse_relationship' => false,
                        'relationship_guid' => $question->getGUID(),
                        'owner_guid' => elgg_get_logged_in_user_guid()
                    ));
                }
                else {
                    $subgroups = elgg_get_entities_from_relationship(array(
                        'type_subtype_pairs' => array('group' => 'lbr_subgroup'),
                        'container_guids' => $group_guid,
                        'relationship' => 'member',
                        'inverse_relationship' => false,
                        'relationship_guid' => get_loggedin_userid(),
                    ));
                    if (is_array($subgroups) && lbr_subgroups_count_membership(get_entity($group_guid), get_loggedin_user()) == 1)
                        $my_answer = elgg_get_entities_from_relationship(array(
                            'type_subtype_pairs' => array('object' => 'answer'),
                            'container_guids' => $group_guid,
                            'relationship' => 'answer',
                            'inverse_relationship' => false,
                            'relationship_guid' => $question->getGUID(),
                            'owner_guid' => $subgroups[0]->getGUID()
                        ));
                }
                echo elgg_view_entity($my_answer[0], array('full_view'=> true));
                echo elgg_echo('questions:body:hideanswers');
            }
?>