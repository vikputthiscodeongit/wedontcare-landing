<?php
    // Template name: Social

    get_header();
?>

<div class="container container--align-center">
    <div class="row">
        <div class="title">
            <h1 class="sr-only"><?php the_title(); ?></h1>
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
                                        return;

                                    // var_dump($name);


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


                                    // Link
                                    $link_class = "stretched-link";

                                    if (!$logo) {
                                        $link_class .= " flex-center text text--center";
                                    }

                                    // var_dump($link_class);


                                    // Title
                                    $name_class = "sr-only";

                                    if (!$logo) {
                                        $name_class = false;
                                    }

                                    // var_dump($name_class);
                                    ?>
                                    <li class="social">
                                        <div class="media" style="--aspect-ratio: 1 / 1">
                                            <?php
                                                if ($logo) {
                                                    ?>
                                                    <img src="<?php echo $logo; ?>" alt="<?php echo $name; ?>">
                                                    <?php
                                                }
                                            ?>

                                            <a class="<?php echo $link_class; ?>" href="<?php echo $url; ?>" target="_blank" rel="noopener">
                                                <span class="<?php echo $name_class; ?>"><?php echo $name; ?></span>
                                            </a>
                                        </div>
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
