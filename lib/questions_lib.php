<?php
        function order_question_links($url) {
			$asc = elgg_echo('questions:ordering:asc');
			$desc = elgg_echo('questions:ordering:desc');

			$links = '<div class="questions_links">';
			$links .= '<a href="?order_by=time_created&criteria=asc" title="'.$asc.'"><img src="' . $url . 'mod/questions/graphics/arrowup_15.png" /></a>';
			$links .= '<a href="?order_by=time_created&criteria=desc" title="'.$desc.'"><img src="' . $url . 'mod/questions/graphics/arrowdown_15.png" /></a>&nbsp;'.elgg_echo('questions:ordering:time_created').'&nbsp;&nbsp;';
                        $links .= '<a href="?order_by=num_of_answers&criteria=asc" title='.$asc.'><img src="' . $url . 'mod/questions/graphics/arrowup_15.png" /></a>';
                        $links .= '<a href="?order_by=num_of_answers&criteria=desc" title="'.$desc.'"><img src="' . $url . 'mod/questions/graphics/arrowdown_15.png" /></a>&nbsp;'.elgg_echo('questions:ordering:num_of_answers').'&nbsp;&nbsp;';
                        $links .= '<a href="?filter_by=featured" title="'.$asc.'"><img src="' . $url . 'mod/questions/graphics/starB.gif" /></a>'.elgg_echo('questions:ordering:featured');
			$links .= '</div>';
			$links .= '<div class="clearfloat"></div>';

			return $links;
	}

	function my_friendly_time($time) {
		$diff = time() - ((int) $time);
		if ($diff > 0){
			if ($diff < 60) {
				return elgg_echo("friendlytime:justnow");
			} else if ($diff < 3600) {
				$diff = round($diff / 60);
				if ($diff == 0) {
					$diff = 1;
				}
				if ($diff > 1) {
					return sprintf(elgg_echo("friendlytime:minutes"),$diff);
				}
				return sprintf(elgg_echo("friendlytime:minutes:singular"),$diff);
			} else if ($diff < 86400) {
			$diff = round($diff / 3600);
			if ($diff == 0) {
				$diff = 1;
			}
			if ($diff > 1) {
				return sprintf(elgg_echo("friendlytime:hours"),$diff);
			}
			return sprintf(elgg_echo("friendlytime:hours:singular"),$diff);
			} else {
				$diff = round($diff / 86400);
				if ($diff == 0) {
					$diff = 1;
				}
				if ($diff > 1) {
					return sprintf(elgg_echo("friendlytime:days"),$diff);
				}
				return sprintf(elgg_echo("friendlytime:days:singular"),$diff);
			}
		}
		else {
			$diff = 0 - $diff;
			if ($diff < 60) {
				return elgg_echo("friendlytime:almostnow");
			}
			else if ($diff < 3600) {
				$diff = round($diff / 60);
				if ($diff == 0) {
					$diff = 1;
				}
				if ($diff > 1) {
					return sprintf(elgg_echo("friendlytime:minutesleft"),$diff);
				}
				return sprintf(elgg_echo("friendlytime:minutesleft:singular"),$diff);
			}
			else if ($diff < 86400) {
			$diff = round($diff / 3600);
			if ($diff == 0) {
				$diff = 1;
			}
			if ($diff > 1) {
				return sprintf(elgg_echo("friendlytime:hoursleft"),$diff);
			}
			return sprintf(elgg_echo("friendlytime:hoursleft:singular"),$diff);
			}
			else {
				$diff = round($diff / 86400);
				if ($diff == 0) {
					$diff = 1;
				}
				if ($diff > 1) {
					return sprintf(elgg_echo("friendlytime:daysleft"),$diff);
				}
				return sprintf(elgg_echo("friendlytime:daysleft:singular"),$diff);
			}
		}
	}

        /**
         * Get the contents of an uploaded file.
         * (Returns false if there was an issue.)
         *
         * @param string $input_name The name of the file input field on the submission form
         * @return mixed|false The contents of the file, or false on failure.
         */
        function get_uploaded_question_file($input_name, $counter) {
                // If the file exists ...
                if (isset($_FILES[$input_name]) && isset($_FILES[$input_name]['error'][$counter])) {
                        return file_get_contents($_FILES[$input_name]['tmp_name'][$counter]);
                }
                return false;
        }

        function is_question_owner($question) {
                if ($question->owner_guid == elgg_get_logged_in_user_guid())
                        return true;
                else
                        return false;
        }

        function is_answer_owner($answer) {
                $question = get_entity($answer->container_guid);
                $container = get_entity($question->container_guid);
                if ($container instanceof ElggGroup && $question->who_answers == 'subgroup') {
                    if (is_group_member($answer->owner_guid, elgg_get_logged_in_user_guid()))
                        return true;
                    else
                        return false;
                }
                else {
                    if ($answer->owner_guid == elgg_get_logged_in_user_guid())
                            return true;
                    else
                            return false;
                }
        }

        function question_status($question) {
                if ($question->closing != 'date')
                    return $question->status;
                elseif ($question->closing_timestamp < time())
                    return 'closed';
                else
                    return 'open';
        }
        
        //Está en gamepoints (start.php)
        /*function is_admin_or_teacher($container_guid, $user_guid = null) {
                if ($user_guid)
                    $user = get_entity($user_guid);
                else
                    $user = elgg_get_logged_in_user_entity();
                $container = get_entity($container_guid);
                if ($container instanceof ElggGroup) {
                        if ($user->guid == $container->owner_guid
                            || (elgg_is_active_plugin('group_tools') && (check_entity_relationship($user->guid,'group_admin', $container->guid))))
                                return true;
                        else
                            return false;
                }
                else {
                        return $user->isAdmin();
                }
        }*/
?>