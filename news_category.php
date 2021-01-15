<?php

require_once ("application/includes/constants.inc.php");
require_once ("application/classes/news_category.class.php");
if (array_key_exists ("id", $_GET) && is_numeric ($_GET['id'])) {
	$id = intval ($_GET['id']);
	$news_category = new news_category();
	if (!$news_category->load_if_exists ($id)) {
		header ("location: /news_categories.php");
		exit;
	}
} else {
	header ("location: /news_categories.php");
	exit;
}

$meta_title = $news_category->get_name();

include('parts/header.php');

require_once ("application/classes/category_l1.class.php");
require_once ("application/classes/image.class.php");

$newsCategoryImage = $news_category->get_image();

?>

<main class="site-content" data-page="news-category">
  <div class="container container--large inline-hack">

		<nav class="breadcrumb">
      <ul class="breadcrumb__nav list--blank">
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/">Home</a>
        </li>
				<li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/news.php">News</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller"><?= clean_display_string ($news_category->get_name()) ?></li>
      </ul>
      <h1 class="breadcrumb__header header"><?= clean_display_string ($news_category->get_name()) ?></h1>
    </nav>

		<section class="main-content main-content--internal">



			<div class="block-links no-edge-margin">

				<?php
				$news = $news_category->get_children();
				if(count ($news) > 0){

					echo '<div class="block-links no-edge-margin">';


					foreach ($news as $news_article) {

						$image = $news_article->get_image();

						?>

						<a class="block-links__item block-links__item--blockdate no-line pos--rel" href="/news.php?id=<?=clean_display_string ($news_article->get_id())?>">
							<div class="block-links__image pos--abs" style="background-image:url('<?= (!is_null($image) ? $image->get_src() : '') ?>')"></div>
							<div class="block-links__text no-edge-margin">
								<h3 class="header font--18px weight--black font--linkblue"><?=clean_display_string ($news_article->get_name())?></h3>
								<span class="block-links__date bodyfont bodyfont--small"><?= date('d.m.Y', $news_article->get_date()) ?></span>
								<p class="block-links__info bodyfont bodyfont--small"><?=clean_display_string ($news_article->get_summary())?></p>
							</div>
						</a>



						<?php
					}

					echo '</div>';

				}
					?>
			</div>


	</section>

	<?php include('parts/sidebars/news.php'); ?>

	</div>

</main>

<?php

	include('parts/footer.php');

?>
