<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}

require_once ("application/includes/constants.inc.php");
require_once ("application/includes/useful.inc.php");
require_once ("application/includes/debug.inc.php");

// Include category l1 list for nav menu
require_once ("application/classes/category_l1_list.class.php");

if (!isset ($meta_title)) {
	$meta_title = "Russell Richardson";
}

$meta_description_tag = "";
if (isset ($meta_description)) {
	$meta_description_tag = "<meta name='Description' content='" . clean_display_string ($meta_description) . "' />";
}

?>

<!DOCTYPE html>

<head>
	<!-- metadata -->
	<meta charset="UTF-8">
	<title><?=clean_display_string ($meta_title)?></title>
	<?=$meta_description_tag?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
	<!-- stylesheets -->
	<link rel="stylesheet" type="text/css" href="/assets/css/main.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/lity.min.css">

	<meta name="google-site-verification" content="3K8KSV37EXqgjQC1xR2AypxdxPsE4xlPsejjb741xsQ" />

	<!--[if IE 9]>
	<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
	<link href="assets/css/ie.css" rel="stylesheet" type="text/css">
	<![endif]-->
	<!--[if IE 8]>
	<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
	<link href="assets/css/oldie.css" rel="stylesheet" type="text/css">
	<![endif]-->

	<!-- favicon -->
	<link rel="apple-touch-icon" sizes="57x57" href="/assets/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/assets/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/assets/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/assets/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/assets/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/assets/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/assets/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/assets/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/assets/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/assets/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
	<link rel="manifest" href="/assets/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/assets/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<meta name="format-detection" content="telephone=no">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="Author" content="Robin Wood - Freedom Software" />

	<script src='https://www.google.com/recaptcha/api.js'></script>
	<!--[if !IE]><!-->
	<script>
	  if (/*@cc_on!@*/false && document.documentMode === 10) {
	    document.documentElement.className+=' ie10';
	  }
	</script>
	<!--<![endif]-->

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-12957961-1"></script>
	<script>
	window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date());
	gtag('config', 'UA-12957961-1');
	gtag('config', 'AW-880160939');
	</script>
	<script>  gtag('config', 'AW-880160939/R5E_CNDbomcQq-HYowM', {    'phone_conversion_number': '0800 294 6552'  });</script>

</head>
<body>


	<header class="site-header fixed--top z--max">
		<div class="site-header__top container container--large inline-hack">

			<a class="site-header__logo" href="/">
				<img src="/assets/images/global/logo.png" alt="Russell Richardson Main Logo" />
				<!-- <img src="/assets/svgs/logos/main-logo.svg" alt="Russell Richardson Main SVG Logo" /> -->
			</a>

			<nav class="site-header__second-nav align--right">

				<ul class="site-header__nav list--blank pos--rel">
					<li class="site-header__inline-item">
						<a class="site-header__link pos--rel z--2" href="/">
							<svg class="icon icon--small-search trans--200ms">
								<use xlink:href="#small-search"/>
							</svg>
						</a>
						<form class="search-bar pos--abs center--y z--1" action="/search.php">
							<input class="search-bar__input font--14px trans--200ms" name="keyword" placeholder="e.g. Shredding Services..." />
							<button onclick="this.form.submit();" class="search-bar__btn font--14px font--white weight--bold pos--abs z--2 trans--200ms">Search</button>
						</form>
					</li>
					<li class="site-header__inline-item">
						<a class="site-header__link font--16px font--grey weight--semibold" href="/about.php">About Us</a>
					</li>
					<li class="site-header__inline-item">
						<a class="site-header__link font--16px font--grey weight--semibold" href="/news_landing.php">News</a>
					</li>
					<li class="site-header__inline-item">
						<a class="site-header__link font--16px font--grey weight--semibold" href="/certificates-downloads.php">Certificates & Downloads</a>
					</li>
					<li class="site-header__inline-item">
						<a class="site-header__link font--16px font--grey weight--semibold" href="/charity.php">Charity</a>
					</li>
				</ul>

				<div class="site-header__contact align--left">
					<p class="bodyfont bodyfont--large weight--bold">Free Phone:</p>
					<a class="font--34px font--lightgreen weight--black no-line" href="tel:08002946552" onClick="gtag('event', 'Press', { 'event_category': 'Call', 'event_label': 'Header'});">0800 294 6552</a>
				</div>

				<a class="site-header__menu-toggle pos--rel" href="#" id="open-menu-btn">
					<p class="weight--bold font--darkgreen">Menu</p>
					<span></span>
				</a>
			</nav>

		</div>


<?php

$service_active = "";
$case_study_active = "";
$locations_active = "";
$advice_active = "";
$contact_active = "";

