<?php
    // Template name: Front page

    get_header();
?>

<div class="container container--rows-gap">
    <div class="row row--top">
        <nav class="fp-nav fp-nav--top">
            <div class="fp-nav__item">
                <a class="fp-nav__link text text--flashy" href="<?php echo SITE_URL; ?>/music/the-madness/" target="_self">The Madness!</a>
            </div>

            <div class="fp-nav__item">
                <a class="fp-nav__link" href="<?php echo SITE_URL; ?>/music/" target="_self">Music</a>
            </div>
        </nav>
    </div>

    <section class="row row--mid fp-content">
        <?php
            $content = get_field("landing_content");

            $has_video = false;

            if (!empty($content)) {
                $video = $content["video"];

                $video_sources = $video["source"];

                if (count($video_sources) > 0) {
                    $has_video = true;

                    $video_poster = $video["poster"];

                    if (!empty($video_poster)) {
                        $video_poster = $video_poster["url"];
                    }
                }
            }
        ?>

        <?php
            $title_parent_classes = "section-title";
            $title_classes = "sr-only";

            if (!$has_video) {
                $title_parent_classes .= " section-title--margin is-visible has-fallback-text";
                $title_classes = false;
            }
        ?>
        <div class="<?php echo $title_parent_classes; ?>">
            <h1 <?php if ($title_classes) { echo "class='" . $title_classes . "'"; } ?>>
                We Don't Care
            </h1>
        </div>

        <?php
            if ($has_video) {
                ?>
                <div class="media" style="--aspect-ratio: 1 / 1" aria-hidden="true">
                    <video
                        <?php if (!empty($video_poster)) { echo "poster='" . $video_poster . "'"; } ?>
                        autoplay
                        controls
                        disablePictureInPicture
                        disableRemotePlayback
                        loop
                        muted
                        playsinline
                    >
                        <?php
                            foreach ($video_sources as $source) {
                                $file = $source["file"];
                                // var_dump($file);

                                $file_src = $file["url"];
                                $file_type = $file["mime_type"];
                                ?>
                                <source src="<?php echo $file_src; ?>" type="<?php echo $file_type; ?>">
                                <?php
                            }
                        ?>

                        We Don't Care
                    </video>
                </div>
                <?php
            }
        ?>

        <?php echo do_shortcode('[contact-form-7 id="102" title="Mailing sign up"]'); ?>
    </section>

    <div class="row row--bottom">
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
