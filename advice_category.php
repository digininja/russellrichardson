<?php

require_once ("application/includes/constants.inc.php");
require_once ("application/classes/advice_l1.class.php");

if (array_key_exists ("url", $_GET)) {
	$url = ($_GET['url']);
	$advice_l1 = new advice_l1();
	if (!$advice_l1->load_by_url ($url)) {
		header ("location: /advice-centre.php");
		exit;
	}
} elseif (array_key_exists ("id", $_GET) && is_numeric ($_GET['id'])) {
	$id = intval ($_GET['id']);
	$advice_l1 = new advice_l1();
	if (!$advice_l1->load_if_exists ($id)) {
		header ("location: /advice-centre.php");
		exit;
	}
} else {
	header ("location: /advice-centre.php");
	exit;
}


$menu_location = "advice";

$meta_title = $advice_l1->get_meta_title();
$meta_description = $advice_l1->get_meta_description();

include('parts/header.php');

require_once ("application/classes/image.class.php");

$image = $advice_l1->get_image();


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
        <li class="breadcrumb__item bodyfont bodyfont--smaller"><?=clean_display_string ($advice_l1->get_name())?></li>
      </ul>
      <h1 class="breadcrumb__header header"><?=clean_display_string ($advice_l1->get_name())?></h1>
    </nav>


		<section class="main-content main-content--internal-list">

			<div class="additional-links">
				<p class="bodyfont mt--0px"><?=clean_display_string ($advice_l1->get_summary())?></p>

			<?php
			$children = $advice_l1->get_children();

			if(count($children) > 0){
				echo '<ul class="additional-links__holder list--blank">';

					foreach ($children as $advice_l2) {
						?>
						<li class="additional-links__item additional-links__item--category">
							<a class="additional-links__link font--linkblue no-line" href="/advice/2/<?=clean_display_string ($advice_l2->get_url())?>">
								<p class="header font--20px font--linkblue"><?=clean_display_string ($advice_l2->get_name())?></p>
								<span class="bodyfont font--grey weight--regular align--right">Guides, FAQ’s & Research</span>
							</a>
						</li>
						<?php
					}
					?>
					</ul>
					<?php
				}
				?>

			</div>


    </section>


			<?php include "parts/sidebars/internal.php" ?>

			</div>

			</main>

			<?php

			include('parts/footer.php');

			?>
