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
    <section class="row row--mid fp-content">
        <?php
            $parent_class = "section-title";
            $class = "sr-only";

            if (!$video) {
                $parent_class .= " section-title--margin is-visible";
                $class = false;
            }
        ?>
        <div class="<?php echo $parent_class; ?>">
            <h1 class="<?php if ($class) { echo $class; } ?>">
                We Don't Care
            </h1>
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
