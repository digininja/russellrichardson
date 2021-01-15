<?php

include('parts/header.php');

require_once ("application/classes/case_study_list.class.php");
require_once ("application/searches/site.search.php");

$results = null;

// Jon prefers search terms on the querystring so he can track them
// through analytics
if (array_key_exists ("keyword", $_GET)) {
	$keyword = $_GET['keyword'];
	$options = array (
						"keyword" => $keyword,
					);
	$results = site_search($options);
	// var_dump_pre ($results);
}

?>
<main class="site-content" data-page="service-category">
  <div class="container container--large inline-hack">

		<nav class="breadcrumb">
      <ul class="breadcrumb__nav list--blank">
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/">Home</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller">Search</li>
      </ul>
      <h1 class="breadcrumb__header header">Results for "<?= $options['keyword'] ?>"</h1>
    </nav>

		<section class="main-content main-content--internal">

			<div class="block-links no-edge-margin">

				<?php
				if (!is_null ($results)) {
					if (count($results['category_l1']) > 0) {
						?>
						<h2>Services</h2>
						<?php
						foreach ($results['category_l1'] as $category_l1) {
							$image = $category_l1->get_image();
							?>
							<a class="block-links__item no-line pos--rel" href="/service/<?=clean_display_string ($category_l1->get_url())?>">
								<div class="block-links__image pos--abs" style="background-image:url('<?= (!is_null($image) ? $image->get_src() : '') ?>')"></div>
								<div class="block-links__text no-edge-margin">
									<h3 class="header font--22px font--linkblue">Service : <?=clean_display_string ($category_l1->get_name())?></h3>
									<p class="bodyfont mt--10px"><?=clean_display_string ($category_l1->get_summary())?> <span class="font--18px font--linkblue weight--heavy">Learn more...</span></p>
								</div>
							</a>

						<?php
						}
					}
					if (count($results['category_l2']) > 0) {
						?>
						<?php
						foreach ($results['category_l2'] as $category_l2) {
							$image = $category_l2->get_image();
							?>
							<a class="block-links__item no-line pos--rel" href="/service/2/<?=clean_display_string ($category_l2->get_url())?>">
								<div class="block-links__image pos--abs" style="background-image:url('<?= (!is_null($image) ? $image->get_src() : '') ?>')"></div>
								<div class="block-links__text no-edge-margin">
									<h3 class="header font--22px font--linkblue"><?=clean_display_string ($category_l2->get_name())?></h3>
									<p class="bodyfont mt--10px"><?=clean_display_string ($category_l2->get_summary())?> <span class="font--18px font--linkblue weight--heavy">Learn more...</span></p>
								</div>
							</a>

						<?php
						}
					}
					if (count($results['category_l3']) > 0) {
						?>
						<?php
						foreach ($results['category_l3'] as $category_l3) {
							$image = $category_l3->get_image();
							?>
							<a class="block-links__item no-line pos--rel" href="/service/3/<?=clean_display_string ($category_l3->get_url())?>">
								<div class="block-links__image pos--abs" style="background-image:url('<?= (!is_null($image) ? $image->get_src() : '') ?>')"></div>
								<div class="block-links__text no-edge-margin">
									<h3 class="header font--22px font--linkblue"><?=clean_display_string ($category_l3->get_name())?></h3>
									<p class="bodyfont mt--10px"><?=clean_display_string ($category_l3->get_summary())?> <span class="font--18px font--linkblue weight--heavy">Learn more...</span></p>
								</div>
							</a>

						<?php
						}
					}
					if (count($results['news']) > 0) {
						?>
						<h2>News</h2>
						<?php
						foreach ($results['news'] as $news) {
							$image = $news->get_image();
							?>
							<a class="block-links__item no-line pos--rel" href="/news.php?id=<?=clean_display_string ($news->get_id())?>">
								<div class="block-links__image pos--abs" style="background-image:url('<?= (!is_null($image) ? $image->get_src() : '') ?>')"></div>
								<div class="block-links__text no-edge-margin">
									<h3 class="header font--22px font--linkblue">News : <?=clean_display_string ($news->get_name())?></h3>
									<p class="bodyfont mt--10px"><?=clean_display_string ($news->get_summary())?> <span class="font--18px font--linkblue weight--heavy">Learn more...</span></p>
								</div>
							</a>

						<?php
						}
					}
					if (count($results['case_studies']) > 0) {
						?>
						<h2>Case Studies</h2>
						<?php
						foreach ($results['case_studies'] as $case_studies) {
							$image = $case_studies->get_image();
							?>
							<a class="block-links__item no-line pos--rel" href="/case_study.php?id=<?=clean_display_string ($case_studies->get_id())?>">
								<div class="block-links__image pos--abs" style="background-image:url('<?= (!is_null($image) ? $image->get_src() : '') ?>')"></div>
								<div class="block-links__text no-edge-margin">
									<h3 class="header font--22px font--linkblue">Case Study : <?=clean_display_string ($case_studies->get_name())?></h3>
									<p class="bodyfont mt--10px"><?=clean_display_string ($case_studies->get_summary())?> <span class="font--18px font--linkblue weight--heavy">Learn more...</span></p>
								</div>
							</a>

						<?php
						}
					}
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
