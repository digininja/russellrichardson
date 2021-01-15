<?php
require_once ("application/includes/constants.inc.php");
require_once ("application/classes/news.class.php");
require_once ("application/classes/recaptchalib.php");

$menu_location = "contact";
$meta_title = "Contact Us | Russell Richardson";
$meta_description = "Contact by phone, email or via our contact form and one of our expert team will get back you as soon as possible.";

include('parts/header.php');

$validation_errors = array();
$result = null;
$submission = false;

$form_array = array (
					"yourname" => "",
					"liame" => "",
					"email" => "",
					"phone" => "",
					"message" => "",
				);


if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$submission = true;

	foreach ($form_array as $name => $value) {
		if (array_key_exists ($name, $_POST)) {
			if (is_array ($_POST[$name])) {
				$form_array[$name] = $_POST[$name];
			} else {
				$form_array[$name] = trim ($_POST[$name]);
			}
		}
	}

	if ($form_array["yourname"] == "") {
		$validation_errors[] = "Please could you confirm your name. ";
	}
	if ($form_array["email"] != "") {
		$validation_errors[] = "";
	}
	if ($form_array["liame"] == "") {
		$validation_errors[] = "Please provide an email address.";
	} elseif (!preg_match ("/..*@..*/", $form_array["liame"])) {
		$validation_errors[] = "Please provide a valid email address";
	}

	if ($form_array["phone"] == "") {
		$validation_errors[] = "You must provide a phone number";
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
				$validation_errors[] = "Failed to answer Captcha correctly";
			}
		} else {
			$validation_errors[] = "Failed to answer Captcha correctly";
		}
	}

	if (count ($validation_errors) == 0) {
		# Email to sender
		$mail_body = "";

		$mail_body .= "Name: " . $form_array['yourname'] . "\n\r";
		$mail_body .= "Email: " . $form_array['liame'] ."\n\r";
		$mail_body .= "Phone: " . $form_array['phone'] ."\n\r";
		$mail_body .= "Message: " . $form_array['message'] ."\n\r";

		$headers = 'From: info@russellrichardson.co.uk'  . "\r\n";
		$result = mail ("info@russellrichardson.co.uk", "Contact form from Russell Richardson website", $mail_body, $headers);
	} else {
		/*
		print "not sending";
		var_dump_pre ($validation_errors);
		*/
	}
}

?>
<main class="site-content" data-page="contact">
<div class="container container--large inline-hack">


	<nav class="breadcrumb">
		<ul class="breadcrumb__nav list--blank">
			<li class="breadcrumb__item bodyfont bodyfont--smaller">
				<a class="font--linkblue" href="/">Home</a>
			</li>
			<li class="breadcrumb__item bodyfont bodyfont--smaller">Contact</li>
		</ul>
		<h1 class="breadcrumb__header header">Contact</h1>
	</nav>

	<section class="main-content main-content--contact">

	      <div class="contact">
	        <div class="contact__top">

	          <div class="contact__top-text ml--0px">
	            <h2 class="header font--24px">Get a Free, Confidential Quote:</h2>
	            <p class="bodyfont">If you would like a quotation or would like to discuss your requirements in more detail, please complete our contact form opposite. Alternatively, call or email us.</p>
	          </div>

	          <a class="button button--slim" href="mailto:info@russellrichardson.co.uk" onClick="gtag('event', 'Click', { 'event_category': 'Email', 'event_label': 'ContactPage'});">Email Us</a>
	          <span class="seperator font--16px mt--40px mb--40px o--2">or</span>
	          <p class="bodyfont mb--5px">Call Us On:</p>
	          <?php include "assets/svgs/small/icon-phone-fill.svg";?>
	          <a class="font--40px font--black weight--black no-line ml--10px" href="tel:08002946552" onClick="gtag('event', 'Press', { 'event_category': 'Call', 'event_label': 'ContactPage'});">0800 294 6552</a>
	        </div>

	        <div class="contact__map mt--60px mb--40px" id="map"></div>
	        <div class="contact__info">
	          <h2 class="header font--24px">Russell Richardson & Sons Ltd</h2>
	          <ul class="list--blank bodyfont">
							<li>Units 11-14,</li>
							<li>Park House Lane,</li>
							<li>Sheffield,</li>
							<li>S9 1XA,</li>
							<li>United Kingdom</li>

	          </ul>

	          <ul class="list--blank bodyfont">
	            <li>Tel: 0800 294 6552,</li>
	            <li>Fax: 0114 243 7646,</li>
	            <li>Email: info@russellrichardson.co.uk</li>
	          </ul>
	        </div>
	      </div>

	    </section>

	    <aside class="sidebar sidebar--contact pos--rel">

	      <div class="contact__form">
	        <?php include "assets/svgs/padlock.svg";?>
	        <h3 class="header font--black font--20px">Complete our confidential call back form. Our team will call you back within <span class="font--linkblue">1 hour</span>.</h3>

					<?php
					if ($result === true) {
						?>
						<div class="contact-result contact-success-container mt--20px">
							<p class="bodyfont">Thanks, message sent</p>
						</div>
						<?php
					}
						?>

						<?php
						if (count ($validation_errors) > 0) {
							?>
							<div class="contact-result contact-error-container mt--20px">
							<p class="bodyfont">Ooops! Something went wrong there. Please review the below and try again.</p>

								<ul>
									<?php
									foreach ($validation_errors as $error) {
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

					<form class="contact-form contact-form--contact inline-hack trans--200ms mt--20px <?= ($submission ? 'reveal' : '') ?>" action="/contact.php" method="post" id="quote-form">

							<div class="contact-form__field">
							  <label for="yourname" class="bodyfont">Your Name <span>(Required)</span></label>
							  <input value="<?=clean_display_string ($form_array['yourname'])?>" name="yourname" class="bodyfont" id="yourname" type="text" placeholder="" />
							</div>

							<div class="contact-form__field">
							  <label for="liame" class="bodyfont">Email Address <span>(Required)</span></label>
							  <input name="liame" class="bodyfont" id="liame" value="<?=clean_display_string ($form_array['liame'])?>" type="text" placeholder="" />
							  <input name="email" type="hidden" />
							</div>

							<div class="contact-form__field">
							  <label for="phone" class="bodyfont">Telephone <span>(Required)</span></label>
							  <input name="phone" class="bodyfont" id="phone" value="<?=clean_display_string ($form_array['phone'])?>" type="text" placeholder="" />
							</div>
							<div class="contact-form__field">

								<div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITEKEY?>"></div>

							</div>
							<div class="contact-form__field">
							  <label for="website" class="bodyfont">Message</label>
							  <textarea class="bodyfont" id="website" type="text" name="message" placeholder=""><?=clean_display_string ($form_array['message'])?></textarea>
							  <button class="button button--quote" name="save" type="submit" onClick="gtag('event', 'Submit', { 'event_category': 'Form', 'event_label': 'ContactPage'});">Contact</button>
							</div>
							<?php
						?>
					</form>
	      </div>

	    </aside>

	  </div>
	</main>

	<?php
	include('parts/footer.php');
	?>
