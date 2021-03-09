<?php

        $container_guid = $vars['container_guid'];
        $container = get_entity ($container_guid);
        $question_type = get_input('question_type',false);

        if (isset($vars['entity'])) {
		$title = $vars['entity']->title;
		$question = $vars['entity']->question;
		$tags = $vars['entity']->tags;
		$access_id = $vars['entity']->access_id;
		$points = $vars['entity']->points;
                $pending = $vars['entity']->pending;
                $creator_points = $vars['entity']->creator_points;
                if ($question_type === false) $question_type = $vars['entity']->question_type;
                $urls = explode(',',$vars['entity']->urls);
                $hide_answers = $vars['entity']->hide_answers;
                $hide_comments = $vars['entity']->hide_comments;
                $answer_type = $vars['entity']->answer_type;
                $who_answers = $vars['entity']->who_answers;
                $closing = $vars['entity']->closing;
                $closing_time = $vars['entity']->closing_time;
                $closing_date = $vars['entity']->closing_date;
                $container_guid = $vars['entity']->container_guid;
                $container = get_entity($container_guid);
                if ($question_type == 'simple_choice')
                    $question_options = unserialize($vars['entity']->question_options);
       	}
        else  {
		$title = "";
		$question = "";
		$tags = "";
                if (!$container instanceof ElggGroup) $access_id = 2;
		$points = 0;
                $creator_points = 0;
                if ($question_type === false) $question_type = 'simple';
                $urls = "";
                $answer_type = 'simple';
                $who_answers = 'member';
                $closing = 'never';
                $hide_answers = false;
                $hide_comments = false;
	}

