<?php
if (have_posts()) {
    $content = "";
    $offset = "";
    while (have_posts()) {
        the_post();
        $title = get_the_title();
        $image = get_the_post_thumbnail_url();
        $url = get_the_permalink();

        $content .= <<<ITEM
        <div class="col-10 col-sm-6 col-lg-3 px-5 px-lg-3 px-xxl-5">
            <a href="$url" class="d-flex flex-wrap">
                <div class="ratio ratio-4x3">
                    <img src="$image" alt="">
                </div>
                <div class="py-3">
                    <h1 class="post-title">$title</h1>
                </div>
            </a>
        </div>
    ITEM;
    }
    echo $content;
    wp_reset_postdata(); // end while 
}
