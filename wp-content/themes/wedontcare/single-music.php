<?php
    get_header();

    $attrs = get_field("music_attrs");
?>

<div class="container container--below-lg-max-width">
    <div class="row row--align-start row--below-lg-justify-center row--box-gap-compact">
        <div class="box box--lg-7 ms-artwork">
            <?php
                $artwork = get_the_post_thumbnail($post->ID, "medium", array("loading" => false));
                // var_dump($artwork);
            ?>
            <div class="media media--underlay" style="--aspect-ratio: 1/1;">
                <?php
                    if (!empty($artwork)) {
                        echo $artwork;
                    } else {
                        ?>
                        <div class="flex-center text text--center">
                            <p>Artwork not available.</p>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>

        <div class="box box--lg-5 ms-stream">
            <div class="streaming">
                <?php
                    // Logo
                    $logo = $attrs["logo"];

                    if (!empty($logo)) {
                        $logo = wp_get_attachment_image($logo["ID"], "extra_small", false, array("loading" => false));
                    }

                    // var_dump($logo);


                    // Title
                    $title_parent_class = "title";
                    $title_class = "sr-only";

                    if (empty($logo)) {
                        $title_parent_class .= " text text--center";
                        $title_class = false;
                    }
                ?>
                <div class="streaming__top">
                    <?php
                        if ($logo) {
                            ?>
                            <div class="streaming__logo">
                                <?php echo $logo; ?>
                            </div>
                            <?php
                        }
                    ?>

                    <div class="<?php echo $title_parent_class; ?>">
                        <h1 class="<?php echo $title_class; ?>"><?php the_title(); ?></h1>
                    </div>
                </div>

                <?php
                    $services = get_field("music_streaming_services");
                    // var_dump($services);

                    if (!empty($services)) {
                        ?>
                        <div class="streaming__main">
                            <ul class="services" aria-label="Streaming services">
                                <?php
                                    foreach ($services as $service => $url) {
                                        // URL
                                        if (empty($url))
                                            continue;

                                        // var_dump($url);


                                        // Name
                                        $name = str_replace("_", " ", $service);

                                        switch ($name) {
                                            case "pre save":
                                                $name = "Pre-save";

                                                break;
                                            case "soundcloud":
                                                $name = "SoundCloud";

                                                break;
                                            case "youtube":
                                                $name = "YouTube";

                                                break;
                                            default:
                                                $name = ucwords($name);
                                        }

                                        // var_dump($name);


                                        // Logo
                                        $logo = false;

                                        $base_dir  = trailingslashit(THEME_DIR_PATH);
                                        $dir       = "dist/images/static/streaming/";
                                        $file_name = str_replace("_", "-", $service);
                                        $files     = glob($base_dir . $dir . $file_name . "*");
                                        // var_dump($files);

                                        if (count($files) > 0) {
                                            if (count($files) === 1) {
                                                $logo = get_theme_file_uri($dir . basename($files[0]));
                                            } else {
                                                $colors = ["color", "black", "white"];

                                                foreach ($colors as $color) {
                                                    foreach ($files as $logo_version) {
                                                        if ($logo)
                                                            break;

                                                        if (strpos($logo_version, $color))
                                                            $logo = get_theme_file_uri($dir . basename($logo_version));
                                                    }
                                                }
                                            }
                                        }

                                        // var_dump($logo);
                                        ?>
                                        <li class="service">
                                            <a class="service__link" href="<?php echo $url; ?>" target="_blank" rel="noopener">
                                                <?php
                                                    if ($logo) {
                                                        ?>
                                                        <span class="service__logo">
                                                            <img src="<?php echo $logo; ?>" alt="<?php echo $name; ?>">
                                                        </span>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <span class="service__name">
                                                            <span><?php echo $name; ?></span>
                                                        </span>
                                                        <?php
                                                    }
                                                ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                ?>
                            </ul>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
    $bg = $attrs["bg"];

    if (!empty($bg)) {
        $bg = wp_get_attachment_image($bg["ID"], "full", false, array("loading" => false));
        // var_dump($bg);
        ?>
        <div class="background">
            <?php echo $bg; ?>
        </div>
        <?php
    }
?>

<?php get_footer(); ?>
