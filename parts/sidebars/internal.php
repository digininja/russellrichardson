<?php
require_once ("application/classes/case_study.class.php");
require_once ("application/classes/news.class.php");
require_once ("application/classes/link_list.class.php");
require_once ("application/classes/recaptchalib.php");

$contact_validation_errors = array();
$contact_result = null;
$submission = false;

$contact_form_array = array (
					"contact_name" => "",
					"contact_liame" => "",
					"contact_email" => "",
					"contact_phone" => "",
					"contact_message" => "",
				);

if ($_SERVER['REQUEST_METHOD'] == "POST" && array_key_exists ("contact_save", $_POST)) {

	$submission = true;

	foreach ($contact_form_array as $name => $value) {
		if (array_key_exists ($name, $_POST)) {
			if (is_array ($_POST[$name])) {
				$contact_form_array[$name] = $_POST[$name];
			} else {
				$contact_form_array[$name] = trim ($_POST[$name]);
			}
		}
	}

	if ($contact_form_array["contact_name"] == "") {
		$contact_validation_errors[] = "You must provide your name";
	}
	if ($contact_form_array["contact_email"] != "") {
		$contact_validation_errors[] = "";
	}
	if ($contact_form_array["contact_liame"] == "") {
		$contact_validation_errors[] = "You must provide an email address";
	} elseif (!preg_match ("/..*@..*/", $contact_form_array["contact_liame"])) {
		$contact_validation_errors[] = "You must provide a valid email address";
	}

	if ($contact_form_array["contact_phone"] == "") {
		$contact_validation_errors[] = "You must provide a phone number";
	}

	if (($_SERVER['SERVER_NAME'] == "joncolegate.dev" ||
		$_SERVER['SERVER_NAME'] == "yes.dev")) {
		# Ignore captcha
	} else {
		// Turned off for dev environment
		// Was there a reCAPTCHA response?
		if (array_key_exists ("g-recaptcha-response", $_POST)) {
			$resp = test_recaptcha (
				$_POST["g-recaptcha-response"],
				$_SERVER["REMOTE_ADDR"]
			);

			# there is another dump in the recaptcha class that gives more info
			# var_dump_pre ($resp);
			if ($resp) {
				# ok
			} else {
				$contact_validation_errors[] = "Failed to answer Captcha correctly";
			}
		} else {
			$contact_validation_errors[] = "Failed to answer Captcha correctly";
		}
	}

	if (count ($contact_validation_errors) == 0) {
		# Email to sender
		$mail_body = "";

		$mail_body .= "Name: " . $contact_form_array['contact_name'] . "\n\r";
		$mail_body .= "Email: " . $contact_form_array['contact_liame'] ."\n\r";
		$mail_body .= "Phone: " . $contact_form_array['contact_phone'] ."\n\r";
		$mail_body .= "Message: " . $contact_form_array['contact_message'] ."\n\r";

		$headers = 'From: info@russellrichardson.co.uk'  . "\r\n";
		$contact_result = mail ("info@russellrichardson.co.uk", "Free quote request from Russell Richardson website", $mail_body, $headers);
		#$contact_result = mail ("robin@freedomsoftware.co.uk", "Free quote request from Russell Richardson website", $mail_body, $headers);

		if ($contact_result) {
			$contact_form_array = array (
								"contact_name" => "",
								"contact_liame" => "",
								"contact_email" => "",
								"contact_phone" => "",
								"contact_message" => "",
							);
		}
	} else {
		// print "not sending";
	}
}

