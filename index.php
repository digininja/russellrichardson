<?php
require_once ("application/includes/constants.inc.php");
require_once ("application/classes/banner.class.php");
require_once ("application/classes/banner_list.class.php");
require_once ("application/classes/case_study_list.class.php");

$banner_list = new banner_list();
$banner_list->set_order_by ("id");
$banner_list->set_homepage();
$banners = $banner_list->do_search();

$meta_title = "Shredding Services - Paper, Textiles & Confidential Waste.";
$meta_description = "UK wide mobile shredding trucks and collection for your on and off-site shredding needs. Backed by our CCTV secure data storage and archiving facility.";

include('parts/header.php');

?>

<main class="site-content site-content--homepage" data-page="homepage">

	<svg class="hidden pos--abs" width="0" height="0">
	    <defs>
	      <clipPath id="curve" clipPathUnits="objectBoundingBox">
	        <path transform="scale(0.000626, 0.00237)" d="M0,0V360s466,60,800,60,800-60,800-60V0Z"/>
	      </clipPath>

	      <clipPath id="curve-m" clipPathUnits="objectBoundingBox">
	        <style>
	          .curve-m {transform: scale(0.0009, 0.00237) translateX(-250px);}
	        </style>
	        <path class="curve-m" d="M0,0V360s466,60,800,60,800-60,800-60V0Z"/>
	      </clipPath>

	      <clipPath id="curve-s" clipPathUnits="objectBoundingBox">
	        <style>
	          .curve-s {transform: scale(0.0015, 0.00237) translateX(-450px);}
	        </style>
	        <path class="curve-s" d="M0,0V360s466,60,800,60,800-60,800-60V0Z"/>
	      </clipPath>
	    </defs>
	  </svg>


<?php

if (count($banners)) {

		$first = true;

		echo '<section class="banner-slider pos--rel">
						<div class="banner-slider__slides pos--rel z--1">
      				<ul class="list--blank">';

							foreach($banners as $banner){

								$jpgExists = file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/banners/banner" . $banner->get_id() . ".jpg");
								$pngExists = file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/banners/banner" . $banner->get_id() . ".png");
								$image = ($jpgExists ? "/userfiles/banners/banner" . $banner->get_id() . ".jpg" : '');

								if(!$jpgExists && $pngExists){
									$image = "/userfiles/banners/banner" . $banner->get_id() . ".png";
								}
?>

									<li class="banner-slider__slide <?= ($first ? 'active' : '') ?> pos--abs z--1">
										<div class="container container--large pos--rel z--3">
											<h2 class="header header--large font--white"><?=clean_display_string ($banner->get_headline())?></h2>
											<p class="bodyfont bodyfont--large font--white"><?=clean_display_string ($banner->get_sub_headline())?></p>
										</div>
										<div class="banner-slider__overlay pos--abs z--2"></div>
										<div class="banner-slider__img pos--abs z--1" style="background-image:url('<?= $image ?>');"></div>
									</li>


<?php
		$first = false;

		}

					echo '</ul>';
				echo '</div>';
		echo '</section>';

}
?>

<div class="container container--large inline-hack">

    <section class="main-content main-content--home">

      <div class="hp-intro mb--80px">
        <h1 class="font--34px font--grey weight--black">
          <a class="font--linkblue" href="/service/shredding">Shredding</a>, <a class="font--linkblue" href="/service/archiving">Archiving</a> & <a class="font--linkblue" href="/service/recycling">Recycling</a> that suits <span class="font--black">YOU</span>
        </h1>

        <ol class="number-list list--blank inline-hack no-last-margin">
          <li class="number-list__item">
            <span class="number-list__number font--22px font--black weight--black">1</span>
            <p class="number-list__text bodyfont bodyfont--large">We say <strong>‘YES’</strong> when bigger shredding firms say ‘NO’. We are perfectly happy to tailor our shredding and recycling services around your needs.</p>
          </li>
          <li class="number-list__item">
            <span class="number-list__number font--22px font--black weight--black">2</span>
            <p class="number-list__text bodyfont bodyfont--large">We are price competitive and offer <strong>flexible pricing structures</strong>. Pay how its suits you best – by box, weight or sack.</p>
          </li>
          <li class="number-list__item">
            <span class="number-list__number font--22px font--black weight--black">3</span>
            <p class="number-list__text bodyfont bodyfont--large">We offer <strong>flexible on site pick up</strong> and are happy to help with any clearance problems you may have. </p>
          </li>
        </ol>

        <a class="video-btn pos--rel" href="https://www.youtube.com/watch?v=FOxtL25iUtM" data-lity>
          <?php include("assets/svgs/play-btn.svg"); ?>
          <p class="font--14px font--grey weight--black pos--abs center--y">Watch Our Video</p>
        </a>

        <ul class="hp-intro__credentials list--blank">
          <li class="hp-intro__credential">
            <img class="hp-intro__credential-logo" src="/assets/images/home/credentials_bsia.png" alt="" />
          </li>
          <li class="hp-intro__credential">
            <img class="hp-intro__credential-logo" src="/assets/images/home/credentials_isoqar_@x2.png" alt="" />
          </li>
        </ul>



      </div>

      <div class="trusted-by mb--40px">
        <h3 class="header header--large font--white text-shadow">Customer Help & Advice</h3>
        <p class="bodyfont bodyfont--large font--white text-shadow">Our new advice centre is full of help and advice for your shredding, archiving and recycling questions.</p>
        <a class="bodylink font--white" href="/advice-centre.php">Visit our advice centre   ></a>
      </div>

      <div class="national-reach">
        <img src="/assets/images/global/national-map.png" class="national-reach__map" alt="Russell Richardson National Reach Map">

        <div class="national-reach__text">
          <h3 class="header font--grey">National Reach, <span class="font--black">Local Focus</span></h3>
          <p class="bodyfont">With a varied fleet of mobile shredding trucks and collection vehicles operating daily throughout the UK, Russell Richardson offers a flexible, reliable service.</p>
          <p class="bodyfont">Operated by highly trained and security vetted staff, your confidential waste and data are in safe hands.</p>
          <a class="bodylink" href="/locations.php">View Locations We Cover</a>
        </div>
      </div>

    </section>

    <?php

			include('parts/sidebars/general.php');

		 ?>

  </div>
</main>

<?php
include('parts/footer.php');
?>
