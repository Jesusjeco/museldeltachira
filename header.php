<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    $title = !is_home() ? get_the_title() : get_the_title(get_option('page_for_posts'));
    wp_head();
    $theme = get_fields('option'); ?>

    <title><?= $title; ?></title>
</head>

<body>
    <header class="fixed-top">
        <nav class="navbar navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo home_url() ?>">
                    <img src="<?= $theme['general_theme']['main_logo']['url'] ?>" alt="" style="height: 50px;object-fit: contain;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Museo del Táchira</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <?php custom_menu_level_3('main'); ?>
                    </div>
                    <div>
                        <div class="ratio ratio-1x1 w-75">
                            <img src="<?= $theme['general_theme']['menu_logo']['url'] ?>" alt="" style="
                            object-fit: contain;
                            object-position: bottom;">
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <?php wp_body_open() ?>