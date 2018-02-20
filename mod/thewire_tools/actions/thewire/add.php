<?php
/**
 * Action for adding a wire post
 *
 */

// don"t filter since we strip and filter escapes some characters
$body = get_input("body", "", false);
$body = strip_tags($body);

$access_id = (int) get_input("access_id", ACCESS_PUBLIC);
$method = "site";
$parent_guid = (int) get_input("parent_guid");
$reshare_guid = (int) get_input("reshare_guid");

// make sure the post isn't blank
if (empty($body)) {
	register_error(elgg_echo("thewire:blank"));
	forward(REFERER);
}

/// we want to trigger our custom event handler
if (elgg_is_active_plugin('thewire_images')) {
	elgg_unregister_event_handler('create','object','cp_create_notification');
	elgg_unregister_event_handler('create','object','thewire_tools_create_object_event_handler');
}

$guid = thewire_tools_save_post($body, elgg_get_logged_in_user_guid(), $access_id, $parent_guid, $method, $reshare_guid);
if (!$guid) {
	register_error(elgg_echo("thewire:error"));
	forward(REFERER);
}

// if reply, forward to thread display page
if ($parent_guid) {
	$parent = get_entity($parent_guid);
	forward("thewire/thread/$parent->wire_thread");
}

system_message(elgg_echo("thewire:posted"));

// send notifications when a user shares your content on the wire
if ($reshare_guid || $reshare_guid > 0) {
	$content_owner = get_entity($reshare_guid)->getOwnerEntity();
	$entity = get_entity($reshare_guid);
	$wire_entity = get_entity($guid);

	if ($entity->getType() == 'group'){
		$entity->title = $entity->name;
	}

	$to_recipients = $entity->getOwnerEntity();

	// if user share his own stuff, dont send the notification
	if ($to_recipients->guid != get_loggedin_user()->guid) { 

		// if cp notification plugin is active, use that for notifications
		if (elgg_is_active_plugin('cp_notifications')) {

			$message = array(
				'cp_msg_type' => 'cp_wire_share',
				'cp_recipient' => $entity->getOwnerEntity(),
				'cp_shared_by' => elgg_get_logged_in_user_entity(),
				'cp_content_reshared' => $entity,
				'cp_content' => $wire_entity,
				'cp_wire_url' => $wire_entity->getURL(),
			);
			elgg_trigger_plugin_hook('cp_overwrite_notification','all',$message);

		}
	}
}


forward(REFERER);



