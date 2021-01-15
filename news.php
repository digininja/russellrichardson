<?php
require_once ("application/includes/constants.inc.php");
require_once ("application/classes/news.class.php");
require_once ("application/classes/news_category.class.php");

if (array_key_exists ("id", $_GET) && is_numeric ($_GET['id'])) {
	$id = intval ($_GET['id']);
	$news = new news();
	if (!$news->load_if_exists ($id)) {
		header ("location: /news_categories.php");
		exit;
	}
} else {
	header ("location: /news_categories.php");
	exit;
}

$meta_title = $news->get_name();

include('parts/header.php');

setlocale (LC_ALL, "en_GB");

require_once ("application/classes/image.class.php");

$banner = $news->get_banner();

$news_category = $news->get_category();
$image = $news->get_image();

?>

<main class="site-content" data-page="service-single">
  <div class="container container--large inline-hack">


    <nav class="breadcrumb">
      <ul class="breadcrumb__nav list--blank">
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/">Home</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/news_landing.php">News</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/news_category.php?id=<?=clean_display_string ($news_category->get_id())?>"><?=clean_display_string ($news_category->get_name())?></a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller"><?=clean_display_string ($news->get_name())?></li>
      </ul>
      <h1 class="breadcrumb__header header"><?=clean_display_string ($news->get_name())?></h1>
			<span class="breadcrumb__date bodyfont bodyfont--small"><?= date('d.m.Y', $news->get_date()) ?></span>
    </nav>


    <section class="main-content main-content--internal">


      <div class="text-other-content mt--30px mb--80px no-edge-margin">

				<?php



				if (!is_null ($image)) {
					?>
					<img class="inline-image inline-image--right" src="<?=clean_display_string ($image->get_src())?>" width="<?=clean_display_string ($image->get_width())?>" height="<?=clean_display_string ($image->get_height())?>" alt="<?=clean_display_string ($image->get_alt_text())?>" />
					<?php
				}
				?>

        <p class="bodyfont mt--0px"><?=clean_display_string ($news->get_summary())?></p>

				<?= $news->get_body(); ?>






      </div>



    </section>


    <?php include('parts/sidebars/news.php'); ?>


  </div>
</main>

<?php include('parts/footer.php'); ?>
