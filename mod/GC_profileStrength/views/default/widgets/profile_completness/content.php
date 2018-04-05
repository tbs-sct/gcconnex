<?php

/**
 * Profile Strength
 *
 * @author Mathieu Blondin Ethan Wallace github.com/ethanWallace
 */

$user_guid = elgg_get_logged_in_user_guid();
$userEnt = get_user ( $user_guid );

//avatar
if($userEnt->getIconURL() !=  elgg_get_site_url() . '_graphics/icons/user/defaultmedium.gif'){
    $avIcon = '<i class="fa fa-check text-primary"><span class="wb-inv">' . elgg_echo('ps:avatar:added') . '</span></i>';
    $avTotal = 100;
}else{
    $avIcon = '<i class="fa fa-exclamation-triangle text-danger"><span class="wb-inv">' . elgg_echo('ps:avatar:notadded') . '</span></i>';
    $avTotal = 0;
}

//About me
if($userEnt->description){
    $aboutIcon = '<i class="fa fa-check text-primary"><span class="wb-inv">' . elgg_echo('ps:about:added') . '</span></i>';
    $aboutTotal = 100;
}else{
    $aboutIcon = '<i class="fa fa-exclamation-triangle text-danger"><span class="wb-inv">' . elgg_echo('ps:about:notadded') . '</span></i>';
    $aboutTotal = 0;
}

//basic profile
$basicCount = 0;

if($userEnt->department){
    $basicCount += 20;
}
if($userEnt->job){
    $basicCount += 20;
}
if($userEnt->location || $userEnt->addressString || $userEnt->addressStringFr){
    $basicCount += 20;
}
if($userEnt->email){
    $basicCount += 20;
}
if($userEnt->phone || $userEnt->mobile){
    $basicCount += 20;
}

//education
if(count($userEnt->education) >= 1){
    $eduCount = 100;
} else {
    $eduCount = 0;
}

//work experience
if(count($userEnt->work) >= 1){
    $workCount = 100;
} else {
    $workCount = 0;
}

//skills
if(count($userEnt->gc_skills) >= 3){
    $skillCount = 100;
} else {
    $skillCount = round(count($userEnt->gc_skills)/3*100);
}

//overall total
$complete = round(($skillCount + $workCount + $eduCount + $basicCount + $aboutTotal + $avTotal)/6);

//set up profile strength metadata
$userEnt->profilestrength = $complete;

echo '<script src="'.elgg_get_site_url().'mod/GC_profileStrength/views/default/widgets/profile_completness/js/circliful.min.js"></script>';
echo '<link rel="stylesheet" href="'.elgg_get_site_url().'mod/GC_profileStrength/views/default/widgets/profile_completness/css/circliful.css">';
?>

<script>$(document).ready(function () {
    $('#complete').circliful({
        animation: 1,
        animationStep: 5,
        iconPosition: 'top',
        foregroundBorderWidth: 15,
        backgroundBorderWidth: 15,
        percent: <?php echo $complete;?>,
        fontColor: '#055959',
        textSize: 28,
        percentageTextSize: 40,
        foregroundColor: "#055959",
        iconColor: '#055959',
        targetColor: '#055959',
    });
});
</script>

