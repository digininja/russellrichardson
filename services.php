<?php

// $menu_location = "service";

include('parts/header.php');

require_once ("application/classes/category_l1.class.php");
require_once ("application/classes/category_l1_list.class.php");
require_once ("application/classes/image.class.php");

$category_l1_list = new category_l1_list();
$category_l1_list->set_order_by ("name");
$category_l1s = $category_l1_list->do_search();

?>

<main class="site-content" data-page="service-category">
  <div class="container container--large inline-hack">

		<nav class="breadcrumb">
      <ul class="breadcrumb__nav list--blank">
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/">Home</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller">Services</li>
      </ul>
      <h1 class="breadcrumb__header header">Services</h1>
    </nav>

		<section class="main-content main-content--internal">

			<div class="block-links no-edge-margin">

				<?php
				foreach ($category_l1s as $category_l1) {

					$image = $category_l1->get_image();

					?>

						<a class="block-links__item no-line pos--rel" href="/service/<?=clean_display_string ($category_l1->get_url())?>">
		          <div class="block-links__image pos--abs" style="background-image:url('<?= (!is_null($image) ? $image->get_src() : '') ?>')"></div>
		          <div class="block-links__text no-edge-margin">
		            <h3 class="header font--22px font--linkblue"><?=clean_display_string ($category_l1->get_name())?></h3>
		            <p class="bodyfont mt--10px"><?=clean_display_string ($category_l1->get_summary())?> <span class="font--18px font--linkblue weight--heavy">Learn more...</span></p>
		          </div>
		        </a>

					<?php
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
