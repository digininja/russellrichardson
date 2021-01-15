<?php
require_once ("application/includes/constants.inc.php");
require_once ("application/classes/download.class.php");
require_once ("application/classes/download_list.class.php");
require_once ("application/classes/cms_image_gallery.class.php");
require_once ("application/classes/cms_image_gallery_list.class.php");

$download_list = new download_list();
$download_list->set_order_by ("name");
$downloads = $download_list->do_search();

$meta_title = "Certificates & Downloads | Russell Richardson";
$meta_description = "Copies of our accreditation certificates and associated shredding info.";

include('parts/header.php');

?>

<main class="site-content" data-page="news">
  <div class="container container--large inline-hack">


    <nav class="breadcrumb">
      <ul class="breadcrumb__nav list--blank">
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/">Home</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller">Certificates & Downloads</li>
      </ul>
      <h1 class="breadcrumb__header header">Certificates & Downloads</h1>
    </nav>

		<section class="main-content main-content--internal">

      <p class="bodyfont mt--0px">Please see the below list of certificates in pdf format. These can be accessed via the 'download' button. If you would like to speak with us specifically, please call or use our contact form to talk to our team.</p>

      <div class="certificates mt--50px">

				<?php
				foreach ($downloads as $download) {
					?>

					<div class="certificates__item">
	          <?php include "assets/svgs/pdf.svg";?>
	          <div class="certificates__text ml--30px">
	            <h3 class="header font--18px weight--black font--linkblue"><?=clean_display_string ($download->get_name())?></h3>
	            <p class="certificates__date bodyfont bodyfont--small"><?=clean_display_string (format_date ($download->get_date(), DATE_ONLY))?></p>
	          </div>
	          <div class="certificates__buttonholder">
	            <a class="button button--blue button--slim" href="/userfiles/documents/<?=clean_display_string ($download->get_filename())?>">Download</a>
	          </div>
	        </div>


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
