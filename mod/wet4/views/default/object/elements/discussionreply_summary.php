<?php
/**
 * Object summary
 *
 * Sample output
 * <ul class="elgg-menu elgg-menu-entity"><li>Public</li><li>Like this</li></ul>
 * <h3><a href="">Title</a></h3>
 * <p class="elgg-subtext">Posted 3 hours ago by George</p>
 * <p class="elgg-tags"><a href="">one</a>, <a href="">two</a></p>
 * <div class="elgg-content">Excerpt text</div>
 *
 * @uses $vars['entity']    ElggEntity
 * @uses $vars['title']     Title link (optional) false = no title, '' = default
 * @uses $vars['metadata']  HTML for entity menu and metadata (optional)
 * @uses $vars['subtitle']  HTML for the subtitle (optional)
 * @uses $vars['content']   HTML for the entity content (optional)
 *
 * GC_MODIFICATION
 * Description: layout changes / added wet and bootstrap classes
 * Author: GCTools Team
 */

$entity = $vars['entity'];

$title_link = elgg_extract('title', $vars, '');
if ($title_link === '') {
	if (isset($entity->title)) {
		$text = $entity->title;
	} else {
		$text = $entity->name;
	}
	$params = array(
		'text' => elgg_get_excerpt($text, 100),
		'href' => $entity->getURL(),
		'is_trusted' => true,
	);
	$title_link = elgg_view('output/url', $params);
}

$metadata = elgg_extract('metadata', $vars, '');
$subtitle = elgg_extract('subtitle', $vars, '');
$content = elgg_extract('content', $vars, '');

//This tests to see if you are looking at a group list and does't outpout the subtitle variable here, It's called at the end of this file
if($entity->getType() == 'group'){
   echo '';
}else{
  echo "<span class=\" mrgn-bttm-sm timeStamp\">$subtitle</span>";   
}

if ($metadata) {
	echo $metadata;
}

echo elgg_view('object/summary/extend', $vars);

if ($content) {
	echo "<div class=\"elgg-content mrgn-tp-sm  clearfix\">$content</div>";
}

if($entity->getType() == 'group'){
   echo "<div class=\" mrgn-bttm-sm mrgn-tp-md timeStamp clearfix\">$subtitle</div>"; 

}

