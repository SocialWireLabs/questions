<?php

class QuestionsPluginFile extends ElggFile
{
	protected function initialiseAttributes()
        {
		parent::initialise_attributes();
		$this->attributes['subtype'] = "question_file";
                $this->attributes['class'] = "ElggFile";
	}

        public function __construct($guid = null)
        {
          if ($guid && !is_object($guid)) {
              // Loading entities via __construct(GUID) is deprecated, so we give it the entity row and the
              // attribute loader will finish the job. This is necessary due to not using a custom
              // subtype (see above).
              $guid = get_entity_as_row($guid);
          }
		    parent::__construct($guid);
        }
}

function questions_init() {

// Extend system CSS with our own styles, which are defined in the questions/css view
   elgg_extend_view('css/elgg','questions/css');

// Register a page handler, so we can have nice URLs
   elgg_register_page_handler('questions','questions_page_handler');

// Register a URL handler for questions
   elgg_register_plugin_hook_handler('entity:url','object','questions_url');
   elgg_register_plugin_hook_handler('entity:url','object','answers_url');

// Register entity type
   elgg_register_entity_type('object','question');
   elgg_register_entity_type('object','answer');

   elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'questions_owner_block_menu');

// Add group menu option
   add_group_tool_option('questions',elgg_echo('questions:group:enable'),false);
   elgg_extend_view('groups/tool_latest', 'questions/group_module');

   elgg_register_library('socialwire:questions', elgg_get_plugins_path() . 'questions/lib/questions_lib.php');

// Register QuestionsPluginFile subtype
   run_function_once("questions_files_add_sub_once");
}

function questions_page_handler($page) {
   if (!isset($page[0])) {
      $page[0] = 'all';
   }

   //elgg_push_breadcrumb(elgg_echo('questions'), 'questions/all');
   elgg_push_breadcrumb(elgg_echo('questions'));
   $base_dir = elgg_get_plugins_path() . 'questions/pages/questions';

   switch ($page[0]) {
      case "view":
         set_input('guid', $page[1]);
         include "$base_dir/read.php";
         break;
      case "owner":
         set_input('username', $page[1]);
	 include "$base_dir/owner.php";
	 break;
      case "group":
	 set_input('username', $page[1]);
	 include "$base_dir/owner.php";
	 break;
      case "friends":
	 set_input('username', $page[1]);
	 include "$base_dir/friends.php";
	 break;
      case "all":
	 include "$base_dir/everyone.php";
	 break;
      case 'configure_credits':
   set_input('container_guid',$page[1]);
   include "$base_dir/configure_credits.php";
   break;
      case "add":
	 set_input('guid', $page[1]);
	 include "$base_dir/new.php";
         break;
      case "edit":
	 set_input('guid', $page[1]);
	 include "$base_dir/edit.php";
	 break;
      default:
	 return false;
   }
}

/**
* Returns the url from a question entity
*
* @param string $hook   'entity:url'
* @param string $type   'object'
* @param string $url    The current URL
* @param array  $params Hook parameters
* @return string question post URL
**/
function questions_url($hook, $type, $url, $params) {
  $entity = $params['entity'];
  // Check that the entity is a question object
  if ($entity->getSubtype() !== 'question') {
    // This is not a question object, so there's no need to go further
    return;
  }
  $title = $entity->title;
  $title = elgg_get_friendly_title($title);
  $url = elgg_get_config('url');
  return $url . "questions/view/" . $entity->getGUID() . "/" . $title;
}

/**
* Returns the url from an answer entity
*
* @param string $hook   'entity:url'
* @param string $type   'object'
* @param string $url    The current URL
* @param array  $params Hook parameters
* @return string answer post URL
**/
function answers_url($hook, $type, $url, $params) {
  $entity = $params['entity'];
  // Check that the entity is a answer object
  if ($entity->getSubtype() !== 'answer') {
    // This is not a answer object, so there's no need to go further
    return;
  }
  $question = get_entity($entity->container_guid);
  $title = $question->title;
  $title = elgg_get_friendly_title($title);
  $url = elgg_get_config('url');
  return $url . "questions/view/" . $entity->getGUID() . "/" . $title;
}

/**
* Add a menu item to the user ownerblock
*/
function questions_owner_block_menu($hook, $type, $return, $params) {
   if (elgg_instanceof($params['entity'], 'group')) {
      if ($params['entity']->questions_enable != "no") {
         $url = "questions/group/{$params['entity']->guid}/all";
         $item = new ElggMenuItem('questions', elgg_echo('questions:group'), $url);
         $return[] = $item;
      }
   }
   return $return;
}

// Make sure the questions initialisation function is called on initialisation
elgg_register_event_handler('init','system','questions_init');

//elgg_register_plugin_hook_handler('permissions_check','object','questions_can_edit');
elgg_register_plugin_hook_handler('permissions_check', 'object', 'answers_write_permission_check');

// Register actions
$action_base = elgg_get_plugins_path() . 'questions/actions/questions';
elgg_register_action("questions/edit", "$action_base/edit.php");
elgg_register_action("questions/configure_credits", "$action_base/configure_credits.php");
elgg_register_action("questions/delete", "$action_base/delete.php");
elgg_register_action("questions/feature", "$action_base/feature.php");
elgg_register_action("questions/editanswer", "$action_base/editanswer.php");
elgg_register_action("questions/openquestion", "$action_base/openquestion.php");
elgg_register_action("questions/closequestion", "$action_base/closequestion.php");
elgg_register_action("questions/rateanswers", "$action_base/rateanswers.php");
elgg_register_action("questions/change_answers_visibility", "$action_base/change_answers_visibility.php");
elgg_register_action("questions/change_answers_comments_visibility", "$action_base/change_answers_comments_visibility.php");

function questions_files_add_sub_once(){
   add_subtype("object", "question_file", "QuestionsPluginFile");
}

function questions_shuffle_assoc( $array )
{
   $keys = array_keys( $array );
   shuffle( $keys );
   return array_merge( array_flip( $keys ) , $array );
}

function questions_can_edit($hook_name, $entity_type, $return_value, $params) {
   if ($params['entity']->getSubtype() ==  "question")
      return true;
}

function answers_write_permission_check($hook, $entity_type, $returnvalue, $params) {
   if ($params['entity']->getSubtype() == 'answer') {
      $user_guid = elgg_get_logged_in_user_guid();
      $question_guid = $params['entity']->container_guid;
      $question=get_entity($question_guid);
      $container_guid=$question->container_guid;
      $container=get_entity($container_guid);
      $operator=false;
      $subgroup_member=false;
      if ($container instanceof ElggGroup){
         if (is_admin_or_teacher($container_guid,$user_guid))
            $operator=true;
         if ($question->who_answers == 'subgroup') {
            if (is_group_member($params['entity']->owner_guid,$user_guid)){
	       $subgroup_member=true;
	    }
         }
      }
      if (($operator)||($subgroup_member))
         return true;
   } elseif ($params['entity']->getSubtype() == 'question_file') {
      return true;
   }
}

/* size puede ser "large" o "small" */

?>
