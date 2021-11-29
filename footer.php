<?php
$theme = get_fields('option');
?>
<footer class="pt-5">
    <div class="footer-container">
        <div class="footer-container-left py-4">
            <div class="d-flex align-items-center"><img src="<?= $theme['theme_footer']['image']['url'] ?>" alt=""></div>
            <div class="d-inline-flex gap-5 align-items-center px-5">
                <?php socials_front($theme['theme_footer']['social_icons']); ?>
            </div>
        </div>
        <div class="py-4 ps-3" style="background-color: #E5E7F6;">
            <div style="border-left: 5px solid; padding-left: 15px;">
                <?= $theme['theme_footer']['text'] ?>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .footer-container-left {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 15px;
        background-color: #2792D0;
    }

    .footer-social-img-container {
        width: 50px;
    }

    @media (min-width: 576px) {}

    @media (min-width: 768px) {}

    @media (min-width: 992px) {}

    @media (min-width: 1200px) {
        .footer-container {
        display: grid;
        grid-template-columns: 3fr 1fr;
        gap: 15px;
    }

    .footer-container-left {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 15px;
        background-color: #2792D0;
    }

    .footer-social-img-container {
        width: 50px;
    }
    }

    @media (min-width: 1400px) {}
</style>

<?php wp_footer() ?>
</body>

</html>