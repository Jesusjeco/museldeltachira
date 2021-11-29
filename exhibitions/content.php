<?php

$args = array(
    'post_type' => 'exhibition',
    'post_status' => 'publish',
    'order' => 'DESC',
    'orderby' => 'date',
    'offset' => 0,
);

$loop = new WP_Query($args);
if ($loop->have_posts()) {
    $content = "";
    $offset = "";
    while ($loop->have_posts()) {
        $loop->the_post();
        $title = get_the_title();
        $image = get_the_post_thumbnail_url();
        $excerpt = $excerpt = wp_trim_words(get_the_content(), 30);
        $url = get_permalink();

        $offset == "" ? $offset = "offset-md-1" : $offset = "";

        $content .= <<<ITEM
            <div class="$offset col-10 pb-5">
                <a class="d-flex" href="$url">
                    <div class="row align-items-center exhibition-card">
                        <div class="col-12 col-md-6">
                            <img class="exhibition-image" src="$image" alt="">
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="py-4 exhibition-text">
                                <h1>$title</h1>
                                <p>$excerpt</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        ITEM;
    }
    wp_reset_postdata(); //while

    echo $content;
} //if there is somehting
