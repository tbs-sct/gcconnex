<?php
/**
 * Conditional banner that prompts users to identify their user type
 * 
 * Nick - piet0024
 */

$user = elgg_get_logged_in_user_entity();
if($user && $user->user_type == ""){
  $update_link = elgg_view('output/url',array(
    'text' => 'Update Organisation',
    'href'=> 'updateorg?forward='.current_page_url(),
    'class' => 'pull-right',
  ));
  $format_body = elgg_format_element('div',['class' => 'container', 'style' => 'padding:15px 0;'], '<i class="fa fa-lg fa-beer" style="color:red;"></i> Sweet banner bro. I bet you really want to update your org. I hope you see this...' .$update_link);
  echo elgg_format_element('div',['class'=>'panel mrgn-bttm-0', 'style' =>'margin-bottom:0 !important'], $format_body);
}
