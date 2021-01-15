<?php

require_once ("application/includes/constants.inc.php");
require_once ("application/classes/advice_l2.class.php");

if (array_key_exists ("url", $_GET)) {
	$url = ($_GET['url']);
	$advice_l2 = new advice_l2();
	if (!$advice_l2->load_by_url ($url)) {
		header ("location: /advice-centre.php");
		exit;
	}
	$parent = $advice_l2->get_parent();
} elseif (array_key_exists ("id", $_GET) && is_numeric ($_GET['id'])) {
	$id = intval ($_GET['id']);
	$advice_l2 = new advice_l2();
	if (!$advice_l2->load_if_exists ($id)) {
		header ("location: /advice-centre.php");
		exit;
	}
	$parent = $advice_l2->get_parent();
} else {
	header ("location: /advice-centre.php");
	exit;
}

$menu_location = "advice";
$meta_title = $advice_l2->get_meta_title();

include('parts/header.php');

require_once ("application/classes/image.class.php");

$image = $advice_l2->get_banner();


$children = $advice_l2->get_children();
$articles = array(
					"faq" => array(),
					"guide" => array(),
					"research" => array(),
);

foreach($children as $child){
	if(array_key_exists ($child->get_content_type(), $articles)) {
		$articles[$child->get_content_type()][] = array(
			'id'=>$child->get_id(),
			'url'=>$child->get_url(),
			'name'=>clean_display_string ($child->get_name()),
			'summary'=>clean_display_string ($child->get_summary()),
		);
	}
}

?>

<main class="site-content" data-page="advice-centre-category">
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
					<a class="font--linkblue" href="/advice/<?= $parent->get_url() ?>"><?=clean_display_string ($parent->get_name())?></a>
				</li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller"><?=clean_display_string ($advice_l2->get_name())?></li>
      </ul>
      <h1 class="breadcrumb__header header"><?=clean_display_string ($advice_l2->get_name())?></h1>
    </nav>

		<section class="main-content main-content--internal-list">

      <div class="image-text-box">
        <div class="image-text-box__box pos--rel">
          <div class="image-text-box__image" style="background-image:url('<?= (!is_null($image) ? $image->get_src() : '') ?>')"></div>
          <div class="image-text-box__text no-edge-margin">
            <p class="bodyfont font--black"><?=clean_display_string ($advice_l2->get_summary())?></p>
          </div>
        </div>
      </div>
		<?php
		if(isset($articles['guide'])){
			?>
			<h6 class="header font--24px mt--60px mb--40px">Guides</h6>
			<div class="advice-links">
			<?php
			foreach($articles['guide'] as $item){

				?>
				<div class="advice-links__item">
					<?php include "assets/svgs/guides.svg"; ?>
					<a class="advice-links__link no-line" href="/advice/3/<?= $item['url'] ?>">
						<h3 class="header font--22px font--linkblue"><?= $item['name'] ?></h3>
						<p class="bodyfont"><?= $item['summary'] ?></p>
					</a>
				</div>
				<?php

			}
			echo '</div>';
		}
		?>

				<?php

					if(isset($articles['faq'])){

						echo '<h6 class="header font--24px mt--60px mb--40px">FAQ\'s</h6>
						<div class="advice-links">';

						foreach($articles['faq'] as $item){

							?>


										<div class="advice-links__item">
											<?php include "assets/svgs/faq.svg"; ?>
											<a class="advice-links__link no-line" href="/advice/3/<?= $item['url'] ?>">
												<h3 class="header font--22px font--linkblue"><?= $item['name'] ?></h3>
												<p class="bodyfont"><?= $item['summary'] ?></p>
											</a>
										</div>






			<?php

						}

						echo '</div>';

					}

				?>

				<?php

					if(isset($articles['research'])){

						echo '<h6 class="header font--24px mt--60px mb--40px">Research</h6>
						<div class="advice-links">';

						foreach($articles['research'] as $item){

							?>


										<div class="advice-links__item">
											<?php include "assets/svgs/research.svg"; ?>
											<a class="advice-links__link no-line" href="/advice/3/<?= $item['url'] ?>">
												<h3 class="header font--22px font--linkblue"><?= $item['name'] ?></h3>
												<p class="bodyfont"><?= $item['summary'] ?></p>
											</a>
										</div>






			<?php

						}

						echo '</div>';

					}

				?>

				<?php

					if(isset($articles['other'])){

						echo '<h6 class="header font--24px mt--60px mb--40px">Other</h6>
						<div class="advice-links">';

						foreach($articles['other'] as $item){

							?>


										<div class="advice-links__item">
											<?php include "assets/svgs/guides.svg"; ?>
											<a class="advice-links__link no-line" href="/advice/3/<?= $item['url'] ?>">
												<h3 class="header font--22px font--linkblue"><?= $item['name'] ?></h3>
												<p class="bodyfont"><?= $item['summary'] ?></p>
											</a>
										</div>






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
