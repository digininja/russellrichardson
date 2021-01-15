<?php
require_once ("application/includes/constants.inc.php");
require_once ("application/classes/advice_l1.class.php");
require_once ("application/classes/advice_l1_list.class.php");
require_once ("application/classes/image.class.php");

$advice_l1_list = new advice_l1_list();
$advice_l1_list->set_order_by ("name");
$advice_l1s = $advice_l1_list->do_search();


$meta_title = "Shredding, Archiving & Storage Advice | Russell Richardson";
$meta_description = "We answer your common questions around Shredding, Archiving & Storage. Get all the latest information relating to legislative changes, statutory requirements and general help and advice from our expert team.";

$menu_location = "advice";

include('parts/header.php');
?>

<main class="site-content" data-page="advice-centre">
  <div class="container container--large inline-hack">


    <nav class="breadcrumb">
      <ul class="breadcrumb__nav list--blank">
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/">Home</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller">Advice Centre</li>
      </ul>
      <h1 class="breadcrumb__header header">Advice Centre</h1>
    </nav>


    <section class="main-content main-content--internal">

			<div class="image-text-box">
        <div class="image-text-box__box pos--rel">
          <div class="image-text-box__image" style="background-image:url('/assets/images/advice/advice-top.jpg')"></div>
          <div class="image-text-box__text no-edge-margin">
            <p class="bodyfont font--black">We answer your common questions around Shredding, Archiving & Storage. Get all the latest information relating to legislative changes, statutory requirements and general help and advice from our expert team.</p>
          </div>
        </div>
      </div>



					<?php
					foreach ($advice_l1s as $advice_l1) {

						$image = $advice_l1->get_image();

						?>

						<div class="block-links mt--90px no-edge-margin">
			        <h2 class="header"><a class="no-line font--black" href="advice_category.php?id=<?=clean_display_string ($advice_l1->get_id())?>"><?=clean_display_string ($advice_l1->get_name())?></a></h2>
			        <p class="bodyfont"><?=clean_display_string ($advice_l1->get_summary())?> <a class="font--linkblue" href="/advice/<?=clean_display_string ($advice_l1->get_url())?>">Learn More</a></p>
			      </div>

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
