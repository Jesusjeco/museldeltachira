<?php
/*
* Template name: Index - default
*/
get_header();

if (is_home()) { ?>
    <div class="pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 pb-4">
                    <h1><?php echo get_the_title(get_option('page_for_posts')) ?></h1>
                </div>
                <?php get_template_part('posts/content'); ?>
            </div>
        </div>
    </div>
    <?php

} else {

    if (have_posts()) {
        while (have_posts()) {
            the_post(); ?>
            <div class="pt-4">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h1><?php the_title() ?></h1>
                        </div>
                        <div class="col-12">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            </div>
<?php

        }
        wp_reset_postdata(); // end while
    } //end if
    else {
        //No content Found
    } // end else
}
get_footer();
