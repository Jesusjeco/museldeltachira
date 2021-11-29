<?php

// Remove the WordPress Version Generator meta tag

remove_action('wp_head', 'wp_generator');
// Removing the admin bar
add_filter('show_admin_bar', '__return_false');

// Adding Feature Image
add_theme_support('post-thumbnails');

/**
 * Disable the wp emoji's
 */
function disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
    add_filter('wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2);
}
add_action('init', 'disable_emojis');
/**
 * Filter function used to remove the tinymce emoji plugin.
 * 
 * @param array $plugins 
 * @return array Difference betwen the two arrays
 */
function disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}
/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function disable_emojis_remove_dns_prefetch($urls, $relation_type)
{
    if ('dns-prefetch' == $relation_type) {
        /** This filter is documented in wp-includes/formatting.php */
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

        $urls = array_diff($urls, array($emoji_svg_url));
    }
    return $urls;
}

/*
* Custom options page
*/
function custom_options_page()
{
    if (function_exists('acf_add_options_page')) {

        acf_add_options_page(array(
            'page_title'     => 'Theme General Settings',
            'menu_title'    => 'Theme Settings',
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
            'redirect'        => false
        ));

        acf_add_options_sub_page(array(
            'page_title'     => 'Theme Header Settings',
            'menu_title'    => 'Header',
            'parent_slug'    => 'theme-general-settings',
        ));

        acf_add_options_sub_page(array(
            'page_title'     => 'Theme Footer Settings',
            'menu_title'    => 'Footer',
            'parent_slug'    => 'theme-general-settings',
        ));
    }
} //custom_options_page

if (!function_exists('mdet_setup')) {
    function mdet_setup()
    {

        custom_options_page();
        //Enabling the excerpt for posts
        add_post_type_support('post', 'excerpt');
        // This theme uses wp_nav_menu() in two locations.
        register_nav_menus(
            array(
                'main' => __('Main')
            )
        );
    }
}
add_action('after_setup_theme', 'mdet_setup');

//****************************************************************************************** */
/*
* Adding scripts and styles to web, when Wordpress triggers the action wp_enqueue_scripts in the header
*/
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('style', get_template_directory_uri() . './style.css', array(), wp_get_theme()->get('Version'));
    wp_enqueue_script('bundle', get_template_directory_uri() . '/dist/bundle.js', array(), wp_get_theme()->get('Version'), true);
    wp_enqueue_style('bundle', get_template_directory_uri() . '/dist/bundle.css', array(), wp_get_theme()->get('Version'));

    //Registering styles when post_carousel_front is called
    //wp_register_style('post_carousel_front', get_template_directory_uri() . "/post_carousel_front.css", null, wp_get_theme()->get('Version'));
});

/*
* Custom menu code - Level 3
*/
function custom_menu_level_3($location)
{
    $menuLocations = get_nav_menu_locations();
    $menu = wp_get_nav_menu_object($menuLocations[$location]);
    $menu_items = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));

    $menuContent = "";
    $menuContent .= <<<ITEM
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
    ITEM;

    if ($menu_items) {

        $current_position = 0;
        $max_items = count($menu_items);
        do {
            $title = $menu_items[$current_position]->title;

            //if there are no more items
            if (!isset($menu_items[$current_position + 1])) {
                $link = $menu_items[$current_position]->url;
                $menuContent .= <<<ITEM
                        <li class="nav-item"><a class="nav-link" href="$link"><h3>$title</h3></a></li>
                    ITEM;
                break;
            }
            //if there are more items
            //if current item is not a father
            if ($menu_items[$current_position]->ID != $menu_items[$current_position + 1]->menu_item_parent) {
                $link = $menu_items[$current_position]->url;
                $menuContent .= <<<ITEM
                    <li class="nav-item"><a class="nav-link" href="$link"><h3>$title</h3></a></li>
                ITEM;
            }
            //if current item is a father - 2nd level
            else {
                $current_parent_1 = $menu_items[$current_position]->ID;
                $menuContent .= <<<ITEM
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><h3>$title</h3></a>
                            <ul class="dropdown-menu">
                ITEM;

                //getting childs level 2
                do {
                    $current_position++;
                    $title = $menu_items[$current_position]->title;

                    //if there are no more next items
                    if (!isset($menu_items[$current_position + 1])) {
                        $link = $menu_items[$current_position]->url;
                        $menuContent .= <<<ITEM
                            <li><a class="dropdown-item" href="$link"><h3>$title</h3></a></li>
                        ITEM;
                        break;
                    }

                    //if the current item is not the father of the next
                    if ($menu_items[$current_position]->ID != $menu_items[$current_position + 1]->menu_item_parent) {
                        $link = $menu_items[$current_position]->url;
                        $menuContent .= <<<ITEM
                            <li><a class="dropdown-item" href="$link"><h3>$title</h3></a></li>
                        ITEM;
                    }
                    // If the current element is the father of the next - 3rd level
                    else {
                        $current_parent_2 = $menu_items[$current_position]->ID;
                        $menuContent .= <<<ITEM
                            <li><a class="dropdown-item" href="#"><h3>$title</h3> &raquo; </a>
                                <ul class="submenu dropdown-menu">
                        ITEM;

                        // Getting childs Level 3
                        do {
                            $current_position++;

                            $title = $menu_items[$current_position]->title;

                            //if there are no more next items
                            if (!isset($menu_items[$current_position + 1])) {
                                $link = $menu_items[$current_position]->url;
                                $menuContent .= <<<ITEM
                                            <li><a class="dropdown-item" href="$link"><h3>$title</h3></a></li>
                                        ITEM;

                                break;
                            }

                            //if the current item is not the father of the next
                            if ($menu_items[$current_position]->ID != $menu_items[$current_position + 1]->menu_item_parent) {
                                $link = $menu_items[$current_position]->url;
                                $menuContent .= <<<ITEM
                                            <li><a class="dropdown-item" href="$link"><h3>$title</h3></a></li>
                                        ITEM;
                            } else {
                                /*
                                * JC 2021/11/03 - If we need a fourth level, the code would be here
                                * TIP: basically, we need to re-use the code from the last while(true).
                                * If you are able to make it recursive, GREAT, but please keep in mind that
                                * The second level uses only "dropdown" class, and from the third level and below,
                                * they use the "submenu dropdown" classes. This will allow you to make them recursive.
                                */

                                break;
                            }
                        } while (isset($menu_items[$current_position + 1]) && $current_parent_2 == $menu_items[$current_position + 1]->menu_item_parent); //while level 3
                        // if the father is still the same

                        $menuContent .= <<<ITEM
                                        </ul>
                                    </li>
                                ITEM;
                    } //else
                    //continue while the next element is also a child
                } while (isset($menu_items[$current_position + 1]) && $current_parent_1 == $menu_items[$current_position + 1]->menu_item_parent);

                $menuContent .= <<<ITEM
                                    </ul>
                                </a>
                            </li>
                        ITEM;
            }
            $current_position++;
        } while ($current_position < $max_items); //do while
    } //if there is a menu to show

    $menuContent .= <<<ITEM
        </ul>
    ITEM;

    echo $menuContent;
} //Custom_menu_level_3