?>
<aside class="sidebar sidebar--internal pos--rel">

  <svg class="hidden pos--abs" width="0" height="0">
    <defs>
      <clipPath id="curve" clipPathUnits="objectBoundingBox">
        <path transform="scale(0.000626, 0.00237)" d="M0,0V360s466,60,800,60,800-60,800-60V0Z"/>
      </clipPath>

      <clipPath id="curve-s" clipPathUnits="objectBoundingBox">
        <style>
          .curve-s {transform: scale(0.0015, 0.00237) translateX(-450px);}
        </style>
        <path class="curve-s" d="M0,0V360s466,60,800,60,800-60,800-60V0Z"/>
      </clipPath>
    </defs>
  </svg>

  <div class="sidebar__image" style="background-image: url('/assets/images/global/sidebar.jpg')"></div>

  <div class="container">

    <h2 class="sidebar__title font--32px font--grey weight--black align--center">
      <a class="font--linkblue" href="/service/shredding">Shredding</a>, <a class="font--linkblue" href="/service/archiving">Archiving</a> & <a class="font--linkblue" href="/service/recycling">Recycling</a> that suits <span class="font--black">YOU</span>
    </h2>

		<div class="results-container">
		<?php
		if ($contact_result === true) {
			?>
			<div class="contact-result contact-success-container">
				<p class="bodyfont">Thanks, message sent</p>
			</div>
			<?php
		}
			?>
			<?php
			if (count ($contact_validation_errors) > 0) {
				?>
				<div class="contact-result contact-error-container">
				<p class="bodyfont">There's errors with your submission. Please review and resubmit.</p>

					<ul>
						<?php
						foreach ($contact_validation_errors as $error) {
							?>
							<li class="bodyfont"><?=clean_display_string ($error)?></li>
							<?php
						}
						?>
					</ul>
				</div>
				<?php
			}
			?>
		</div>
    <form class="contact-form contact-form--homepage inline-hack trans--200ms <?= ($submission ? 'reveal' : '') ?> pos--rel z--2" action="" method="post" id="quote-form">
		<?php
		if ($contact_result === true) {
			?>
			<p>Thanks, message sent</p>
			<?php
		}
			?>
			<div class="contact-form__field">
			  <label for="yourname" class="bodyfont">Your Name <span>(Required)</span></label>
			  <input value="<?=clean_display_string ($contact_form_array['contact_name'])?>" name="contact_name" class="bodyfont" id="yourname" type="text" placeholder="">
			</div>



			<div class="contact-form__field">
			  <label for="liame" class="bodyfont">Email Address <span>(Required)</span></label>
			  <input value="<?=clean_display_string ($contact_form_array['contact_liame'])?>" name="contact_liame" class="bodyfont" id="liame" type="text" placeholder="">
			  <input name="contact_email" type="hidden" />
			</div>

			<div class="contact-form__field">
			  <label for="phone" class="bodyfont">Telephone <span>(Required)</span></label>
			  <input value="<?=clean_display_string ($contact_form_array['contact_phone'])?>" name="contact_phone" class="bodyfont" id="phone" type="text" placeholder="">
			</div>

			<div class="contact-form__field mb--10px">
				<div class="g-recaptcha" data-sitekey="<?=RECAPTCHA_SITEKEY?>"></div>
			</div>

			<div class="contact-form__field">
			  <label for="website" class="bodyfont">Message</label>
			  <textarea class="bodyfont" id="website" type="text" name="contact_message" placeholder=""><?=clean_display_string ($contact_form_array['contact_message'])?></textarea>
			  <button class="button button--quote" name="contact_save" type="submit" onClick="gtag('event', 'Submit', { 'event_category': 'Form', 'event_label': 'Sidebar'});">Get a Free Quote</button>
			</div>
			<?php
		?>
	</form>

	<a class="button button--quote" href="#" id="button--quote">Get a Free Quote</a>

	<?php
	$random_case_study = case_study::get_random();
	// had to remove this styling as the quote comes styled up from the admin area
	// <p class="quote font--22px font--grey">
	if (!is_null ($random_case_study)) {
	  	?>
		<div class="sidebar__quote no-edge-margin">
			<p class="quote font--22px font--grey">"<?=$random_case_study->get_quote()?>"</p>
			<p class="bodyfont font--14px font--grey mt--10px"><?=clean_display_string ($random_case_study->get_quote_name())?></p>
		</div>
		<?php
	}
	?>

	<?php
	$link_list = new link_list();
	$link_list->set_order_by ("id");
	$links = $link_list->do_search();

	if(count($links > 0)){
	 ?>
    <div class="sidebar__popular-links mb--50px">
      <?php include "assets/svgs/small/icon-link.svg" ?>
      <h3 class="header font--20px">Popular Links</h3>
      <ul class="list--blank">
		<?php
		foreach ($links as $link) {
			?>
			<li>
			  <a class="bodyfont font--linkblue" href="<?=clean_display_string ($link->get_url())?>"><?=clean_display_string ($link->get_title())?></a>
			</li>
        	<?php
		}
		?>
      </ul>
    </div>
		<?php
	}
		?>
    <div class="sidebar__locations inline-hack">
      <img src="/assets/images/global/national-map.png" class="national-reach__map" alt="Russell Richardson National Reach Map">

      <div class="sidebar__locations-text">
        <?php include "assets/svgs/small/icon-pin.svg" ?>
        <h3 class="header font--20px">We Cover...</h3>
        <ul class="list--blank">
          <li class="bodyfont">South Yorkshire</li>
          <li class="bodyfont">West Yorkshire</li>
          <li class="bodyfont">Humberside</li>
          <li class="bodyfont">Derbyshire</li>
          <li class="bodyfont">Greater Manchester</li>
          <li class="bodyfont">And more...</li>
					<li class="bodyfont"><a class="font--linkblue" href="/locations.php">See All Locations</a></li>
        </ul>
      </div>
    </div>

  </div>
</aside>
