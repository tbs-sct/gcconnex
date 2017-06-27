<?php
	// Set context and title
	elgg_set_context('freshdesk');

	$title = elgg_echo('Freshdesk');

	$body = elgg_view_layout('one_column', array(
		'title' => $title,
		'content' => elgg_view('freshdesk/embed')
	));

	echo elgg_view_page($title, $body);
?>
