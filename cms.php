<?php
if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}

require_once ("application/classes/advice_l1.class.php");
require_once ("application/classes/advice_l1_list.class.php");
require_once ("application/classes/image.class.php");
require_once ("application/includes/constants.inc.php");
require_once ("application/includes/useful.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/includes/error.inc.php");
require_once ("application/classes/cms.class.php");

$cms = new cms();

if (array_key_exists ("url", $_GET)) {
	$url = $_GET['url'];
} else {
	header ("location: /index.php");
	exit;
}

if ($url == "") {
	header ("location: /index.php");
	exit;
}

if (!$cms->load_by_url ($url)) {
	header ("location: /404.php");
	exit;
}

$menu_location = "";
$meta_title = clean_display_string ($cms->get_meta_title());
$meta_description = clean_display_string ($cms->get_meta_description());

include('parts/header.php');

?>
<main class="site-content" data-page="advice-centre">
  <div class="container container--large inline-hack">


    <nav class="breadcrumb">
      <ul class="breadcrumb__nav list--blank">
        <li class="breadcrumb__item bodyfont bodyfont--smaller">
          <a class="font--linkblue" href="/">Home</a>
        </li>
        <li class="breadcrumb__item bodyfont bodyfont--smaller"><?=clean_display_string ($cms->get_title())?></li>
      </ul>
      <h1 class="breadcrumb__header header"><?=clean_display_string ($cms->get_title())?></h1>
    </nav>


    <section class="main-content main-content--internal">

			<?=$cms->get_body()?>



		<?=clean_display_string ($cms->get_title())?>

        </section>

        <?php include "parts/sidebars/internal.php" ?>

        </div>

        </main>

        <?php

        include('parts/footer.php');

        ?>