?>
<div class="contentWrapper">
    <br/>
    <?php
        $tabs = array(
            'simple' => array(
                'title' => elgg_echo('questions:simple'),
                'url' => '?question_type=simple',
                'selected' => $question_type == 'simple',
            ),
			'file' => array(
                'title' => elgg_echo('questions:file'),
                'url' => '?question_type=file',
                'selected' => $question_type == 'file',
            ),
            'simple_choice' => array(
                'title' => elgg_echo('questions:simple_choice'),
                'url' => '?question_type=simple_choice',
                'selected' => $question_type == 'simple_choice',
            ),
        );

        echo elgg_view('navigation/tabs', array('tabs' => $tabs));
    ?>
    <br/>

    <p>
        <label>
            <strong><?php echo elgg_echo("questions:form:title"); ?></strong>
            <br />
            <?php echo elgg_view("input/text" ,array('name' => 'title', 'value' => $title)); ?>
        </label>
    </p>
    <?php switch ($question_type) {
                case 'simple':
    ?>
                    <p>
                        <label>
                            <strong><?php echo elgg_echo("questions:form:question"); ?></strong>
                            <br />
                            <?php echo elgg_view("input/longtext" ,array('name' => 'question', 'value' => $question)); ?>
                        </label>
                    </p>
                <?php break; ?>

    <?php      case 'simple_choice':
    ?>
                    <p>
                        <label>
                            <strong><?php echo elgg_echo("questions:form:question"); ?></strong>
                            <br />
                            <?php echo elgg_view("input/longtext" ,array('name' => 'question', 'value' => $question)); ?>
                        </label>
                    </p>

                    <?php if (!isset($vars['entity'])) { ?>
                        <div id="option-array">
                             <p id="option1">
                                 <label>
                                     <?php
                                         echo elgg_echo('questions:form:question_option');
                                         //echo elgg_view("input/text", array('name' => "question_options[opt:1]", 'js' => "onblur=leave_corresponding_option(1) onfocus=focus_corresponding_option(1) onkeyup=correct_option_label(this.value,1)"));
										 echo elgg_view("input/text", array('name' => "question_options[opt:1]", "onblur" =>"leave_corresponding_option(1)", "onfocus"=>"focus_corresponding_option(1)", "onkeyup"=>"correct_option_label(this.value,1)"));
                                     ?>
                                 </label>
                                 <a id="addmore" href="#"><?php echo elgg_echo('questions:form:question_option_add'); ?></a>
                             </p>
                        </div>
                    <?php } else {?>

                        <p>
                          <label>
                              <?php echo elgg_echo('questions:form:question_option'); ?><br />
                          </label>
                        </p>
                    <?php
                        foreach($question_options as $key => $val) {
                    ?>
                            <p>
                              <?php echo elgg_view("input/text", array("name" => "question_options[".$key."]", "value" => $val, 'class' => 'general-text')); ?>
                            </p>
                    <?php
                        }
                    }
                    break; ?>

    <?php       case 'file':
    ?>
                    <p>
                        <label>
                            <strong><?php echo elgg_echo("questions:form:question"); ?></strong>
                            <br />
                            <?php echo elgg_view("input/longtext" ,array('name' => 'question', 'value' => $question)); ?>
                        </label>
                    </p>
                    <p>

	<label><strong><?php echo elgg_echo("questions:form:url"); ?></strong></label>

<?php
        if(count($urls) > 0 && $urls[0] != '') {
            foreach ($urls as $url)
            {
?>
                <p class="clone">
<?php
                    echo elgg_view("input/text", array(
                                                        "name" => "urls[]",
                                                        "value" => $url
                                                                                ));
?>
                    <!-- remove link -->
                    <a class="remove" href="#" onclick="$(this).parent().slideUp(function(){ $(this).remove() }); return false"><?php echo elgg_echo("questions:form:url:remove"); ?></a>
                </p>
<?php
            }
?>
                <!-- add another link/remove link functionality without attaching the remove link to it.-->
                <script type="application/javascript">
                    // remove function for the jquery clone plugin
                    $(function(){
                        $('a.add').relCopy();
                    });
                </script>
<?php
        }
        else {
?>
            <p class="clone">
<?php
                echo elgg_view("input/text", array(
                                                        "name" => "urls[]",
                                                        "value" => $urls
                                                                                        ));
?>
            </p>

            <!-- add the link/remove link functionality  -->
            <script type="application/javascript">
                // remove function for the jquery clone plugin
                $(function(){
                    var removeLink = '<a class="remove" href="#" onclick="$(this).parent().slideUp(function(){ $(this).remove() }); return false">remove</a>';
                    $('a.add').relCopy({ append: removeLink});
                });

            </script>
<?php
        }

?>

<!-- add link to add more URL fields which triggers a jquery clone function -->
<a href="#" class="add" rel=".clone"><?php echo elgg_echo("questions:form:url:add"); ?></a>
<br /><br /> <!-- even out the space between url and file -->
<p>
    <label>
        <strong><?php echo elgg_echo("questions:form:file"); ?></strong>
<br />
<?php
	echo elgg_view("input/file",array('name' => 'upload[]', 'class' => 'multi'));
?>
	</label>
<?php
        if(isset($vars['entity'])) {
            $files = elgg_get_entities_from_relationship(array( 'relationship' => 'question_file_link',
                                                                    'relationship_guid' => $vars['entity']->getGUID(),
                                                                    'inverse_relationship' => FALSE,
                                                                    'types' => 'object',
                                                                    'subtypes' => 'question_file'));

           if(count($files) > 0)
           {
                foreach($files as $file) {
?>
                    <div class="file_wrapper">
                         <a class="bold" onclick="changeFormValue(<?php echo $file->guid; ?>), changeImage(<?php echo $file->guid; ?>)">
                             <img id ="image_<?php echo $file->guid; ?>" src="<?php echo elgg_get_site_url(); ?>mod/questions/graphics/tick.jpeg">
                         </a>
                         <span><?php echo $file->title ?></span>
<?php
                         echo elgg_view("input/hidden",array('name' => $file->guid, 'internalid'=> $file->guid, 'value' => '0'));
?>
                    </div>
<?php
                }
            }
        }
?>
                <?php break;
    }?>
    <br/>
    <?php
        if ($question_type != 'simple_choice') {
    ?>
    <p>
        <label><strong><?php echo elgg_echo("questions:form:answer_type"); ?> </strong></label><br/>
        <?php echo elgg_view('input/radio',array('name'=>'answer_type','options'=> array(elgg_echo('questions:simple') => 'simple',elgg_echo('questions:file') => 'file'), 'value' => $answer_type));?>
    </p>
    <?php
        }
    ?>

    <?php if ($container instanceof ElggGroup &&
            ($container->owner_guid == elgg_get_logged_in_user_guid() && is_admin_or_teacher($container->guid,elgg_get_logged_in_user_guid()))) { ?>
    <p>
                        <?php if ($hide_answers) { ?>
                            <input checked type="checkbox" name="hide_answers"><?php echo elgg_echo('questions:form:hide') ?>
                        <?php } else {?>
                            <input type="checkbox" name="hide_answers"><?php echo elgg_echo('questions:form:hide') ?>
                            <?php }?>
    </p>
    <p>
                        <?php if ($hide_comments) { ?>
                            <input checked type="checkbox" name="hide_comments"><?php echo elgg_echo('questions:form:hide_comments') ?>
                        <?php } else {?>
                            <input type="checkbox" name="hide_comments"><?php echo elgg_echo('questions:form:hide_comments') ?>
                            <?php }?>
    </p>
    <p>
            <input <?php if (!isset($vars['entity'])) { ?> checked <?php } ?> type="checkbox" name="notify_members"><?php echo elgg_echo('questions:form:notify_members') ?>
    </p>
    <?php } ?>
    <p>
         <label>
             <strong><?php echo elgg_echo("questions:form:tags"); ?></strong>
             <br/>
             <?php echo elgg_view("input/tags",array('name' => 'tags', 'value' => $vars['entity']->tags)); ?>
         </label>
    </p>

    <?php	if (!$container instanceof ElggGroup){ ?>
                    <p>
                        <label>
                            <strong><?php echo elgg_echo('questions:form:points'); ?></strong>
                            <br/>
                                <select name="points">
                                    <option selected="selected" value="0">0</option>
                                    <?php for ($i = 1 ; $i <= $total_points ; $i ++) {
                                                if ($i != $points)
                                                    echo "<option value=\"$i\">$i</option>";
                                                else
                                                    echo "<option selected value=\"$i\">$i</option>";
                                    } ?>
                                </select>
                        </label>
                    </p>
            <?php }
            else { ?>

                <?php if (!($container->getContainerEntity() instanceof ElggGroup) && is_admin_or_teacher($container->guid,elgg_get_logged_in_user_guid())){ ?>
                <p>
                    <label><strong><?php echo elgg_echo("questions:form:who_answers"); ?> </strong></label><br/>
                    <?php $who_answers = 'member';
                        echo elgg_view('input/radio',array('name'=>'who_answers','options'=> array(elgg_echo('questions:member') => 'member',elgg_echo('questions:subgroup') => 'subgroup'), 'value' => $who_answers));?>
                </p>
                <?php } ?>


            <?php if ($container->owner_guid == elgg_get_logged_in_user_guid()
                        || is_admin_or_teacher($container->guid,elgg_get_logged_in_user_guid())) {
                    if ($vars['entity']->status != 'closed') { ?>
                    <p>
                        <label>
                            <strong><?php echo elgg_echo("questions:form:time:closing"); ?></strong>
                        </label>
                        <br/>
                        <input type="radio" name="closing"  value="never" <?php if ($closing == 'never') echo "checked = \"checked\"";?> class="input-radio" /><?php echo elgg_echo('questions:form:time:never'); ?>
                        <br />
                        <input type="radio"   name="closing"  value="date" <?php if ($closing == 'date') echo "checked = \"checked\"";?> class="input-radio" /><?php echo elgg_echo('questions:form:time:choose_date'); ?>
                        <br/>
			<?php echo elgg_view("input/date",array('timestamp'=>TRUE, 'autocomplete'=>'off','class'=>'questions-compressed-date',"name" => "closing_date","value" => $closing_date)); ?>
                        <br/>
                        <?php echo elgg_echo('questions:form:time:time'); ?>
                        <?php echo elgg_view("input/questions_timepicker",array('name' => 'closing_time','value'=>$closing_time)); ?>
                    </p>
                    <?php } ?>
                    <?php if ($vars['entity']->directed){ ?>
                                <p>
                                    <label>
                                        <strong><?php echo elgg_echo('questions:form:creator_points')."&nbsp;"; ?></strong>
                                        <?php echo elgg_view("input/text",array('name' => 'creator_points', 'value' => $creator_points)); ?>
                                    </label>
                                </p>
                    <?php }
                }
                else {
                    //aqui se le mete una marca de que esta pregunta fue creada por un no moderador de grupo y aun no fue editada por el mismo ?>
                    <input type="hidden" name="pending" value="yes">
                <?php }
            } ?>

            <?php if (!($container->getContainerEntity() instanceof ElggGroup) && ($container->owner_guid != elgg_get_logged_in_user_guid() &&
                            (elgg_is_active_plugin('group_tools') && (!check_entity_relationship(elgg_get_logged_in_user_guid(),'group_admin', $container->guid ))))) {
                        // Pregunta creada por un alumno en un grupo => acceso por defecto para profesores del grupo (pendientes de moderación)
                        $access_id = $container->teachers_acl;
                        echo elgg_view('input/hidden', array('name' => 'access_id','value' => $access_id ));
                  }
                  elseif ($container->getContainerEntity() instanceof ElggGroup && ($container->owner_guid != elgg_get_logged_in_user_guid() &&
                            (elgg_is_active_plugin('group_tools') && (!check_entity_relationship(elgg_get_logged_in_user_guid(),'group_admin', $container->getContainerGUID() ))))) {
                        // Pregunta creada por un alumno en un subgrupo => acceso por defecto: profesores del grupo y alumnos del subgrupo (pendientes de moderación)
                        $access_id = $container->getContainerEntity()->teachers_acl;
                        echo elgg_view('input/hidden', array('name' => 'access_id','value' => $access_id ));
                  }
                  else { ?>
                    <p>
                        <label>
                            <strong><?php echo elgg_echo('questions:form:access')."<br />"; ?></strong>
                            <?php
                            if (!($container->getContainerEntity() instanceof ElggGroup))
                                // Pregunta moderada por un profesor en un grupo => acceso por defecto para todo el grupo
                                $access_id = $container->group_acl;
                            else
                                // Pregunta moderada por un profesor en un grupo => acceso por defecto para profesores del grupo + alumnos del subgrupo
                                $access_id = $container->teachers_acl;
                            echo elgg_view('input/access', array('name' => 'access_id','value' => $access_id));
                            ?>
                        </label>
                    </p>
            <?php } ?>

            <?php if (isset($vars['entity'])) { ?>
                <input type="hidden" name="question_guid" value="<?php echo $vars['entity']->guid; ?>">
            <?php } ?>
            <input type="hidden" name="question_type" value="<?php echo $question_type; ?>">
            <?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save'))); ?>
            <input type="hidden" name="container_guid" value="<?php echo $container_guid; ?>">



