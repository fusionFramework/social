<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'topics' => array(
		'total_items' => 20, // How many topics are shown in a category per page
		'class' => 'pagination pagination-sm' //HTML class for the pagination
	),
	'replies' => array(
		'total_items' => 15,// How many replies are shown in a topic per page
		'class' => 'pagination pagination-sm' //HTML class for the pagination
	),
	'guest_view' => true, // Can guests view the forum?
	'allow_owner' => array(
		'replies' => array(
			'edit' => true, // Allow users to edit their own replies
			'delete' => true // Allow users to delete their own replies
		),
		'topics' => array(
			'edit' => true, // Allow users to edit their own topics
			'delete' => true // Allow users to delete their own topics
		)
	)
);