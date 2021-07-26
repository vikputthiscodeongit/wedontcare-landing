<?php
    // Template name: Social

    get_header();
?>

<div class="container container--align-center">
    <div class="row">
        <div class="title">
            <h1 class="visually-hidden"><?php the_title(); ?></h1>
        </div>
    </div>

    <?php
        $query_args = array(
            "post_type" => "entity",
            "post_status" => "publish",
            "posts_per_page" => -1,
            "order" => "ASC"
        );

        $the_query = new WP_Query($query_args);

        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();

                $entity = get_field("entity");

                $social = $entity["social"];
                // var_dump($social);

                if (!empty($social["service"])) {
                    ?>
                    <div class="row">
                        <ul class="socials" aria-label="Social media services">
                            <?php
                                $social_shown = false;

                                foreach ($social["service"] as $service) {
                                    // URL
                                    $url = $service["url"];

                                    if (empty($url))
                                        continue;

                                    // var_dump($url);


                                    // Name
                                    $name = false;

                                    switch ($service["id"]) {
                                        case "youtube":
                                            $name = "YouTube";

                                            break;
                                        default:
                                            $name = ucwords($service["id"]);
                                    }

                                    if (!$name)
                                        continue;

                                    // var_dump($name);


                                    // Set "shown" variable
                                    $social_shown = true;


                                    // Logo
                                    $logo = false;

                                    $base_dir  = trailingslashit(THEME_DIR_PATH);
                                    $dir       = "dist/images/static/social/";
                                    $file_name = $service["id"] . "-white.png";
                                    $files     = glob($base_dir . $dir . $file_name);
                                    // var_dump($files);

                                    if (count($files) > 0) {
                                        $logo = get_theme_file_uri($dir . basename($files[0]));
                                    }

                                    // var_dump($logo);


                                    // Name - class
                                    $name_class = $logo ? "visually-hidden" : false;
                                    // var_dump($name_class);
                                    ?>
                                    <li class="social">
                                        <a class="social__item" href="<?php echo $url; ?>" target="_blank" rel="noopener">
                                            <?php
                                                if ($logo) {
                                                    ?>
                                                    <span class="social__logo media" style="--aspect-ratio: 1 / 1">
                                                        <img src="<?php echo $logo; ?>" alt="<?php echo $name; ?> logo">
                                                    </span>
                                                    <?php
                                                }
                                            ?>

                                            <span class="social__name">
                                                <span class="<?php echo $name_class; ?>"><?php echo $name; ?></span>
                                            </span>
                                        </a>
                                    </li>
                                    <?php
                                }

                                if (!$social_shown) {
                                    ?>
                                    <li class="social">
                                        <span class="social__item">
                                            <span class="social__text">No social media services found.</span>
                                        </span>
                                    </li>
                                    <?php
                                }
                            ?>
                        </ul>
                    </div>
                    <?php
                }
            }

            wp_reset_postdata();
        } else {
            ?>
            <div class="row">
                <div class="text text--center">
                    <p>No social media services found.</p>
                </div>
            </div>
            <?php
        }
    ?>
</div>

<?php get_footer(); ?>
