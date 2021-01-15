<?php
require_once ("application/includes/constants.inc.php");
require_once ("application/classes/news_category.class.php");
require_once ("application/classes/news_category_list.class.php");
require_once ("application/classes/image.class.php");

$news_category_list = new news_category_list();
$news_category_list->set_order_by ("name");
$news_categories = $news_category_list->do_search();

$meta_title = "All the latest news | Russell Richardson";
$meta_description = "UK wide mobile shredding trucks and collection for your on and off-site shredding needs. Backed by our CCTV secure data storage and archiving facility.";

include('parts/header.php');

?>

<main class="site-content" data-page="news-category">
  <div class="container container--large inline-hack">

		<nav class="breadcrumb">
      <ul class="breadcrumb__nav list--blank">
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/">Home</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller">News</li>
      </ul>
      <h1 class="breadcrumb__header header">News</h1>
    </nav>

		<section class="main-content main-content--internal">

			<div class="block-links no-edge-margin">

				<?php
				foreach ($news_categories as $news_category) {

					$image = $news_category->get_image();

					?>

						<a class="block-links__item no-line pos--rel" href="news_category.php?id=<?=clean_display_string ($news_category->get_id())?>">
		          <div class="block-links__image pos--abs" style="background-image:url('<?= (!is_null($image) ? $image->get_src() : '') ?>')"></div>
		          <div class="block-links__text no-edge-margin">
		            <h3 class="header font--22px font--linkblue"><?=clean_display_string ($news_category->get_name())?></h3>
		          </div>
		        </a>

					<?php
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
