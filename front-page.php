<?php
/*
* Template name: Front page
*/
get_header();

if (have_posts()) {
    while (have_posts()) {
        the_post(); ?>
        <div class="pt-5">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <?php
                    exhibition_front();
                    ?>
                </div>

            </div>
        </div>

        <div class="pt-5">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12 pb-3">
                        <h1>Testimonios</h1>
                    </div>
                    <?php testimonials_front(); ?>
                </div>
            </div>
        </div>

        <div class="pt-5">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12 pb-3">
                        <h1>Noticias y eventos</h1>
                    </div>
                    <?php posts_front(); ?>
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
get_footer();
