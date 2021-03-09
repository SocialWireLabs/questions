<?php
/*=============================
===============================
  Questions plugin CSS
===============================
=============================*/

/*=======================================
   CSS for displaying listed quotes
========================================*/
?>

.questions-compressed-date {
        width: 150px !important;
        margin-right: 10px;
}

.questions .answer_questions {
	background-color: #eeeeff;
	margin-bottom: 15px;
	border-bottom: 1px solid #aaaaaa;
	padding-right:50px;
}

.questions .comment {
	background-color: #00ffff;
	margin-bottom: 15px;
	border-bottom: 1px solid #aaaaaa;
	padding-right:50px;
}

.questions .question {
	background-color: #eee;
	margin-bottom: 15px;
	border-bottom: 1px solid #aaaaaa;
	padding-right:50px;
}

.question_open a{
	display:block;
	height:20px;
	width:20px;
	float:left;
	margin-top:3px;
	background: transparent url('<?php echo elgg_get_site_url(); ?>mod/questions/graphics/openclosequestion_20.png') no-repeat top right;
	background-position: right -20px;
}

.question_closed a{
	display:block;
	height:20px;
	width:20px;
	float:left;
	margin-top:3px;
	background: transparent url('<?php echo elgg_get_site_url(); ?>mod/questions/graphics/openclosequestion_20.png') no-repeat top right;
}

.question_open a:hover {
	background: transparent url('<?php echo elgg_get_site_url(); ?>mod/questions/graphics/openclosequestion_20.png') no-repeat top right;
}

.question_closed a:hover {
	background-position: right -20px;
}

.questions_icon {
	float:left;
	margin:3px 0 0 3px;
	padding:0;
}

.questions h3 {
	font-size: 150%;
	margin-bottom: 5px;
}

.questions p {
	margin: 0 0 5px 0;
}

.questions .strapline {
	margin: 0 0 0 35px;
	padding:0;
	color: #aaa;
	line-height:1em;
}
.questions p.tags {
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/icon_tag.gif) no-repeat scroll left 2px;
	margin:0 0 0 35px;
	padding:0pt 0pt 0pt 16px;
	min-height:22px;
}

.questions_body {
	font-style:italic;
	margin-left: 35px;
}

.questions_body img[align="left"] {
	margin: 10px 10px 10px 0;
	float:left;
}
.questions_body img[align="right"] {
	margin: 10px 0 10px 10px;
	float:right;
}

.questions_title {
	font-size:14px;
	font-weight:bold;
}

.questions_warnings {
	color: red;
	font-size:14px;
	font-weight:bold;
}

.open_title_questions {
	color: green;
}

.closed_title_questions {
	color: red;
}

.pending_title_questions {
	color: blue;
}

.questions_rate a {
	text-decoration: none;
	font-weight: bold;
}

.questions_links {
	float:right;
	font-size:10px;
}

#questions_widget_layout .questions_widget_galleryview {
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	background: white;
	margin:0 0 5px 0;
}


.group_questions_widget {
        margin:0 0 20px 0;
        padding: 0 0 5px 0;
        background:white;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
}
.group_questions_widget .search_listing {
        border: 2px solid #cccccc;
}


<?php
/*END CSS FOR LISTING QUESTIONS*/

/*==============================
   End Questions Plugin
================================*/
?>