if (isset ($menu_location)) {
	switch ($menu_location) {
		case "locations":
			$locations_active = " active ";
			break;
		case "advice":
			$advice_active = " active ";
			break;
		case "contact":
			$contact_active = " active ";
			break;
		case "case_study":
			$case_study_active = " active ";
			break;
	}
} else {
}
?>
		<nav class="site-header__main-nav">
			<div class="container container--large">

				<a class="site-header__menu-toggle" href="#" id="close-menu-btn">
					<p class="weight--bold font--darkgreen">Close</p>
					<span></span>
				</a>

				<ul class="site-header__nav site-header__nav--main list--blank">
          <?php

            $service_list = new category_l1_list();
            $service_list->set_order_by ("order");
            $services = $service_list->do_search();

            foreach ($services as $service) {

				$service_active = " ";
				if (isset ($menu_location)) {
					if ($menu_location == "service_" . $service->get_id()) {
						$service_active = " active ";
					}
				}

          		$sub_services = $service->get_children();

              echo '<li class="site-header__inline-item site-header__inline-item--main pos--rel trans--200ms' . $service_active . '">
    						<a class="site-header__link font--white weight--semibold no-line pos--rel" href="/service/' . clean_display_string ($service->get_url()) . '">
    							' . clean_display_string ($service->get_name());
									if (count ($sub_services) > 0) {

	    							echo '<svg class="icon icon--small-arrow o--4 trans--200ms">
	    								<use xlink:href="#small-arrow"/>
	    							</svg>';

									}

    						echo '</a>';

                if (count ($sub_services) > 0) {

                  echo '<ul class="site-header__subnav pos--abs list--blank">';

        					foreach ($sub_services as $sub_service) {

                    $sub_sub_services = $sub_service->get_children();

                    echo '<li class="site-header__sub-item">
      								<a class="site-header__link font--white weight--semibold no-line pos--rel trans--200ms" href="/service/2/' . clean_display_string ($sub_service->get_url()) . '">
      									' . clean_display_string ($sub_service->get_name());
												if (count ($sub_sub_services) > 0) {

	      									echo '<svg class="icon icon--small-arrow o--4 trans--200ms pos--abs">
	      										<use xlink:href="#small-arrow"/>
	      									</svg>';

												}

      								echo '</a>';

                      if (count ($sub_sub_services) > 0) {

                        echo '<ul class="site-header__subnav site-header__subnav--level2 list--blank pos--abs z--0">';

                          foreach ($sub_sub_services as $sub_sub_service) {

                            echo '<li class="site-header__sub-item">
          										<a class="site-header__link font--white no-line trans--200ms" href="/service/3/' . clean_display_string ($sub_sub_service->get_url()) . '">
          										 ' . clean_display_string ($sub_sub_service->get_name()) . '
          										</a>
          									</li>';

                          }

                        echo '</ul>';


                      }

                    echo '</li>';

                  }
                  echo '</ul>';

                }


              echo '</li>';

            }

           ?>

					<li class="site-header__inline-item site-header__inline-item--main trans--200ms pos--rel <?=$case_study_active?>">
						<a class="site-header__link font--white weight--semibold no-line" href="/case_studies.php">Case Studies</a>
					</li>
					<li class="site-header__inline-item site-header__inline-item--main trans--200ms pos--rel <?=$locations_active?>">
						<a class="site-header__link font--white weight--semibold no-line" href="/locations.php">Our Locations</a>
					</li>
					<li class="site-header__inline-item site-header__inline-item--main trans--200ms pos--rel <?=$advice_active?>">
						<a class="site-header__link font--white weight--semibold no-line" href="/advice-centre.php">Advice Centre</a>
					</li>
					<li class="site-header__inline-item site-header__inline-item--main trans--200ms pos--rel <?=$contact_active?>">
						<a class="site-header__link font--white weight--semibold no-line" href="/contact.php">Contact Us</a>
					</li>

				</ul>

				<ul class="site-header__nav site-header__nav--mobile list--blank pos--rel">
					<li class="site-header__inline-item">
						<a class="site-header__link pos--rel z--2" href="/">
							<svg class="icon icon--small-search trans--200ms">
								<use xlink:href="#small-search"/>
							</svg>
						</a>
						<form class="search-bar pos--abs center--y z--1" action="#">
							<input class="search-bar__input font--14px trans--200ms" placeholder="e.g Shredding Services..." />
							<button class="search-bar__btn font--14px font--white weight--bold pos--abs z--2 trans--200ms">Search</button>
						</form>
					</li>
					<li class="site-header__inline-item">
						<a class="site-header__link font--16px font--grey weight--semibold" href="/about.php">About Us</a>
					</li>
					<li class="site-header__inline-item">
						<a class="site-header__link font--16px font--grey weight--semibold" href="/news_landing.php">News</a>
					</li>
					<li class="site-header__inline-item">
						<a class="site-header__link font--16px font--grey weight--semibold" href="/certificates-downloads.php">Certificates & Downloads</a>
					</li>
					<li class="site-header__inline-item">
						<a class="site-header__link font--16px font--grey weight--semibold" href="/charity.php">Charity</a>
					</li>
				</ul>

				<div class="site-header__contact site-header__contact--mobile align--left">
					<p class="bodyfont bodyfont--large weight--bold">Free Phone:</p>
					<a class="font--34px font--lightgreen weight--black no-line" href="tel:08002946552" onClick="gtag('event', 'Press', { 'event_category': 'Call', 'event_label': 'Header'});">0800 294 6552</a>
				</div>

			</div>

			<form class="search-bar search-bar--mobile pos--fix" action="/search.php">
				<input class="search-bar__input font--14px trans--200ms pos--rel z--1" name="keyword" placeholder="e.g. Shredding Services..." />
				<button onclick="this.form.submit();" class="search-bar__btn pos--abs z--2">
					<svg class="icon icon--small-search trans--200ms">
						<use xlink:href="#small-search"/>
					</svg>
				</button>
			</form>

		</nav>

</header>
