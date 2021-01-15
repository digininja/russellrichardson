<?php

require_once ("application/includes/constants.inc.php");
require_once ("application/classes/advice_l3.class.php");

if (array_key_exists ("url", $_GET)) {
	$url = ($_GET['url']);
	$advice_l3 = new advice_l3();
	if (!$advice_l3->load_by_url ($url)) {
		header ("location: /advice-centre.php");
		exit;
	}
	$parent = $advice_l3->get_parent();
	$parentParent = $parent->get_parent();

} elseif (array_key_exists ("id", $_GET) && is_numeric ($_GET['id'])) {
	$id = intval ($_GET['id']);
	$advice_l3 = new advice_l3();
	if (!$advice_l3->load_if_exists ($id)) {
		header ("location: /advice-centre.php");
		exit;
	}
	$parent = $advice_l3->get_parent();
	$parentParent = $parent->get_parent();

} else {
	header ("location: /advice-centre.php");
	exit;
}

$menu_location = "advice";
$meta_title = $advice_l3->get_meta_title();
$meta_description = $advice_l3->get_meta_description();


include('parts/header.php');

require_once ("application/classes/image.class.php");

?>

<main class="site-content" data-page="advice-centre-page">
  <div class="container container--large inline-hack">


    <nav class="breadcrumb">
      <ul class="breadcrumb__nav list--blank">
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/">Home</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/advice-centre.php">Advice Centre</a>
        </li>
				<li class="breadcrumb__item bodyfont bodyfont--smaller">
					<a class="font--linkblue" href="/advice/<?= $parentParent->get_url() ?>"><?=clean_display_string ($parentParent->get_name())?></a>
				</li>
				<li class="breadcrumb__item bodyfont bodyfont--smaller">
					<a class="font--linkblue" href="/advice/2/<?= $parent->get_url() ?>"><?=clean_display_string ($parent->get_name())?></a>
				</li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller"><?=clean_display_string ($advice_l3->get_name())?></li>
      </ul>
      <h1 class="breadcrumb__header header"><?=clean_display_string ($advice_l3->get_name())?></h1>
    </nav>

		<section class="main-content main-content--internal main-content--advice-page">

			<?= $advice_l3->get_body() ?>

	<?php
	$related_articles = $advice_l3->get_related_articles();

	if (count ($related_articles) > 0) {
		?>
		<h6 class="header font--22px mt--60px mb--30px">Related Articles</h6>
		<ul class="additional-links__holder list--blank">
			<?php
			foreach ($related_articles as $advice_l3) {
				?>
				<li class="additional-links__item additional-links__item--category">
				<a class="additional-links__link font--linkblue no-line" href="/advice/3/<?=clean_display_string ($advice_l3->get_url())?>">
					<p class="header font--20px font--linkblue"><?=clean_display_string ($advice_l3->get_name())?></p>
				</a>
				<?php
			}
			?>
			</li>
		</ul>
		<?php
	}
	?>

	<?php
	$related_services = $advice_l3->get_related_services();

	if (count ($related_services) > 0) {
		?>
		<h6 class="header font--22px mt--60px mb--30px">Related Services</h6>
		<ul class="additional-links__holder list--blank">
			<?php
			foreach ($related_services as $category_l3) {
				?>
				<li class="additional-links__item additional-links__item--category">
				<a class="additional-links__link font--linkblue no-line" href="/service/3/<?=clean_display_string ($category_l3->get_url())?>">
					<p class="header font--20px font--linkblue"><?=clean_display_string ($category_l3->get_name())?></p>
				</a>
				<?php
			}
			?>
			</li>
		</ul>
		<?php
	}
	?>

    </section>



		<?php include "parts/sidebars/internal.php" ?>

		</div>

		</main>

		<?php

		include('parts/footer.php');

		?>
