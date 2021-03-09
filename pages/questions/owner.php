<?php
elgg_load_library('socialwire:questions');

$owner = elgg_get_page_owner_entity();
if (!$owner) {
   forward('questions/all');
}

// access check for closed groups
group_gatekeeper();

$title = elgg_echo('questions:title:user', array($owner->name));

elgg_push_breadcrumb($owner->name);

if(is_admin_or_teacher($owner->guid, elgg_get_logged_in_user_guid())){
    // Show configure_credits button
    elgg_register_title_button('questions','configure_credits');
    // Check if action: possibility of making questions (dis)enabled for pupils
    $q_locked = get_input('q_locked');
    // If teacher wants to disable the possibility for students to make questions => group questions will be locked
    if ($q_locked == 'close'){
        $owner->q_locked = true;
        system_message(elgg_echo('questions:disable_questions:success'));
    }
    // If teacher wants to enable the possibility for students to make questions => group questions will be unlocked
    elseif ($q_locked == 'open'){
        $owner->q_locked = false;
        system_message(elgg_echo('questions:enable_questions:success'));
    }
    if ($q_locked) forward(REFERRER);
}



// I can see the button of adding questions if I am a teacher, and also if I am a student and questions are unlocked. 
if(is_admin_or_teacher($owner->guid, elgg_get_logged_in_user_guid())
        || ($owner->q_locked == false)){
    // Si somos alumnos y está activado el límite de preguntas para contestar (así como la posibilidad de hacer preguntas), visualizamos límite
    if(!is_admin_or_teacher($owner->guid, elgg_get_logged_in_user_guid()) && $owner->q_locked == false){
        // Buscamos la entidad de configuración
        $options = array('types' => 'object','subtypes' => 'questions_configure_credits','container_guid' => $owner->guid);
        $questions_configure_credits = elgg_get_entities_from_metadata($options);
        if ($questions_configure_credits && $questions_configure_credits[0]->enable_credits){
            $options = array('type' => 'object', 'subtype' => 'question', 'container_guid' => elgg_get_page_owner_guid(), 
                'limit' => 0, 'offset' => 0, 'count'=> true);
            $num_preguntas_mias = elgg_get_entities_from_metadata(array_merge($options,array('owner_guid' => elgg_get_logged_in_user_guid())));
            $num_respuestas_mias = elgg_get_entities_from_relationship(array_merge($options,array('relationship' => 'answered_by' , 'relationship_guid' => elgg_get_logged_in_user_guid(), 'inverse_relationship' => true)));
            $credito_inicial = $questions_configure_credits[0]->num_credits;
            $credito_disponible = $questions_configure_credits[0]->num_credits - 
                $num_preguntas_mias + $num_respuestas_mias / $questions_configure_credits[0]->credits_add;
            // Visualizo estadísticas
            $credito_titulo_texto = elgg_echo('questions:credits_title');
            $credito_inicial_texto = sprintf(elgg_echo('questions:credits_initial'), $credito_inicial);
            $credito_disponible_texto = sprintf(elgg_echo('questions:credits_available'), $credito_disponible);
            $num_preguntas_mias_texto = sprintf(elgg_echo('questions:questions_made'), $num_preguntas_mias);
            $num_respuestas_mias_texto = sprintf(elgg_echo('questions:questions_answered'), $num_respuestas_mias);
            $content .= <<<EOT
                <div class="elgg-image-block clearfix">
                    <div class="elgg-body">
                    <h3>{$credito_titulo_texto}</h3></div></div>
                <div class="elgg-body">
                <ul class="elgg-menu elgg-menu-owner-block elgg-menu-owner-block-default">
                    <li>{$credito_inicial_texto}&emsp;&emsp;&emsp;&emsp;&emsp;{$num_preguntas_mias_texto}</li>
                    <li>{$credito_disponible_texto}&emsp;&emsp;&emsp;{$num_respuestas_mias_texto}</li>
                </ul>
                </div>
EOT;
            if ($questions_configure_credits[0]->credits_add == 1){
                $more_credits_one = elgg_echo('questions:more_credits_one');
                $content .= <<<EOT
                    <b>{$more_credits_one}</b>
EOT;
            } else{
                $more_credits = sprintf(elgg_echo('questions:more_credits'), $questions_configure_credits[0]->credits_add);
                $content .= <<<EOT
                    <b>{$more_credits}</b>
EOT;
            }
            $content .= <<<EOT
                <br>
EOT;
            // Si no hay crédito, no se muestra el botón de añadir preguntas
            if ($credito_disponible >= 1)
                elgg_register_title_button();
            else{
                $preguntas_necesarias = $questions_configure_credits[0]->credits_add * (1 - $credito_disponible);
                $no_credito = elgg_echo('questions:no_credit');
                if ($preguntas_necesarias == 1){
                    $need_answer = elgg_echo('questions:need_answer');
                    $content.= <<<EOT
                    <br>
                        <div class = "questions_warnings">
                            {$no_credito}
                            <br>
                            {$need_answer}
                        </div>
EOT;
                }
                else{
                    $need_answers = sprintf(elgg_echo('questions:need_answers'), $preguntas_necesarias);
                    $content.= <<<EOT
                    <br>
                        <div class = "questions_warnings">
                            {$no_credito}
                            <br>
                            {$need_answers}
                        </div>
EOT;
                }
            }//end else
        }//end if
        else elgg_register_title_button();
    }
    else elgg_register_title_button();
}
elseif (!is_admin_or_teacher($owner->guid, elgg_get_logged_in_user_guid())){
    //Inform that questions are disable in the title
    $information = elgg_echo('questions:disable_questions:information');
    $content.= <<<EOT
<br>
<div class = "questions_warnings">
{$information}</div>
<br>
EOT;
}

