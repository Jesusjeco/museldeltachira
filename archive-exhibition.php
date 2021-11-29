<?php
/*
* Template name: Exhibition main page
*/
get_header(); ?>

<div class="pt-4">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 pb-4">
                <h1><?php the_title() ?></h1>
            </div>
            <?php get_template_part('exhibitions/content'); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>