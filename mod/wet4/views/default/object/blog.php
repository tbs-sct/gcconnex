<?php
/**
 * View for blog objects
 *
 * @package Blog
 */

$lang = get_current_language();
$full = elgg_extract('full_view', $vars, FALSE);
$blog = elgg_extract('entity', $vars, FALSE);
//$simple = 'simple string';
$simple_fr = $blog->description2;
$simple_en = $blog->description;


if (!$blog) {
	return TRUE;
}

$owner = $blog->getOwnerEntity();
$container = $blog->getContainerEntity();
$categories = elgg_view('output/categories', $vars);

if($blog->excerpt3){
	$excerpt = gc_explode_translation($blog->excerpt3,$lang);
}else{
	$excerpt = $blog->excerpt;
}

if (empty($excerpt)) {
	if($blog->description3){
		$excerpt = elgg_get_excerpt(gc_explode_translation($blog->description3, $lang));
	}else{
		$excerpt = elgg_get_excerpt($blog->description);
	}
}

//test to see if it is widget view
if(elgg_get_context() !== 'widgets'){
$owner_icon = elgg_view_entity_icon($owner, 'medium');
}else{
   
   $owner_icon = elgg_view_entity_icon($owner, 'small'); 
    
}

$owner_link = elgg_view('output/url', array(
	'href' => "blog/owner/$owner->username",
	'text' => $owner->name,
));
$author_text = elgg_echo('byline', array($owner_link));

// add container text
if (elgg_instanceof($container, "group") && ($container->getGUID() !== elgg_get_page_owner_guid())) {
	$params = array(
		'href' => $container->getURL(),
		'text' => gc_explode_translation($container->title3, $lang),
		'is_trusted' => true
	);
	$group_link = elgg_view('output/url', $params);
	$author_text .= " " . elgg_echo('river:ingroup', array($group_link));
}

$tags = elgg_view('output/tags', array('tags' => $blog->tags));
$date = elgg_view_friendly_time($blog->time_created);

$info_class = "";
$blog_icon = "";
$title = "";

// show icon
if (!empty($blog->icontime)) {
	$params = $vars;
	$params["plugin_settings"] = true;

	$blog_icon = elgg_view_entity_icon($blog, "dummy", $params);
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $blog,
	'handler' => 'blog',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz list-inline',
));

$subtitle = "$author_text $date $categories";

function removeTag($content, $tagName) {
    $dom = new DOMDocument();
    $dom->loadXML($content);

    $nodes = $dom->getElementsByTagName($tagName);

    while ($node = $nodes->item(0)) {
        $replacement = $dom->createDocumentFragment();
        while ($inner = $node->childNodes->item(0)) {
            $replacement->appendChild($inner);
        }
        $node->parentNode->replaceChild($replacement, $node);
    }

    return $dom->saveHTML();
}

$content = '<span>This <b>is</b> an <span>example</span></span>';

$simple_en2 =  removeTag($simple_en, 'span'); 
$simple_fr2 =  removeTag($simple_fr, 'span'); 