$wwwroot = elgg_get_config('wwwroot');
$content .= order_question_links($wwwroot);


$filter_by = get_input('filter_by');
$order_by = get_input('order_by','time_created');
$criteria = get_input('criteria','desc');
$offset = get_input('offset',0);
if ($order_by == 'time_created')
    $order = 'e.'.$order_by.' '.$criteria;
elseif ($order_by == 'num_of_answers')
    $order_by_metadata = array('name' => 'answers_number', 'direction' => $criteria);

$options = array('type' => 'object', 'subtype' => 'question', 'container_guid' => elgg_get_page_owner_guid(), 'limit' => 10, 'offset' => $offset,
    'full_view' => FALSE, 'list_type_toggle' => FALSE, 'order_by' => $order, 'order_by_metadata' => $order_by_metadata);

if ($filter_by == 'pending') {
    $content .= elgg_list_entities_from_metadata(array_merge($options,array('metadata_name_value_pairs' => array(array('name'=> 'status', 'value' => 'pending')), 'metadata_case_sensitive' => false)));
}
elseif ($filter_by == 'featured') {
    $content .= elgg_list_entities_from_metadata(array_merge($options,array('metadata_name_value_pairs' => array(array('name'=> 'featured', 'value' => true)), 'metadata_case_sensitive' => false)));
}
elseif ($filter_by == 'mine') {
    $content .= elgg_list_entities_from_metadata(array_merge($options,array('owner_guid' => elgg_get_logged_in_user_guid())));
}
elseif ($filter_by == 'open') {
    $content .= elgg_list_entities_from_metadata(array_merge($options,array('metadata_name_value_pairs' => array(array('name'=> 'status', 'value' => 'open')), 'metadata_case_sensitive' => false)));
}
elseif ($filter_by == 'answered') {
    $content .= elgg_list_entities_from_relationship(array_merge($options,array('relationship' => 'answered_by' , 'relationship_guid' => elgg_get_logged_in_user_guid(), 'inverse_relationship' => true)));
}
elseif ($filter_by == 'not_answered') {
    //chapuza
    $answered = elgg_get_entities_from_relationship(array_merge($options,array('relationship' => 'answered_by' , 'relationship_guid' => elgg_get_logged_in_user_guid(), 'inverse_relationship' => true)));
    if ($answered)
        foreach ($answered as $question)
            $answered_guids[] = $question->getGUID();
    $all = elgg_get_entities_from_metadata(array_merge($options, array('limit' => null, 
            'metadata_name_value_pairs' => array(array('name'=> 'status', 'value' => 'open')), 'metadata_case_sensitive' => false)));
    foreach ($all as $question){
        $answered = false;
        foreach ($answered_guids as $guid)
            if ($question->getGUID() == $guid)
                $answered = true;
        // Los alumnos sólo verán las preguntas que no son suyas y que pueden responder
        // Los profesores verán todas las preguntas que pueden responder (incluyendo las suyas)
        if ((!$answered && is_admin_or_teacher($question->container_guid,elgg_get_logged_in_user_guid())) ||
                (!$answered && ($question->getOwnerGUID() != elgg_get_logged_in_user_guid())))
            $content .= elgg_view_entity ($question, array('full_view' => false));
    }
}
else {
    $content .= elgg_list_entities_from_metadata(array_merge($options,array('metadata_name_value_pairs' => array(array('name'=> 'featured', 'value' => true)), 'metadata_case_sensitive' => false)));
    $content .= elgg_list_entities_from_metadata(array_merge($options,array('metadata_name_value_pairs' => array(array('name'=> 'featured', 'value' => 0)), 'metadata_case_sensitive' => false)));
}
if (!$content) 
	$content = '<p>' . elgg_echo('questions:none') . '</p>';


$filter_context = '';
if (elgg_get_page_owner_guid() == elgg_get_logged_in_user_guid()) {
	$filter_context = 'mine';
}

$params = array(
	'filter_context' => $filter_context,
	'content' => $content,
	'title' => $title,
);

if (elgg_instanceof($owner, 'group')) {
	$params['filter'] = '';
}

if ($owner instanceof ElggGroup) 
    $params['sidebar'] = elgg_view('questions/sidebar/links_sidebar', array('owner' => $owner));


$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
