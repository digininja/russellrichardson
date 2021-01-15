<?php

require_once ("application/includes/constants.inc.php");
require_once ("application/classes/category_l2.class.php");

if (array_key_exists ("url", $_GET)) {
	$url = ($_GET['url']);
	$category_l2 = new category_l2();
	if (!$category_l2->load_by_url ($url)) {
		header ("location: /services.php");
		exit;
	}
} elseif (array_key_exists ("id", $_GET) && is_numeric ($_GET['id'])) {
	$id = intval ($_GET['id']);
	$category_l2 = new category_l2();
	if (!$category_l2->load_if_exists ($id)) {
		header ("location: /services.php");
		exit;
	}
} else {
	header ("location: /services.php");
	exit;
}

$meta_title = $category_l2->get_meta_title();
$meta_description = $category_l2->get_meta_description();

$category_l1 = $category_l2->get_parent();
$menu_location = "service_" . $category_l1->get_id();

include('parts/header.php');

require_once ("application/classes/category_l1.class.php");
require_once ("application/classes/image.class.php");

$serviceImage = $category_l2->get_banner();

$sub_services = $category_l2->get_children();

?>

<main class="site-content" data-page="service-category">
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
					<a class="font--linkblue" href="/service/<?= clean_display_string ($category_l1->get_url()) ?>"><?= clean_display_string ($category_l1->get_name()) ?></a>
				</li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller"><?= clean_display_string ($category_l2->get_name()) ?></li>
      </ul>
      <h1 class="breadcrumb__header header"><?= clean_display_string ($category_l2->get_name()) ?></h1>
    </nav>

		<section class="main-content main-content--internal">

			<div class="image-text-box <?= (count ($sub_services) > 0 ? 'mb--50px': 'mb--40px') ?>">
        <div class="image-text-box__box pos--rel">
          <div class="image-text-box__image" style="background-image:url('<?= (!is_null($serviceImage) ? $serviceImage->get_src() : '') ?>')"></div>
          <div class="image-text-box__text no-edge-margin">
            <p class="bodyfont font--black"><?=clean_display_string ($category_l2->get_summary())?></p>
          </div>
        </div>
      </div>

			<div class="block-links no-edge-margin">

				<?php

				if(count ($sub_services) > 0){

					echo '<div class="block-links no-edge-margin">
					<h2 class="block-links__title header font--24px">Our ' . clean_display_string ($category_l2->get_name()) . ' Services</h2>';

					foreach ($sub_services as $sub_service) {

						$image = $sub_service->get_image();

						?>

							<a class="block-links__item no-line pos--rel" href="/service/3/<?=clean_display_string ($sub_service->get_url())?>">
			          <div class="block-links__image pos--abs" style="background-image:url('<?= (!is_null($image) ? $image->get_src() : '') ?>')"></div>
			          <div class="block-links__text no-edge-margin">
			            <h3 class="header font--22px font--linkblue"><?=clean_display_string ($sub_service->get_name())?></h3>
			            <p class="bodyfont mt--10px"><?=clean_display_string ($sub_service->get_summary())?> <span class="font--18px font--linkblue weight--heavy">Learn more...</span></p>
			          </div>
			        </a>

						<?php
					}

					echo '</div>';

				}else{

					echo '<div class="text-other-content mt--30px mb--80px no-edge-margin">';
					echo $category_l2->get_body();
					echo '</div>';

				}
					?>
			</div>


	</section>

	<?php include('parts/sidebars/internal.php'); ?>

	</div>

</main>

<?php

	include('parts/footer.php');

?>
