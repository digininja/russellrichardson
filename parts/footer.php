<?php
  require_once ("application/classes/category_l1_list.class.php");
  require_once ("application/classes/case_study_list.class.php");
?>
<footer class="site-footer">

  <div class="site-footer__contact">
    <div class="container container--large">

      <a class="site-footer__logo" href="/">
        <img src="/assets/images/global/logo-white.png" alt="Russell Richardson White SVG Logo" width="100%" />
      </a>

      <h6 class="header font--white">
        Free Phone: <a class="font--lightgreen no-line" href="tel:08002946552" onClick="gtag('event', 'Press', { 'event_category': 'Call', 'event_label': 'Footer'});">0800 294 6552</a> <span class="font--grey">or</span> Email: <a class="font--lightgreen" href="mailto:info@russellrichardson.co.uk" onClick="gtag('event', 'Click', { 'event_category': 'Email', 'event_label': 'Footer'});">info@russellrichardson.co.uk</a>
      </h6>

    </div>
  </div>

  <div class="site-footer__main">
    <div class="container container--large">

      <nav class="site-footer__navs">

        <?php

          $service_list = new category_l1_list();
          $service_list->set_order_by ("name");
          $services = $service_list->do_search();

          foreach ($services as $service) {

            $sub_services = $service->get_children();
            echo '<ul class="site-footer__col list--blank">';
            echo '<li class="site-footer__item">
              <a class="bodyfont bodyfont--small font--white no-line"  href="/service/' . clean_display_string ($service->get_url()) . '">
                ' . clean_display_string ($service->get_name()) . '

              </a></li>';

              if (count ($sub_services) > 0) {

                foreach ($sub_services as $sub_service) {

                  echo '<li class="site-footer__item">
                    <a class="bodyfont bodyfont--smaller no-line trans--200ms" href="/service/2/' . clean_display_string ($sub_service->get_url()) . '">
                      ' . clean_display_string ($sub_service->get_name()) .'
                    </a>';

                  echo '</li>';

                }


              }

            echo '</ul>';

          }

         ?>

        <ul class="site-footer__col list--blank">
          <li class="site-footer__item">
            <a class="bodyfont bodyfont--small font--white no-line" href="/case_studies.php">Case Studies</a>
          </li>

          <?php

            $case_study_list = new case_study_list();
            $case_study_list->set_page_size(6);
            $case_study_list->set_order_by('case_study_id');
            $case_studies = $case_study_list->do_search();

            if(!empty($case_studies)){

                foreach($case_studies as $case_study){

                  echo '<li class="site-footer__item">
                    <a class="bodyfont bodyfont--smaller no-line trans--200ms" href="/case_study.php?id=' . clean_display_string ($case_study->get_id()) . '">' . clean_display_string ($case_study->get_name()) . '</a>
                  </li>';


                }


            }
          ?>
        </ul>

        <ul class="site-footer__col list--blank">
          <li class="site-footer__item">
            <a class="bodyfont bodyfont--small font--white no-line" href="#">Other Areas</a>
          </li>

          <li class="site-footer__item">
            <a class="bodyfont bodyfont--smaller no-line trans--200ms" href="/locations.php">Locations</a>
          </li>
          <li class="site-footer__item">
            <a class="bodyfont bodyfont--smaller no-line trans--200ms" href="/advice-centre.php">Advice Centre</a>
          </li>
          <li class="site-footer__item">
            <a class="bodyfont bodyfont--smaller no-line trans--200ms" href="/contact.php">Contact Us</a>
          </li>
          <li class="site-footer__item">
            <a class="bodyfont bodyfont--smaller no-line trans--200ms" href="/about.php">About Us</a>
          </li>
          <li class="site-footer__item">
            <a class="bodyfont bodyfont--smaller no-line trans--200ms" href="/news_landing.php">News</a>
          </li>
          <li class="site-footer__item">
            <a class="bodyfont bodyfont--smaller no-line trans--200ms" href="/certificates-downloads.php">Certificates & Downloads</a>
          </li>
          <li class="site-footer__item">
            <a class="bodyfont bodyfont--smaller no-line trans--200ms" href="/charity.php">Charity</a>
          </li>
        </ul>

        <ul class="site-footer__col site-footer__col--socials list--blank">
          <li class="site-footer__item">
            <p class="bodyfont bodyfont--small font--white" href="#">Socials</p>
          </li>

          <li class="site-footer__item site-footer__item--social">
            <a class="trans--200ms" href="https://www.facebook.com/russellrichardsonuk">
              <?php include "assets/svgs/socials/facebook.svg";?>
            </a>
          </li>

          <li class="site-footer__item site-footer__item--social">
            <a class="trans--200ms" href="https://twitter.com/russellrichuk">
              <?php include "assets/svgs/socials/twitter.svg";?>
            </a>
          </li>

          <li class="site-footer__item site-footer__item--social">
            <a class="trans--200ms" href="http://www.youtube.com/user/russellrichardsonuk">
              <?php include "assets/svgs/socials/youtube.svg";?>
            </a>
          </li>

          <li class="site-footer__item site-footer__item--social">
            <a class="trans--200ms" href="http://www.linkedin.com/company/russell-richardson-&-sons-ltd">
              <?php include "assets/svgs/socials/linkedin.svg";?>
            </a>
          </li>
        </ul>

      </nav>

      <div class="site-footer__bottom">

        <div class="site-footer__left">
          <img srcset="/assets/images/global/credentials_@x1.png 300w,
                       /assets/images/global/credentials_@x2.png 600w"
               sizes="(max-width: 960px) 300px,
                      600px"
               src="/assets/images/global/credentials_@x2.png"
               class="site-footer__credentials"
               alt="Russell Richardson Footer Credentials">
          <p class="bodyfont bodyfont--smaller">Fax: 0114 243 7646 <span>|</span><br class="footer-break">Company Registration No: 1351912</p>
          <p class="bodyfont bodyfont--smaller">Units 11-14, Park House Lane, Sheffield, S9 1XA. <br class="footer-break"><a class="font--white" href="https://goo.gl/maps/6uEDijhwYhn">View on Google Maps</a></p>
          <p class="bodyfont bodyfont--smaller">© <?= date('Y'); ?> Russell Richardson & Sons LTD</p>
        </div>

        <nav class="site-footer__right">
          <ul class="site-footer__inline-nav list--blank">
            <li class="site-footer__item">
              <a class="bodyfont bodyfont--smaller no-line trans--200ms" href="/privacy.php">Privacy</a>
            </li>
            <li class="site-footer__item">
              <a class="bodyfont bodyfont--smaller no-line trans--200ms" href="/terms.php">Terms &amp; Condition</a>
            </li>
            <li class="site-footer__item">
              <a class="bodyfont bodyfont--smaller no-line trans--200ms" href="#">
                <span>Made By</span>
                <?php include "assets/svgs/logos/ignition-ux-logo.svg";?>
              </a>
            </li>
          </ul>
        </nav>

      </div>

    </div>
  </div>

</footer>


<?php include "assets/svgs/all.svg" ?>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/js/modules.min.js"></script>
<script type="text/javascript" src="/assets/js/libraries.min.js"></script>
<script type="text/javascript" src="/assets/js/scripts.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGocS7T9yiF_lAkf1dmTgoSOQWtC17_ts&callback=initMap"></script>

</body>
</html>
