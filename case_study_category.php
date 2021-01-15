<?php

require_once ("application/includes/constants.inc.php");
require_once ("application/classes/case_study_category.class.php");

if (array_key_exists ("id", $_GET) && is_numeric ($_GET['id'])) {
	$id = intval ($_GET['id']);
	$case_study_category = new case_study_category();
	if (!$case_study_category->load_if_exists ($id)) {
		header ("location: /case_studies.php");
		exit;
	}
} else {
	header ("location: /case_studies.php");
	exit;
}

$menu_location = "case_study";

include('parts/header.php');

require_once ("application/classes/image.class.php");

$image = $case_study_category->get_image();

?>

<main class="site-content" data-page="case-studies">
  <div class="container container--large inline-hack">


    <nav class="breadcrumb">
      <ul class="breadcrumb__nav list--blank">
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/">Home</a>
        </li>
				<li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/case_studies.php">Case Studies</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller"><?=clean_display_string ($case_study_category->get_name())?></li>
      </ul>
      <h1 class="breadcrumb__header header"><?=clean_display_string ($case_study_category->get_name())?> - Case Studies</h1>
    </nav>

		<section class="main-content main-content--internal">

      <div class="image-text-box">
        <div class="image-text-box__box pos--rel">
          <div class="image-text-box__image" style="background-image:url('<?= (!is_null($image) ? $image->get_src() : '') ?>')"></div>
          <div class="image-text-box__text no-edge-margin">
            <p class="bodyfont font--black">NEED CATEGORY SUMMARY HERE</p>
          </div>
        </div>
      </div>

		<?php
		$case_studies = $case_study_category->get_children();
		if (count ($case_studies) > 0) {
			?>

			<div class="block-links mt--90px no-edge-margin">

				<h2 class="block-links__title header"><?=clean_display_string ($case_study_category->get_name())?> - Case Studies</h2>

				<?php



					foreach($case_studies as $case_study){

						$image = $case_study->get_image();



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
		?>

	</section>

	<?php include "parts/sidebars/internal.php" ?>

</div>

</main>

<?php

include('parts/footer.php');

?>
