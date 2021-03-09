<?php

gatekeeper();

$user_guid = elgg_get_logged_in_user_guid();
$user = get_entity($user_guid);

$questions_config_guid = get_input('questions_config_guid');
$questions_config = get_entity($questions_config_guid);

$enable_credits = get_input('enable_credits');

// Si hemos activado los créditos, tomo los campos necesarios del formulario
if (strcmp($enable_credits,"on")==0){
   $enable_credits = true;
   $num_credits = get_input('num_credits');
   $credits_add = get_input('credits_add');
}
else{
   $enable_credits = false; 
}

//Cache to the session
elgg_make_sticky_form('configure_credits_questions');

// Busco los posibles errores en los campos
if ($enable_credits){
   // Comprobamos si alguno de los campos está vacío
   if (strcmp($num_credits,'')==0) { 
      register_error(elgg_echo('questions:empty_initial_credits'));
      forward($_SERVER['HTTP_REFERER']);
   }   
   if (strcmp($credits_add,'')==0) { 
      register_error(elgg_echo('questions:empty_answers_credits'));
      forward($_SERVER['HTTP_REFERER']);
   }
   // Comprobamos si todos los campos son números enteros
   $mask_integer='^([[:digit:]]+)$';                           
   // Primero los créditos iniciales
   $is_integer = true;
   if (ereg($mask_integer,$num_credits,$same)){
      if ((substr($same[1],0,1)==0)&&(strlen($same[1])!=1))
         $is_integer=false;
   } else
      $is_integer=false;
   if (!$is_integer){
      register_error(elgg_echo('questions:error_initial_credits'));
      forward($_SERVER['HTTP_REFERER']);
   }
   // Luego los créditos que se suman
   $is_integer = true;
   if (ereg($mask_integer,$credits_add,$same)){
      if ((substr($same[1],0,1)==0)&&(strlen($same[1])!=1))
         $is_integer=false;
   } else
      $is_integer=false;
   if (!$is_integer){
      register_error(elgg_echo('questions:error_answers_credits'));
      forward($_SERVER['HTTP_REFERER']);
   }
}
     
// No tengo que crear la entidad de configuración de créditos 
if ($questions_config && $questions_config->getSubtype() == 'questions_configure_credits') {
   $container_guid = $questions_config->container_guid;
   if ($enable_credits) {
      $questions_config->enable_credits = true;
      $questions_config->num_credits = $num_credits;
      $questions_config->credits_add = $credits_add;
   } else 
      $questions_config->enable_credits = false;
}
// Tengo que crear la entidad de configuración de créditos 
else {
   $container_guid = get_input('container_guid');
   $questions_config = new ElggObject();
   $questions_config->subtype = 'questions_configure_credits';
   $questions_config->owner_guid = $user_guid;
   $questions_config->container_guid = $container_guid;
   $questions_config->group_guid = $container_guid;
   $questions_config->access_id = get_entity($container_guid)->group_acl;
   
   if ($enable_credits) {
      $questions_config->enable_credits = true;
      $questions_config->num_credits = $num_credits;
      $questions_config->credits_add = $credits_add;
   } else
      $questions_config->enable_credits = false;
   if (!$questions_config->save()) {
      register_error(elgg_echo('questions:error_configuration'));
      forward($_SERVER['HTTP_REFERER']);
   }
}

// Remove the e_portfolio post cache
elgg_clear_sticky_form('configure_credits_questions');

system_message(elgg_echo('questions:sucess_configuration'));

forward(elgg_get_site_url() . 'questions/group/' . $container_guid);

?>