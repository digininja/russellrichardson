<?php

require_once ("application/classes/news_category.class.php");
require_once ("application/classes/news_category_list.class.php");

$news_category_list = new news_category_list();
$news_category_list->set_order_by ("name");
$news_categories = $news_category_list->do_search();

?>
<aside class="sidebar sidebar--news pos--rel">
      <div class="sidebar__popular-links">
        <?php include "assets/svgs/small/icon-news.svg" ?>
        <h3 class="header font--18px font--grey">News Categories</h3>
        <ul class="list--blank mt--10px">

          <?php

            foreach ($news_categories as $news_category) {

          ?>



          <li>
            <a class="bodyfont font--16px font--linkblue" href="news_category.php?id=<?=clean_display_string ($news_category->get_id())?>"><?=clean_display_string ($news_category->get_name())?></a>
          </li>
          

          <?php
            }
          ?>
        </ui>
      </div>
</aside>
