<?php
    // Template name: Social

    get_header();
?>

<div class="container container--align-center">
    <div class="row row--space-center row--lg-align-center">
        <ul class="socials">
            <?php
                $query_args = array(
                    "post_type" => "entity",
                    "post_status" => "publish",
                    "posts_per_page" => 3,
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
                            foreach ($social["service"] as $service) {
                                // Link
                                $link = $service["url"];


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
                                ?>
                                <li class="social">
                                    <?php
                                        if ($logo) {
                                            ?>
                                            <div class="media" style="--aspect-ratio: 1 / 1">
                                                <img src="<?php echo $logo; ?>" alt="<?php echo $name; ?>">

                                                <a class="stretched-link" href="<?php echo $link; ?>" target="_blank" rel="noopener">
                                                    <span class="sr-only"><?php echo $name; ?></span>
                                                </a>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="<?php echo $link; ?>" target="_blank" rel="noopener"><?php echo $name; ?></a>
                                            <?php
                                        }
                                    ?>
                                </li>
                                <?php
                            }
                        }
                    }

                    wp_reset_postdata();
                } else {
                    ?>
                    <div class="text text--center">
                        <p>Error message.</p>
                    </div>
                    <?php
                }
            ?>
        </ul>
    </div>
</div>

<?php get_footer(); ?>
