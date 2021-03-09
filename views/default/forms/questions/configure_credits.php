<div class="contentWrapper">

<?php

$user_guid = elgg_get_logged_in_user_guid();
$user = get_entity($user_guid);

$questions_config = $vars['entity'];
$container_guid = $vars['container_guid'];
$container = get_entity($container_guid);

$action = "questions/configure_credits";

// Hemos hecho la configuración antes
if (isset($questions_config)) {
   if (!elgg_is_sticky_form('configure_credits_questions')) {
      $enable_credits = $questions_config->enable_credits;
      $num_credits = $questions_config->num_credits;
      $credits_add = $questions_config->credits_add;
   } else {
      $enable_credits = elgg_get_sticky_value('configure_credits_questions','enable_credits');
      if (strcmp($enable_credits,'on')==0) {
         $enable_credits = true;
         $num_credits = elgg_get_sticky_value('configure_credits_questions','num_credits');
         $credits_add = elgg_get_sticky_value('configure_credits_questions','credits_add');
      }
      else
         $enable_credits = false; 
   }
// No hemos hecho la configuración antes
} else {
   if (!elgg_is_sticky_form('configure_credits_questions')) {
      $enable_credits = false;
      $num_credits = 20;
      $credits_add = 2;
   } else {
      $enable_credits = elgg_get_sticky_value('configure_credits_questions','enable_credits');
      if (strcmp($enable_credits,'on')==0){
         $enable_credits = true;
         $num_credits = elgg_get_sticky_value('configure_credits_questions','num_credits');
         $credits_add = elgg_get_sticky_value('configure_credits_questions','credits_add');
      }
      else
         $enable_credits = false; 
   }
}

elgg_clear_sticky_form('configure_credits_questions');

$enable_credits_label = elgg_echo('questions:form:enable_credits_system');
if ($enable_credits) {
   $selected_enable_credits = "checked = \"checked\"";
   $style_display_enable_credits = "display:bloc";
} else {
   $selected_enable_credits = "";
   $style_display_enable_credits = "display:none";
}
$num_credits_label = elgg_echo('questions:form:initial_credits');
$credits_add_label = elgg_echo('questions:form:questions_answered_needed');

?>

<form action="<?php echo elgg_get_site_url()."action/".$action?>" name="configure_credits_questions" enctype="multipart/form-data" method="post">

<?php echo elgg_view('input/securitytoken'); ?>

<p>
<b>
<?php echo "<input type = \"checkbox\" $disabled name = \"enable_credits\" $selected_enable_credits onChange=\"questions_show_enable_credits()\"> $enable_credits_label";?>
</b>   
</p>
<div id="resultsDiv_enable_credits" style="<?php echo $style_display_enable_credits;?>;">
   <p>
   <b><?php echo $num_credits_label; ?></b>
   <?php echo "<input type = \"text\" name = \"num_credits\" value = $num_credits>"; ?></p>
   <p>
   <b><?php echo $credits_add_label; ?></b>
   <?php echo "<input type = \"text\" name = \"credits_add\" value = $credits_add>"; ?></p>
   <p></p>
</div>

<!-- add the add/delete_response functionality  -->
<script type="text/javascript">
// remove function for the jquery clone plugin
$(function(){
   var removeLink = '<a class="remove" href="#" onclick="$(this).parent().slideUp(function(){ $(this).remove() }); return false"><?php echo elgg_echo("delete");?></a>';
   $('a.add').relCopy({ append: removeLink});
});
</script>

<?php
if (isset($questions_config)) {
   ?>
   <input type="hidden" name="questions_config_guid" value="<?php echo $questions_config->getGUID(); ?>">
   <?php
   $submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('questions:form:save')));
} else {
   ?>
   <input type="hidden" name="container_guid" value="<?php echo $vars['container_guid']; ?>">
   <?php
   $submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('questions:form:activate_save')));
}
echo $submit_input;
?>

</form>

<script language="javascript">
   function questions_show_enable_credits(){
      var resultsDiv_enable_credits = document.getElementById('resultsDiv_enable_credits');
      if (resultsDiv_enable_credits.style.display == 'none')
         resultsDiv_enable_credits.style.display = 'block';
      else        
         resultsDiv_enable_credits.style.display = 'none';
   }    
</script>

<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>mod/questions/lib/reCopy.js"></script><!-- copy field jquery plugin -->

</div>