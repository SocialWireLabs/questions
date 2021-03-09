<?php
	return array(

			/**
			 * Titlebar
			 */

			'questions:titlebar' => "Preguntas",

			/**
			 * Title
			 */

			'questions:title:allquestions' => 'All Questions',
                        'questions:title:answer' => 'Reply',
                        'questions:title:editanswer' => "Edit Response",
                        'questions:title:editquestion' => "Edit Question",
                        'questions:title:editcomment' => "Edit Comment",
                        'questions:title:home' => "Questions",
                        'questions:title:new' => "New Question",
                        'questions:title:viewanswers' => "Show Answers",
                        'questions:title:yours' => "Your Questions ",
                        'questions:title:friends' => "Questions of your contacts",
                        'questions:title:commentanswer' => "Comment Response",
                        'questions:title:commentquestion' => "Comment Question",
                        'questions:title:user' => "Questions of %s",
                        'questions:enable_questions:success' => "Questions have been successfuly enabled",
                        'questions:disable_questions:success' => "Questions have been successfuly disabled",
                        'questions:disable_questions:information' => "The creation of questions has been disabled by the teacher.",
			'questions:enable_answers_visibility' => "Visible Answers",
			'questions:enable_answers_comments_visibility' => "Visible Comments",
			'questions:disable_answers_visibility' => "Not Visible Answers",
			'questions:disable_answers_comments_visibility' => "Not Visible Comments",

			// Credits view
			'questions:configure_credits' => "Configure credits",
			'questions:no_credit' => "You do not have sufficient credit to do questions.", 
			'questions:need_answer' => "You need 1 answer to be able to do a new question.",
			'questions:need_answers' => "You need %s answers to be able to do a new question.",
			'questions:credits_title' => "Credits of my questions",
			'questions:credits_initial' => "Initial credit: %s",
			'questions:credits_available' => "Available credit: %s",
			'questions:questions_made' => "Questions made: %s",
			'questions:questions_answered' => "Questions I answered: %s",
			'questions:more_credits_one' => 'To increase in 1 your credit, it is necessary to answer 1 question',
			'questions:more_credits' => 'To increase in 1 your credit, it is necessary to answer %s questions',
			// Credits action
			'questions:empty_initial_credits' => 'Error: field of number of initial credits empty.',
			'questions:empty_answers_credits' => 'Error: field answers for get a credit empty.',
			'questions:error_initial_credits' => 'Error: number of initial credits is wrong.',
			'questions:error_answers_credits' => 'Error: number of answers to get a credit is wrong.',
			'questions:error_configuration' => 'Error: the configuration could not be saved.',
			'questions:sucess_configuration' => 'The configuration was saved successfully.',
			// Credits form
			'questions:form:enable_credits_system' => 'Enable system of credits',
			'questions:form:initial_credits' => 'Initial credit of questions of the student',
			'questions:form:questions_answered_needed' => 'Number of answers needed to add a credit',
			'questions:form:save' => 'Save configuration',
			'questions:form:activate_save' => 'Activate and save configuration',

			/**
			 * Form
			 */

			'questions:form:access' => "Access",
                        'questions:form:answer' => "Answer",
                        'questions:form:question' => "Question (Text / HTML)",
                        'questions:form:tags' => "Tags (comma separated)",
                        'questions:form:title' => "Question",
                        'questions:form:image' => "Upload image (jpg, png, gif)",
                        'questions:form:audio' => "Upload audio file (mp3)",
                        'questions:form:file' => "Upload file",
                        'questions:form:url' => "Add Link",
                        'questions:form:url:remove' => "Remove Link",
                        'questions:form:url:add' => "Add another link",
                        'questions:simple' => "text / html",
                        'questions:simple_choice' => "Test",
                        'questions:image' => "Image",
                        'questions:audio' => "Audio",
                        'questions:file' => "File / URL",
                        'questions:member' => "Individual",
                        'questions:subgroup' => "Sub",
                        'questions:form:answer_type' => "Type of Answer",
                        'questions:form:hide' => "Hide Answers (except when asking the question and the creators of them)",
                        'questions:form:hide_comments' =>" Hide comments on the answers (if they are visible) ",
                        'questions:form:limited' => "Enable limited scores (maximum set points to distribute among all respondents). ",
                        'questions:form:who_answers' => "type of response (respondents)",
                        'questions:form:points' => "points at stake",
                        'questions:form:creator_points' => "points to the creator of the question",
                        'questions:form:notify_members' => "Notify group members creating the question (if access is not private)",
                        'questions:form:time:closing' => "closing question",
                        'questions:form:time:never' => "Undefined (manual lock)",
                        'questions:form:time:choose_date' => "Choose day and time ",
                        'questions:form:time:time' => "Time",
                        'questions:form:time:date' => 'Day',


			/**
			 * Menu & Submenu
			 */

			'questions:menu:home' => "Questions",
                        'questions:submenu:allquestions' => "All Questions",
                        'questions:submenu:home' => "Questions",
                        'questions:submenu:new' => "New Question",
                        'questions:submenu:friends' => "Questions of your contacts",
                        'questions:submenu:yours' => "Your Questions ",
                        'questions:submenu:group' => "Group Questions",
                        'questions:submenu:group:allquestions' => "All questions of this group",
                        'questions:submenu:group:new' => "New group question",
                        'questions:submenu:user' => "%s's Question",
                        'questions:submenu:pending' => "Pending to be edited by a teacher",
                        'questions:submenu:mine' => "My Questions",
                        'questions:submenu:open' => "Open questions",
                        'questions:submenu:answered' => "Questions I have answered",
                        'questions:submenu:not_answered' => "Questions I haven't answered",
                        'questions:submenu:enable' => "Enable questions",
                        'questions:submenu:disable' => "Disable questions",
                        'questions:submenu:featured' => "Highlighted!",
                        'questions:group' => "Group Questions",
                        'questions:nogroup' => "There are no questions in this group.",
                        'questions:group:enable' => "Enable Questions from the group",



			/**
			 * Alerts
			 */

			'questions:alert:answer:deleted' => "La respuesta fue eliminada exitosamente.",
			'questions:alert:answer:edited' => "La respuesta fue editada exitosamente.",
			'questions:alert:answer:submitted' => "Tu respuesta fue guardada exitosamente.",
			'questions:alert:question:deleted' => "La pregunta fue eliminada exitosamente.",
                        'questions:alert:question:featured' => "La pregunta fue destacada exitosamente.",
                        'questions:alert:question:nofeatured' => "La pregunta ya no es destacada.",
                        'questions:alert:answer:featured' => "La respuesta fue destacada exitosamente.",
                        'questions:alert:answer:nofeatured' => "La respuesta ya no es destacada.",
			'questions:alert:question:edited' => "La pregunta fue editada exitosamente.",
			'questions:alert:question:submitted' => "Tu pregunta fue guardada exitosamente.",
			'questions:alert:comment:deleted' => "Tu comentario fue borrado exitosamente.",
			'questions:alert:comment:submitted' => "Tu comentario fue guardado exitosamente.",


			/**
			 * Errors
			 */

			'questions:error:answer:delete' => "Lo lamentamos, hubo un error mientras se eliminaba la respuesta. Por favor, inténtalo de nuevo.",
			'questions:error:answer:edit' => "Lo lamentamos, hubo un error mientras se editaba la respuesta. Por favor, inténtalo de nuevo.",
			'questions:error:answer:empty' => "Tu respuesta está vacía.",
                        'questions:error:answer:empty_choice' => "No puedes responder si no marcas una opción.",
			'questions:error:comment:empty' => "Tu comentario está vacío.",
			'questions:error:answer:save' => "Lo lamentamos, hubo un error mientras se guardaba tu respuesta. Por favor, inténtalo de nuevo.",
			'questions:error:question:delete' => "Lo lamentamos, hubo un error mientras se eliminaba tu pregunta. Por favor, inténtalo de nuevo.",
			'questions:error:question:edit' => "Lo lamentamos, hubo un error mientras se editaba tu pregunta. Por favor, inténtalo de nuevo.",
			'questions:error:question:empty' => "Tu pregunta está vacía.",
			'questions:error:question:save' => "Lo lamentamos, hubo un error mientras se guardaba tu pregunta. Por favor, inténtalo de nuevo.",
			'questions:error:question:open' => "No se puede abrir la pregunta, puesto que ya fue valorada",
                        'questions:error:question:min_points' => "No tiene el mínimo número de puntos necesario para crear una pregunta.",
                        'questions:error:file:delete' => "Lo lamentamos, hubo un error mientras se eliminaba un archivo asociado a tu pregunta. Por favor, inténtalo de nuevo.",
                        'questions:error:url:failed' => "URL no válida",
                        'questions:error:closing_date' => "Si escoge una fecha de cierre, esta debe ser válida",
                        'questions:error:answer:not_subgroup_member' => "Esta es una pregunta preparada para responder en subgrupos. Si no perteneces a ninguno todavía, ingresa en uno para poder responder.",

                        /**
			 * Warnings
			 */

			'questions:warning:question:open' => "A pesar de haber abierto la pregunta, esta sigue teniendo limitación por fecha de cierre. Edite la pregunta si desea abrirla.",

                        /**
			 * Body
			 */

			'questions:points' => "%s points have been assigned to the question",
			'questions:point' => "%s point has been assigned to the question",
			'questions:body:answer' => "Answer question",
                        'questions:body:comment' => "Comment (%s)",
                        'questions:body:deleteanswer' => "Delete Reply",
                        'questions:body:deleteanswerconfirm' => "Are you sure you want to delete your answer?",
                        'questions:body:deletequestion' => "Delete Question",
                        'questions:body:deletequestionconfirm' => "Are you sure you want to delete the question?",
			'questions:body:notdeletequestion' => "To delete a question, you must delete first all answers",
                        'questions:body:featurequestion' => "Highlight Question",
                        'questions:body:nofeaturequestion' => "Unhighlight question",
                        'questions:body:featurequestionconfirm' => "Are you sure you want to highlight the question?",
                        'questions:body:nofeaturequestionconfirm' => "Are you sure you want to stop highlighting the question?",
                        'questions:body:featureanswer' => "Highlight Response",
                        'questions:body:nofeatureanswer' => "Unhighlight response",
                        'questions:body:featureanswerconfirm' => "Are you sure you want to highlight the answer?",
                        'questions:body:nofeatureanswerconfirm' => "Are you sure you want to stop highlighting the answer?",
                        'questions:body:editanswer' => "Edit Response",
                        'questions:body:editquestion' => "Edit Question",
                        'questions:body:editquestion_pending' => "Moderate and rate question",
                        'questions:body:editcomment' => "Edit Comment",
                        'questions:body:commentanswer' => "Comment Response",
                        'questions:body:deletecomment' => "Delete Comment",
                        'questions:body:deletecommentconfirm' => "Are you sure you want to delete this comment?",
                        'questions:body:emptyset' => "No Questions",
                        'questions:body:introduction' => "Usando el plugin de Preguntas usted podrá enviar preguntas a otros usuarios de la comunidad.",
                        'questions:body:noanswers' => "There are no answers to this question",
                        'questions:body:numberofanswer' => "%s answer",
                        'questions:body:numberofanswers' => "%s answers",
                        'questions:body:rate:points' => "Assigned Points",
                        'questions:body:rate:helpful' => "¿Fue de su ayuda?",
                        'questions:body:rate:rated' => "Points assigned to answer: %s",
                        'questions:body:rate:norated' => "There was not punctuated",
                        'questions:body:rate:interesting' => "Is that interesting?",
                        'questions:body:rate:answerconfirm' => "Are you sure you want to rate this answer?",
                        'questions:body:rate:questionconfirm' => "Are you sure you want to vote this question?",
                        'questions:body:submittedby' => 'Sent %s by %s',
                        'questions:body:commentedby' => 'Commented %s by %s',
                        'questions:body:viewanswers' => "Show Answers",
                        'questions:body:viewanswers_number' => "Show Answers (%s)",
                        'questions:body:viewanswer' => "Show answer",
                        'questions:body:rated' => 'Question already voted',
                        'questions:body:unrated' => 'Question not voted',
                        'questions:body:ratebutton' => 'Distribute the points',
                        'questions:body:pending' => "Question waiting to be moderated and rated by the teacher",
                        'questions:body:question_points_limited:question' => '%d points to the participants.',
                        'questions:body:question_points_no_limited:question' => 'There are no limited points to distribute among the participants.',
                        'questions:body:question_points:creator_points' => '%d point was for the creator of the question.',
                        'questions:body:hideanswers' => "The creator of the question has not enabled the vision of the other responses.",
                        'questions:body:question_closing:date_future' => "The question will be closed %s",
                        'questions:body:question_closing:date_past' => "The question was closed %s",
                        'questions:body:question_closing:never' => "There is no closing date for the question.",
                        'questions:body:simple_choice:of' => "(%s of %s)",

			/**
			 * Ordering
			 */

			'questions:ordering:asc' => 'Ascending',
                        'questions:ordering:desc' => 'Descending',
                        'questions:ordering:helpful' => 'Help',
                        'questions:ordering:interesting' => 'Interest',
                        'questions:ordering:num_of_answers' => '# Answers',
                        'questions:ordering:time_created' => 'Created',
                        'questions:ordering:username' => 'User',
                        'questions:ordering:rated' => 'Points',
                        'questions:ordering:featured' => 'Featured',

			/**
			 * Actions
			 */

			'questions:action:closequestion' => "Deshabilitar Respuestas",
			'questions:action:openquestion' => "Habilitar Respuestas",

			/**
			 * Status
			 */

			'questions:status:closequestion' => "Pregunta Cerrada",
			'questions:status:openquestion' => "Pregunta Abierta",

			/**
			 * View Answers
			 */

			'questions:viewanswers:question' => "Question",
			'questions:viewanswers:answers' => "Answers",

			/**
			 * Make a comment
			 */

			'questions:makecomment:answer' => "Answer",
			'questions:makecomment:comment' => "Comment:",




			/**
			 * Rate
			 */

			'questions:rate:answer:error' => 'Ha ocurrido un error al registrar su calificación de la respuesta',
			'questions:rate:answer:submitted' => 'Su calificación de la respuesta ha sido registrada',
			'questions:rate:question:error' => 'Ha ocurrido un error al registrar su calificación de la pregunta',
			'questions:rate:question:submitted' => 'Su calificación de la pregunta ha sido registrada',
			'questions:rate:qa:submit' => 'Distribute Points',
                        'questions:rate:qa:submit_no_answers' => 'Vote the question',
			'questions:rate:qa:sumerror' => 'Debe repartir los puntos que escogio al crear la pregunta.',
			'questions:rate:qa:points_limited' => 'You can distribute %s points',
                        'questions:rate:qa:points_no_limited' => 'You can distribute any amount of points',
			'questions:rate:qa:error' => 'Ha ocurrido un error al registrar su calificación de las respuestas',
			'questions:rate:qa:submitted' => 'Su calificación de las respuestas ha sido registrada',

			/**
			 * River
			 */

			'questions:river:answer:create' => " a la pregunta/tarea con título ",
			'questions:river:answer:created' => "%s ha respondido",
			'questions:river:question:create' => " una nueva pregunta/tarea con título ",
			'questions:river:question:created' => "%s ha creado",
                        'questions:river:question:update' => " la pregunta/tarea con título ",
			'questions:river:question:updated' => "%s ha editado",
                        'question:river:annotate' => "un comentario en la pregunta",

			/**
			 * Widgets
			 */

			'questions:widgets:description' => "Podrás ver el estado de tus preguntas.",
			'questions:widgets:numberofquestions' => "Número de preguntas a mostrar:",
			'questions:widgets:title' => "Preguntas",

			/**
			 * Email
			 */

			'questions:email:subject' => "%s ha respondido una pregunta tuya en %s.",
			'questions:email:mailbody' => "%s, %s ha respondido una pregunta tuya en %s.
Pregunta:
%s
Respuesta:
%s
Para ver la respuesta desde la página, haz clic en el siguiente enlace:%s",

                        'questions:group:email:subject' => "%s ha creado una pregunta en %s en el grupo %s.",
                        'questions:group:email:mailbody' => "%s, %s ha creado una pregunta en %s en el grupo %s.
Pregunta:
%s
Para ver la pregunta desde la página, haz clic en el siguiente enlace:%s",

                        'questions:group_approve:email:subject' => "%s ha aprobado una pregunta en %s en el grupo %s.",
                        'questions:group_approve:email:mailbody' => "%s, %s ha aprobado una pregunta en %s en el grupo %s.
Pregunta:
%s
Para ver la pregunta desde la página, haz clic en el siguiente enlace:%s",

                        'questions:group:email:subject:pending' => "%s ha creado una pregunta en %s en el grupo %s pendiente de ser moderada.",
                        'questions:group:email:mailbody:pending' => "%s, %s ha creado una pregunta en %s en el grupo %s pendiente de ser moderada.
Pregunta:
%s
Para ver la pregunta y  moderarla desde la página, haz clic en el siguiente enlace:%s",

                        'questions:email:subject:addcomment' => "%s hizo un comentario en una respuesta tuya",
                        'questions:email:mailbody:addcomment' => "%s, %s hizo un comentario en una respuesta tuya.
Comentario:
%s
Para ver la pregunta con sus respuestas y comentarios, haz clic en el siguiente enlace:%s",

			/**
			 * Settings
			 */

			'questions:settings:answers:perpage' => "Respuestas por página:",
			'questions:settings:questions:perpage' => "Preguntas por página:",
                        'questions:settings:questions:only_groups' => "Habilitado solo para grupos:",
			'questions:settings:questions:only_groups:yes' => "Si",
			'questions:settings:questions:only_groups:not' => "No",
                        'questions:settings:questions:min_points' => "Mínimo número de puntos para crear una pregunta:",

			/**
			 * Stats
			 */

			'item:object:answer' => "Answers",
			'item:object:question' => "Questions",

			/**
			* My Friendly Time
			 */

			'friendlytime:almostnow' => "in less than a minute",
			'friendlytime:minutesleft' => "in %s minutes",
			'friendlytime:minutesleft:singular' => "in a minute",
			'friendlytime:hoursleft' => "in %s hours",
			'friendlytime:hoursleft:singular' => "in an hour",
			'friendlytime:daysleft' => "in %s days",
			'friendlytime:daysleft:singular' => "tomorrow",

                         'questions' => 'Questions',
                         'questions:none' => 'No Questions were created yet',
                         'questions:add' => 'New Question',
                         'questions:edit' => 'Edit Question',
                         'questions:body:commentanswer' => "Answer Comments",
                         'river:create:object:question' => '%s created a question titled %s',
                         'river:update:object:question' => '%s updated a question titled %s',
                         'river:create:object:answer' => '%s answered the question titled %s',
                         'river:comment:object:question' => "%s commented the question %s",
                         'river:comment:object:answer' => "%s commented the answer %s",

                         /**
                         * Elgg 1.8
                         */

                         'questions' => 'Questions',
                         'questions:none' => 'No questions',
                         'questions:add' => 'New Question',
                         'questions:edit' => 'Edit Question',
                         'questions:body:commentanswer' => "Answer Comments",
                         'river:create:object:question' => '%s created a question titled %s',
                         'river:update:object:question' => '%s updated a question titled %s',
                         'river:create:object:answer' => '%s answered a question titled %s',
                         'river:comment:object:question' => "%s commented the question %s",
                         'river:comment:object:answer' => "%s commented the answer %s"
	);
