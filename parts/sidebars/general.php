<?php
require_once ("application/classes/news.class.php");
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
		#$contact_result = mail ("jon@joncolegate.co.uk", "Free quote request from Russell Richardson website", $mail_body, $headers);

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
<aside class="sidebar sidebar--home pos--rel">
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
	<form class="contact-form contact-form--homepage inline-hack trans--200ms <?= ($submission ? 'reveal' : '') ?>" action="" method="post" id="quote-form">


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

  <ul class="perks perks--homepage list--blank pos--rel trans--400ms" id="perks">
    <li class="perks__item">
      <p class="bodyfont weight--black">On-site Collection</p>
    </li>
    <li class="perks__item">
      <p class="bodyfont weight--black">Fully Certified Services</p>
    </li>
    <li class="perks__item">
      <p class="bodyfont weight--black">National Coverage</p>
    </li>
    <li class="perks__item">
      <p class="bodyfont weight--black">100% Recycled – No Landfill</p>
    </li>
    <li class="perks__item">
      <p class="bodyfont weight--black">Vehicle Tracking</p>
    </li>
    <li class="perks__item">
      <p class="bodyfont weight--black">Fast Response</p>
    </li>
    <li class="perks__item">
      <p class="bodyfont weight--black">40 Year’s Experience</p>
    </li>
    <li class="perks__item">
      <p class="bodyfont weight--black">Security Vetted Staff</p>
    </li>
  </ul>

  <div class="news-snippet pos--rel trans--200ms" id="news-snippet">
    <svg class="icon icon--news">
      <use xlink:href="#news-icon"/>
    </svg>
    <h4 class="header header--icon font--18px font--grey">Latest News & Advice</h4>
    <a class="bodylink font--14px trans--200ms" href="news.php">View All</a>

	<?php
	$latest_news = news::get_latest();
	if (!is_null ($latest_news)) {

		if(is_array($latest_news)){

			$latest_news = $latest_news[0];

		}

		$image = $latest_news->get_image();

		?>
		<a class="bodylink font--22px trans--200ms" href="news.php?id=<?= $latest_news->get_id() ?>"><?= clean_display_string ($latest_news->get_name()) ?></a>
		<p class="bodyfont"><?=clean_display_string ($latest_news->get_summary())?></p>

		<?php
	}
	?>

  </div>

</aside>
