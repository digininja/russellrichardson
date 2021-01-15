<?php

require_once ("application/includes/constants.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/classes/category_l1.class.php");

if (array_key_exists ("url", $_GET)) {
	$url = ($_GET['url']);
	$category_l3 = new category_l3();
	if (!$category_l3->load_by_url ($url)) {
		header ("location: /services.php");
		exit;
	}
} elseif (array_key_exists ("id", $_GET) && is_numeric ($_GET['id'])) {
	$id = intval ($_GET['id']);
	$category_l3 = new category_l3();
	if (!$category_l3->load_if_exists ($id)) {
		header ("location: /services.php");
		exit;
	}
} else {
	header ("location: /services.php");
	exit;
}

$meta_title = $category_l3->get_meta_title();
$meta_description = $category_l3->get_meta_description();
$category_l2 = $category_l3->get_parent();
$category_l1 = $category_l2->get_parent();

$menu_location = "service_" . $category_l1->get_id();

include('parts/header.php');

setlocale (LC_ALL, "en_GB");

require_once ("application/classes/category_l2.class.php");
require_once ("application/classes/category_l2.class.php");
require_once ("application/classes/category_l3.class.php");
require_once ("application/classes/image.class.php");

$banner = $category_l3->get_banner();

?>

<main class="site-content" data-page="service-single">
  <div class="container container--large inline-hack">


    <nav class="breadcrumb">
      <ul class="breadcrumb__nav list--blank">
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/">Home</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/services.php">Services</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/service/<?=clean_display_string ($category_l1->get_url())?>"><?=clean_display_string ($category_l1->get_name())?></a>
        </li>
				<li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/service/2/<?=clean_display_string ($category_l2->get_url())?>"><?=clean_display_string ($category_l2->get_name())?></a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller"><?=clean_display_string ($category_l3->get_name())?></li>
      </ul>
      <h1 class="breadcrumb__header header"><?=clean_display_string ($category_l3->get_name())?></h1>
    </nav>


    <section class="main-content main-content--internal">

      <div class="image-text-box mb--40px">
        <div class="image-text-box__box pos--rel">
          <div class="image-text-box__image" style="background-image:url('<?= (!is_null($banner) ? $banner->get_src() : '') ?>')"></div>
          <div class="image-text-box__text no-edge-margin">
            <p class="bodyfont font--black"><?=clean_display_string ($category_l3->get_summary())?></p>
          </div>
        </div>
      </div>


      <ul class="icon-highlights no-edge-margin list--blank">
		<?php
		if ($category_l3->get_onsite_shredding()) {
			?>
			<li class="icon-highlights__item pos--rel">
			  <?php include "assets/svgs/small/icon-tick.svg" ?>
			  <p class="bodyfont bodyfont--small">On-Site Shredding</p>
			</li>
		<?php
		}
		if ($category_l3->get_offsite_shredding()) {
			?>
			<li class="icon-highlights__item pos--rel">
			  <?php include "assets/svgs/small/icon-tick.svg" ?>
			  <p class="bodyfont bodyfont--small">Off-Site Collection</p>
			</li>
		<?php
		}
		if ($category_l3->get_adhoc_collections()) {
			?>
			<li class="icon-highlights__item pos--rel">
			  <?php include "assets/svgs/small/icon-tick.svg" ?>
			  <p class="bodyfont bodyfont--small">Adhoc Collection</p>
			</li>
		<?php
		}
		if ($category_l3->get_regular_collections()) {
		?>
			<li class="icon-highlights__item pos--rel">
			  <?php include "assets/svgs/small/icon-tick.svg" ?>
			  <p class="bodyfont bodyfont--small">Regular Collections</p>
			</li>
			<?php
		}
		if ($category_l3->get_containers_provided()) {
			?>
			<li class="icon-highlights__item pos--rel">
			  <?php include "assets/svgs/small/icon-tick.svg" ?>
			  <p class="bodyfont bodyfont--small">Containers Provided</p>
			</li>
			<?php
		}
		?>
      </ul>


      <div class="text-other-content mt--30px mb--40px no-edge-margin">


        <div class="text-other-content__main no-edge-margin">

          <?= $category_l3->get_body(); ?>

        </div>

        <div class="text-other-content__other no-edge-margin">

					<?php

					$videoUrl = $category_l3->get_video_url();

					if(!empty($videoUrl)){

					?>

						<a class="video-btn pos--rel" href="<?=clean_display_string ($category_l3->get_video_url())?>" data-lity>
	            <?php include("assets/svgs/play-btn.svg"); ?>
	            <p class="font--14px font--grey weight--black pos--abs center--y">Watch Our Video</p>
	          </a>
						<?php
					}
					?>


          <div class="image-gallery no-line">
            <h6 class="header font--20px">Image Gallery</h6>

						<?php
							$first = true;
							$gallery_images = $category_l3->get_gallery_images();
							if(count($gallery_images)){

								foreach ($gallery_images as $image) {
									?>

									<?php
										if($first){
									?>

										<a class="image-gallery__img image-gallery__img--main pos--rel" style="background-image:url('<?=clean_display_string ($image->get_thumbnail_src())?>')" data-lightbox="image-gallery" href="<?=clean_display_string ($image->get_src())?>" data-lity></a>

									<?php

										$first = false;
										echo '<div class="image-gallery__small">';
										continue;



										}
									?>
											<a class="image-gallery__img image-gallery__img--small" style="background-image:url('<?=clean_display_string ($image->get_thumbnail_src())?>')" href="<?=clean_display_string ($image->get_src())?>" data-lity></a>

									<?php
								}
								echo '</div>';
							}
						?>


          </div>

        </div>



      </div>


	<?php
	$linked_category_l3s = $category_l3->get_linked_category_l3s();
	if (count ($linked_category_l3s) > 0) {
		?>
		  <div class="additional-links">
			<h6 class="header font--24px">Related Services</h6>

			<ul class="additional-links__holder list--blank">
				<?php
				foreach ($linked_category_l3s as $linked_category_l3) {
					?>
					<li class="additional-links__item">
						<a class="additional-links__link font--linkblue no-line" href="/service/3/<?=clean_display_string ($linked_category_l3->get_url())?>">
							<p class="header font--20px font--linkblue"><?=clean_display_string ($linked_category_l3->get_name())?></p>
						</a>
					</li>
					<?php
				}
				?>
			</ul>
		  </div>
		  <?php
	}
	?>

	<?php
	$advice_l3s = $category_l3->get_advice_l3s();
	if (count ($advice_l3s) > 0) {
		?>
		  <div class="additional-links mt--30px">
			<h6 class="header font--24px">Further Resources</h6>

			<ul class="additional-links__holder list--blank">
				<?php
				foreach ($advice_l3s as $advice_l3) {
					?>
					<li class="additional-links__item">
						<a class="additional-links__link font--linkblue no-line" href="/advice/3/<?=clean_display_string ($advice_l3->get_url())?>">
							<p class="header font--20px font--linkblue"><?=clean_display_string ($advice_l3->get_name())?></p>
						</a>
					</li>
					<?php
				}
				?>
			</ul>
		  </div>
		  <?php
	}
	?>

    </section>


    <?php include "parts/sidebars/internal.php" ?>


  </div>
</main>

<?php include('parts/footer.php'); ?>