<?php
//render results
if($userEnt->profilestrength != 100){
    echo '<div class="col-md-push-3 col-md-6"><div aria-hidden="true" id="complete"></div></div>';
    echo '<span class="wb-inv">'.elgg_echo('ps:youhave', array($complete)).'</span>';
    echo '<div class="clearfix"></div>';
    echo '<details><summary class="bg-primary">'.elgg_echo('ps:details').'</summary>';

    //about me / Avatar

    echo '<ul class="list-unstyled colcount-sm-2 colcount-md-2 colcount-lg-2 mrgn-tp-sm">';
    echo '<li><strong>Avatar</strong>';
    echo $avIcon;
    echo '</li>';
    echo '<li><strong>'.elgg_echo('profile:aboutme').'</strong>';
    echo $aboutIcon;
    echo '</li></ul>';

    //progress bars

    echo '<ul class="list-unstyled colcount-sm-1 colcount-md-1 colcount-lg-1">';
    echo '<li><strong style="width:100%;">'. elgg_echo("ps:basicprofile").'</strong> <span style="display: block; width:100%;"><progress class="progress-bar-striped" max="100" value="'.$basicCount .'"><span class="wb-inv">' . $basicCount . '%</span></progress><span>' . $basicCount . '%</span></span></li>';
    echo '<li><strong style="width:100%;">'. elgg_echo('profile:skills') .'</strong> <span style="display: block; width:100%;"><progress class="progress-bar-striped" max="100" value="'.$skillCount .'"><span class="wb-inv">' . $skillCount . '%</span></progress><span>' . $skillCount . '%</span></span></li>';
    echo '<li><strong style="width:100%;">'. elgg_echo('ps:education') .'</strong> <span style="display: block; width:100%;"><progress class="progress-bar-striped" max="100" value="'.$eduCount .'"><span class="wb-inv">' . $eduCount . '%</span></progress><span>' . $eduCount . '%</span></span></li>';
    echo '<li><strong style="width:100%;">'. elgg_echo('ps:work') .'</strong> <span style="display: block; width:100%;"><progress class="progress-bar-striped" max="100" value="'.$workCount .'"><span class="wb-inv">' . $workCount . '%</span></progress><span>' . $workCount . '%</span></span></li>';
    echo '</ul>';
    echo '<div class="clearfix"></div></details>';
} else {
    /*Strength is at 100%*/
    echo '<div class="text-center">';
    echo elgg_view('output/img', array(
            'src' => 'mod/GC_profileStrength/graphics/completeBadgeLvl01.png',
            'alt' => elgg_echo('badge:complete:achieved:1', array($userEnt->name)),
            'title' => elgg_echo('badge:complete:achieved:1', array($userEnt->name)),
            'style' => 'width:125px;'
            ));
    echo '<p>'.elgg_echo('ps:all-star').'</p>';
    echo '</div>';

    if($userEnt->opt_in_missions == 'gcconnex_profile:opt:yes') {
	    $OptedIn = true;
	}
	if($userEnt->opt_in_swap == 'gcconnex_profile:opt:yes') {
	    $OptedIn = true;
	}
	if($userEnt->opt_in_mentored == 'gcconnex_profile:opt:yes') {
	    $OptedIn = true;
	}
	if($userEnt->opt_in_mentoring == 'gcconnex_profile:opt:yes') {
        $OptedIn = true;
	}
	if($userEnt->opt_in_shadowed == 'gcconnex_profile:opt:yes') {
	    $OptedIn = true;
	}
	if($userEnt->opt_in_shadowing == 'gcconnex_profile:opt:yes') {
        $OptedIn = true;
	}
	if($userEnt->opt_in_peer_coached == 'gcconnex_profile:opt:yes') {
	    $OptedIn = true;
	}
	if($userEnt->opt_in_peer_coaching == 'gcconnex_profile:opt:yes') {
	    $OptedIn = true;
	}

    //focus onto the micromission section
    if(elgg_plugin_exists('missions') && elgg_is_active_plugin('missions') && $OptedIn==false){
        echo '<p class="pull-left" style="width: 70%;">'.elgg_echo('ps:optingin').'</p>';

        if(!strpos($currentPage,'profile')){
            echo '<a href="'.elgg_get_site_url().'profile/'.$userEnt->username.'#edit-opt-in" class="btn btn-primary mrgn-tp-sm pull-right">'. elgg_echo('ps:optin').'</a>';
        }else{
            echo '<a href="#edit-opt-in" class="btn btn-primary mrgn-tp-sm pull-right">'. elgg_echo('ps:optin').'</a>';
        }
    }
}

?>
