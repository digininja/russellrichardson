<?php
require_once ("application/includes/constants.inc.php");
require_once ("application/classes/case_study_category_list.class.php");

$case_study_category_list = new case_study_category_list();
$case_study_category_list->set_order_by ("name");
$case_study_categories = $case_study_category_list->do_search();

$meta_title = "Case Studies | Russell Richardson";
$meta_description = "We are proud to share some of our projects and the kind testimonials we receive from our customers.";

$menu_location = "case_study";

include('parts/header.php');

?>

<main class="site-content" data-page="case-studies">
  <div class="container container--large inline-hack">


    <nav class="breadcrumb">
      <ul class="breadcrumb__nav list--blank">
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/">Home</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller">Case Studies</li>
      </ul>
      <h1 class="breadcrumb__header header">Case Studies</h1>
    </nav>

		<section class="main-content main-content--internal">

      <div class="image-text-box">
        <div class="image-text-box__box pos--rel">
          <div class="image-text-box__image" style="background-image:url('/assets/images/case/case-top.jpg')"></div>
          <div class="image-text-box__text no-edge-margin">
            <p class="bodyfont font--black">We are proud to share some of our project success stories and the kind testimonials we receive from our customers.</p>
          </div>
        </div>
      </div>

			<?php
			foreach ($case_study_categories as $case_study_category) {

				$case_studies = $case_study_category->get_children();
					if (count ($case_studies) > 0) {
				?>

				  <div class="block-links mt--90px no-edge-margin">

						<h2 class="block-links__title header"><?=clean_display_string ($case_study_category->get_name())?> - Case Studies</h2>

						<?php



							foreach($case_studies as $case_study){

								$image = $case_study->get_logo();

						?>

						<a class="block-links__item no-line pos--rel" href="/case_study.php?id=<?=clean_display_string ($case_study->get_id())?>">
							<div class="block-links__image pos--abs" style="background-image:url('<?= (!is_null($image) ? $image->get_src() : '') ?>')"></div>
							<div class="block-links__text no-edge-margin">
								<h3 class="header font--18px font--linkblue"><?=clean_display_string ($case_study->get_name())?></h3>
								<p class="bodyfont bodyfont--small mt--10px"><?=clean_display_string ($case_study->get_summary())?> <span class="font--16px font--linkblue">Learn More...</span></p>
							</div>
						</a>

					<?php
							}
							echo '</div>';
						}

					}
						?>

    </section>

		<?php include "parts/sidebars/internal.php" ?>

</div>

</main>

<?php

include('parts/footer.php');

?>
