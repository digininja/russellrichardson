<?php

require_once ("application/includes/constants.inc.php");
require_once ("application/classes/case_study.class.php");

if (array_key_exists ("id", $_GET) && is_numeric ($_GET['id'])) {
	$id = intval ($_GET['id']);
	$case_study = new case_study();
	if (!$case_study->load_if_exists ($id)) {
		header ("location: /case_studies.php");
		exit;
	}
} else {
	header ("location: /case_studies.php");
	exit;
}

$menu_location = "case_study";

$meta_title = $case_study->get_name();
include('parts/header.php');


require_once ("application/classes/case_study_category.class.php");
require_once ("application/classes/image.class.php");

$banner = $case_study->get_banner();
$image = $case_study->get_image();
$logo = $case_study->get_logo();

$case_study_category = $case_study->get_category();

?>

<main class="site-content" data-page="case-study">
  <div class="container container--large inline-hack">


    <nav class="breadcrumb">
      <ul class="breadcrumb__nav list--blank">
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/">Home</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/case_studies.php">Case Studies</a>
        </li>
				<li class="breadcrumb__item bodyfont bodyfont--smaller">
					<a class="font--linkblue" href="/case_study_category.php?id=<?=clean_display_string ($case_study_category->get_id())?>"><?=clean_display_string ($case_study_category->get_name())?></a>
				</li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller"><?=clean_display_string ($case_study->get_name())?></li>
      </ul>
      <h1 class="breadcrumb__header header">Case Study - <?=clean_display_string ($case_study->get_name())?></h1>
    </nav>


    <section class="main-content main-content--internal">

			<div class="image-quote mb--40px">
        <!-- <div class="image-quote__image" style="background-image:url('<?= (!is_null($banner) ? $banner->get_src() : '') ?>')"></div> -->
        <div class="image-quote__info pos--rel mt--40px">

					<p class="bodyfont bodyfont--large font--black">
						"<?= $case_study->get_quote() ?>"
						<span class="bodyfont"><?=clean_display_string ($case_study->get_quote_name())?></span>
					</p>


          <div class="image-quote__small-img" style="background-image:url('<?= (!is_null($logo) ? $logo->get_src() : '') ?>')"></div>
        </div>
      </div>

			<?=($case_study->get_body())?>

		</section>

		<?php include "parts/sidebars/internal.php" ?>

	</div>

	</main>

	<?php

	include('parts/footer.php');

	?>
