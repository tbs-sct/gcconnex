<?php
/*
* Community picker
*/

//Testing - using a plugin setting for array, found out you can't save arrays in plugin settings.
//$community_array = elgg_get_plugin_setting('community_list', 'gc_tags');
//Communities of practice
$community_array = array(
    'atip'=> elgg_echo('gctags:community:atip'),
    'communications'=> elgg_echo('gctags:community:communications'),
    'evaluators'=> elgg_echo('gctags:community:evaluators'),
    'financial'=> elgg_echo('gctags:community:financial'),
    'hr'=> elgg_echo('gctags:community:hr'),
    'im'=> elgg_echo('gctags:community:im'),
    'it'=> elgg_echo('gctags:community:it'),
    'auditors'=> elgg_echo('gctags:community:auditors'),
    'matmanagement'=> elgg_echo('gctags:community:matmanagement'),
    'policy'=> elgg_echo('gctags:community:policy'),
    'procurement'=> elgg_echo('gctags:community:procurement'),
    'realproperty'=> elgg_echo('gctags:community:realproperty'),
    'regulators'=> elgg_echo('gctags:community:regulators'),
    'security'=> elgg_echo('gctags:community:security'),
    'service'=> elgg_echo('gctags:community:service'),
    'science'=> elgg_echo('gctags:community:science'),
    'allps' => elgg_echo('gctags:community:allps'),
);



if (isset($vars['entity'])) {
	$value = $vars['entity']->audience;
}
//Class audience-select is used with selectize.js
$communities_input = elgg_view('input/select', array(
    'name' => 'audience',
    'id' => 'audience',
    'options_values' => $community_array,
    'multiple' => 'multiple',
    'value' => $value,
    'class' => 'audience-select',
));
$help_link = elgg_view('output/url', array(
    'text' => '[?] <span class="wb-invisible">'.elgg_echo("gctags:help:community").'</span>',
    'href' => '/community-help#what-are-communities',
    'title' => elgg_echo("gctags:help:community"),
    'target' => '_blank',
));

echo elgg_format_element('label', array('for' => 'audience'), elgg_echo('gctags:label:community'));
echo elgg_format_element('span',array('class' => 'mrgn-lft-sm'),$help_link);
echo elgg_format_element('p', array('class' => 'timeStamp'), elgg_echo('gctags:helpertext:community') .' + '.$value);
echo $communities_input;