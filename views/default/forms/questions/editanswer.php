<?php
        $question = $vars['question'];

        if (isset($vars['entity'])) {
                $the_answer = $vars['entity']->answer;
                $question = get_entity($vars['entity']->container_guid);
                $answer_type = $question->answer_type;
		$tags = $vars['entity']->tags;
                $urls = explode(',',$vars['entity']->urls);
                $embed = html_entity_decode($vars['entity']->embed);
                $chosen_answer = $vars['entity']->chosen_answer;
	} else  {
                $answer_type = $question->answer_type;
		$the_answer = "";
		$tags = "";
                $urls = "";
                $embed = "";
	}
?>

<form action="<?php echo elgg_get_site_url()."action/questions/editanswer"?>" name="form" enctype="multipart/form-data" method="post">

        <?php echo elgg_view('input/securitytoken'); ?>

        <?php switch ($answer_type) {
                case 'simple':
    ?>
                    <p>
                        <label>
                            <?php echo elgg_echo("questions:form:answer"); ?>
                            <br />
                            <?php echo elgg_view("input/longtext" ,array('name' => 'answer', 'value' => $the_answer)); ?>
                        </label>
                    </p>
                <?php break; ?>

    <?php       case 'simple_choice':
                    $question_options = questions_shuffle_assoc(unserialize($question->question_options));
                    if (isset($vars['entity'])) {
                        foreach($question_options as $key => $val) {
                            $checked = ($chosen_answer == $key) ? 'CHECKED' : '';
                            ?>
                                <p>
                                  <input name="question_answer" value="<?php echo $key; ?>" class="input-radio" type="radio" <?php echo $checked; ?> />
                                  <?php echo $val; ?>
                                </p>
                            <?php
                        }
                    }
                    else {
                        foreach ($question_options as $key => $val){
                            $reversed_options[$val] = $key;
                        }
                        echo elgg_view('input/radio', array('name' => 'question_answer', 'options' => questions_shuffle_assoc($reversed_options))).'<br/>';
                    }
    ?>

                    <p>
                        <label>
                            <?php echo elgg_echo("questions:form:answer"); ?>
                            <br />
                            <?php echo elgg_view("input/longtext" ,array('name' => 'answer', 'value' => $the_answer)); ?>
                        </label>
                    </p>
                <?php break; ?>

    <?php       case 'file':
    ?>
                    <p>
	<label><?php echo elgg_echo("questions:form:answer"); ?>
  <br />
  <?php echo elgg_echo("questions:form:url"); ?></label>

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
        <?php echo elgg_echo("questions:form:file"); ?>
<br />
<?php
	echo elgg_view("input/file",array('name' => 'upload[]', 'class' => 'multi'));
?>
	</label>
<?php
        if(isset($vars['entity'])) {
            $files = elgg_get_entities_from_relationship(array( 'relationship' => 'answer_file_link',
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
    <p>
        <input type="hidden" name="question_guid" value="<?php echo $question->guid; ?>" />
        <?php  if (isset($vars['entity'])) { ?>
            <input type="hidden" name="answer_guid" value="<?php echo $vars['entity']->guid; ?>" />
        <?php } ?>
	<?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save'))); ?>
    </p>

</form>
<br /><br />
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