$simple_en4 = preg_replace( "/\r|\n/", "", $simple_en2 );
$simple_fr4 = preg_replace( "/\r|\n/", "", $simple_fr2 );

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
    //$metadata = '';
}
$test = 'Bacon ipsum dolor amet boudin flank short loin shank sirloin alcatra shankle t-bone fatback ball tip porchetta shoulder prosciutto. Sirloin venison turkey meatball salami fatback capicola bresaola ball tip jowl. Tail pork chop turkey kielbasa alcatra biltong. Turducken tongue ham shoulder, beef t-bone tenderloin venison frankfurter shankle short loin strip steak. Salami leberkas bresaola shoulder ball tip, capicola kevin drumstick rump shank. Turkey pork chop shoulder fatback drumstick corned beef pig prosciutto venison biltong jerky chicken boudin filet mignon. Sirloin tri-tip short ribs filet mignon sausage cow brisket alcatra tenderloin.';
// Show blog
if ($full) {
	// full view

	// identify available content
if(($blog->description2) && ($blog->description)){
	echo'<div id="change_language">';
	if (get_current_language() == 'fr'){
		
					?>			

<a id="indicator_language_en" href="#"><label class="testClass hidden" ><?php echo $blog->description; ?>'</label><span id="indicator_text" onclick="change_en('<?php echo $simple_en4; ?>', '<?php echo $simple_fr4; ?>');">Content available in english</span></a>



<?php


		
	}else{
				
		?>			

<a id="indicator_language_fr" href="#"><label class="testClass hidden" ><?php echo $blog->description2; ?>'</label><span id="indicator_text" onclick="change_fr('<?php echo $simple_en4; ?>', '<?php echo $simple_fr4; ?>');">Contenu disponible en français</span></a>



<?php

			
	}
	echo'</div>';
}

echo'<div id="output"></div>';

if($blog->description3){
	$blog_descr = gc_explode_translation($blog->description3, $lang);
}else{
	$blog_descr = $blog->description;
}
 	$body = elgg_view('output/longtext', array(
		'value' => $blog_descr,
		'class' => 'blog-post',
	));

	$header = elgg_view_title($blog->title);

	$params = array(
		'entity' => $blog,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view("object/elements/full", array(
        'entity' => $blog,
		"summary" => $summary,
		"icon" => $owner_icon,
		"body" => $blog_icon . $body,
	));

    echo '<div id="group-replies" class="elgg-comments mrgn-rght-md mrgn-lft-md clearfix">';
    
} else {

	// identify available content
if(($blog->description2) && ($blog->description)){
		
			echo'<span style="padding-left:90%;"><i class="fa fa-language fa-2x" aria-hidden="true"></i></span>';
	
}
	// how to show strapline
	if (elgg_in_context("listing")) {
		$excerpt = "";
		$blog_icon = "";
	} elseif (elgg_in_context("simple")) {
		$owner_icon = "";
		$tags = false;
		$subtitle = "";
		$title = false;

		// prepend title to the excerpt
		$title_link = "<h3>" . elgg_view("output/url", array("text" => $blog->title, "href" => $blog->getURL())) . "</h3>";
		$excerpt = $title_link . $excerpt;

		// add read more link
		if (substr($excerpt, -3) == "...") {
			$read_more = elgg_view("output/url", array("text" => elgg_echo("blog_tools:readmore"), "href" => $blog->getURL()));
			$excerpt .= " " . $read_more;
		}
	} elseif (elgg_get_plugin_setting("listing_strapline", "blog_tools") == "time") {
		$subtitle = "";
		$tags = false;

		$excerpt = date("F j, Y", $blog->time_created) . " - " . $excerpt;
	}

	// prepend icon
	$excerpt = $blog_icon . $excerpt;

	// brief view
	$params = array(
		'entity' => $blog,
		'title' => $title,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);

	$params = $params + $vars;

	$list_body = elgg_view('object/elements/summary', $params);
    

	echo elgg_view_image_block($owner_icon, $list_body);

	

}
?>

<!-- 
/*function change_fr(e){
	var label = e;
	$.ajax(
    {
        type : "post",
        dataType: "html",
        cache: false,
        success : function(response)
        {
        	$(".blog-post").html(label);
        }
    });
    change_title_en();
};

function change_en(e){
  	var label = e;
	$.ajax(
    {
        type : "post",
        dataType: "html",
        cache: false,
        success : function(response)
        {
            $(".blog-post").html(label);
        }
    });
	change_title_fr();
};

function change_title_fr(){

	var link_available = '<a id="indicator_language_fr" onclick="change_fr((this.textContent || this.innerText))"  href="#"><label class="testClass hidden" ><?php echo $simple_fr; ?></label><span id="indicator_text">Contenu disponible en français</span></a>';
	$("#change_language").html(link_available)
}

function change_title_en(){

	 var link_available = '<a id="indicator_language_en" onclick="change_en((this.textContent || this.innerText))" href="#"><label class="testClass hidden" ><?php echo $simple_en; ?></label><span id="indicator_text">Content available in english</span></a>';
	$("#change_language").html(link_available)
}*/ -->

<?php