<script type="application/javascript" src="<?php echo elgg_get_site_url(); ?>mod/questions/lib/jquery.MultiFile.js"></script><!-- multi file jquery plugin -->
<script type="application/javascript" src="<?php echo elgg_get_site_url(); ?>mod/questions/lib/reCopy.js"></script><!-- copy field jquery plugin -->
<script type="application/javascript" src="<?php echo elgg_get_site_url(); ?>mod/questions/lib/js_functions.js"></script>
<script language="javascript" type="application/javascript">
    function changeImage(num) {
        if (document.getElementById('image_'+num).src == "<?php echo elgg_get_site_url(); ?>mod/questions/graphics/tick.jpeg")
            document.getElementById('image_'+num).src = "<?php echo elgg_get_site_url(); ?>mod/questions/graphics/papelera.jpeg";
        else
            document.getElementById('image_'+num).src = "<?php echo elgg_get_site_url(); ?>mod/questions/graphics/tick.jpeg";
    }
</script>
<script type="application/javascript">
  var counter=2;
  $('document').ready(function(){
    $("#addmore").click(function(){
      $("#option-array").append('<p id="option'+counter+'"><label><?php echo elgg_echo('questions:form:question_option'); ?><input onblur="leave_corresponding_option('+counter+')" onfocus="focus_corresponding_option('+counter+')" onkeyup="correct_option_label(this.value,'+counter+')" type="text" name="question_options[opt:'+counter+']" class="input-text"></label><a href="javascript:del_options(\''+counter+'\');"><?php echo elgg_echo('questions:form:question_option_delete'); ?></a></p>');
      counter++;
      return false;
    });
  });
  function del_options(id){
    $("#option"+id).remove();
    $("#correct"+id).remove();
  }
  /*function correct_option_label(str,ctr){
    $("#rlabel"+ctr).text(str).html();
  }

  function focus_corresponding_option(ctr){
    $("#rlabel"+ctr).css('background',"#BFFBB1");
  }
  function leave_corresponding_option(ctr){
    $("#rlabel"+ctr).css('background',"#FFFFFF");
  }*/
</script>
</div>