/*
* Adding actions to the header
*/
add_action('wp_body_open', function () {
    if (is_home())
        $banner = get_field('general_banner', get_option('page_for_posts'));
    else
        $banner = get_field('general_banner');
    $banner = isset($banner['main_banner']['url']) ? $banner['main_banner']['url'] : "";

    if ($banner) {
        $content = <<<ITEM
                <style>
                    .main_header {
                        height: 500px;
                        background-image: url($banner);
                        background-color: white;
                        background-repeat: no-repeat;
                        background-size: cover;
                        text-align: center;
                    }
                </style>
                <div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 p-0 main_header position-relative">
                            </div>
                        </div>
                    </div>
                </div>
            ITEM;
        echo $content;
    } else {
        return;
    }
});

function exhibition_front($amount = 3)
{

    $args = array(
        'post_type' => 'exhibition',
        'post_status' => 'publish',
        'posts_per_page' => $amount,
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
} //exhibition_front

function testimonials_front($amount = 2)
{

    $args = array(
        'post_type' => 'testimonial',
        'post_status' => 'publish',
        'posts_per_page' => $amount,
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
            $excerpt = $excerpt = wp_trim_words(get_the_content(), 20);

            $content .= <<<ITEM
                <div class="col-12 col-lg-6">
                    <div class="row align-items-center testimonial-row">
                        <div class="col-12 col-md-4 col-xxl-3">
                            <div class="ratio ratio-1x1 mx-auto testimonial-image-container">
                                <img class="mx-auto rounded-circle testimonial-image" src="$image" alt="">
                            </div>
                        </div>
                        <div class="col-12 col-md-8 col-xxl-9">
                            <h3>$title</h3>
                            <p>$excerpt</p>
                        </div>
                    </div>
                </div>
            ITEM;
        }
        wp_reset_postdata(); //while

        echo $content;
    } //if there is somehting

} //testimonials_front

function posts_front($amount = 4)
{

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $amount,
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
        wp_reset_postdata(); //while

        echo $content;
    } //if there is somehting
} //posts_front

function socials_front($socials)
{

    if (!$socials || !is_array($socials)) {
        return;
    } else {
        $content = "";

        foreach ($socials as $social) {
            $link = $social['link'];
            if ($link) {
                $link_url = esc_url($link['url']);
                //$link_title = esc_html($link['title']);
                $link_target = $link['target'] ? $link['target'] : '_self';
                $link_target = esc_attr($link_target);

                $image = $social['logo']['url'];

                $content .= <<<ITEM
                    <a href="$link_url" target="$link_target">
                        <div class="ratio ratio-1x1 footer-social-img-container">
                            <div><img src="$image" alt="" class="rounded-circle"></div>
                        </div>
                    </a>
                ITEM;
            }
        } //foreach
        echo $content;
    }
}
