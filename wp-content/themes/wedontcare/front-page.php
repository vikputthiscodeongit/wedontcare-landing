<?php
    // Template name: Front page

    get_header();
?>

<div class="container container--rows-gap">
    <div class="row row--no-box-gap row--top">
        <nav class="fp-nav fp-nav--top">
            <div class="fp-nav__item">
                <a class="fp-nav__link text text--flashy" href="<?php echo SITE_URL; ?>/music/the-madness/" target="_self">The Madness!</a>
            </div>

            <div class="fp-nav__item">
                <a class="fp-nav__link" href="<?php echo SITE_URL; ?>/music/" target="_self">Music</a>
            </div>
        </nav>
    </div>

    <?php
        $content = get_field("landing_content");

        $video = false;

        if (!empty($content)) {
            if (!empty($content["video"]["source"])) {
                $video = $content["video"];
                // var_dump($video);
            }
        }
    ?>
    <section class="row row--no-box-gap row--mid fp-content">
        <?php
            $title_parent_class = "title";
            $title_class = "sr-only";

            if (!$video) {
                $title_parent_class .= " title--margin text text--center is-visible";
                $title_class = false;
            }
        ?>
        <div class="<?php echo $title_parent_class; ?>">
            <h1 class="<?php echo $title_class; ?>">We Don't Care</h1>
        </div>

        <?php
            if ($video) {
                ?>
                <div class="media" style="--aspect-ratio: 1 / 1" aria-hidden="true">
                    <?php
                        $video_poster = $video["poster"];

                        if (!empty($video_poster)) {
                            $video_poster = $video_poster["url"];
                        }
                    ?>
                    <video
                        poster="<?php echo $video_poster; ?>"
                        autoplay
                        controls
                        disablePictureInPicture
                        disableRemotePlayback
                        loop
                        muted
                        playsinline
                    >
                        <?php
                            foreach ($video["source"] as $source) {
                                $file = $source["file"];
                                // var_dump($file);

                                $file_src = $file["url"];
                                $file_type = $file["mime_type"];
                                ?>
                                <source src="<?php echo $file_src; ?>" type="<?php echo $file_type; ?>">
                                <?php
                            }
                        ?>

                        <span>We Don't Care</span>
                    </video>
                </div>
                <?php
            }
        ?>

        <?php echo do_shortcode('[contact-form-7 id="102" title="Mailing sign up"]'); ?>
    </section>

    <div class="row row--no-box-gap row--bottom">
        <nav class="fp-nav fp-nav--bottom">
            <div class="fp-nav__item">
                <a class="fp-nav__link" href="<?php echo SITE_URL; ?>/social/" target="_self">Social</a>
            </div>

            <div class="fp-nav__item">
                <a class="fp-nav__link" href="<?php echo SITE_URL; ?>/shows/" target="_self">Shows</a>
            </div>
        </nav>
    </div>
</div>

<?php get_footer(); ?>
