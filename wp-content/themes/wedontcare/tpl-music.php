<?php
    // Template name: Music

    get_header();
?>

<section class="container container--align-center">
    <div class="row row--space-center row--lg-align-center row--lg-direction-reverse">



        <!--
            Pagina titel hier
         -->



        <?php
            $query_args = array(
                "post_type" => "music",
                "post_status" => "publish",
                "posts_per_page" => 3
            );

            $the_query = new WP_Query($query_args);

            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();

                    $attrs = get_field("music_attrs");
                    // var_dump($attrs);

                    $class = "box box--lg-4";

                    $id = $attrs["id"];

                    if (!empty($id)) {
                        $class .= " box--" . $id;
                    }

                    $artwork = get_the_post_thumbnail($post->ID, "small");

                    if (empty($artwork)) {



                        // Code



                    }

                    $link = get_permalink();
                    // var_dump($class, $id, $artwork, $link);
                    ?>
                    <div class="<?php echo $class; ?>">
                        <div class="media media--filter media--filter-grayscale" style="--aspect-ratio: 1 / 1">
                            <?php echo $artwork; ?>

                            <a class="stretched-link" href="<?php echo $link; ?>" target="_self">
                                <span class="sr-only"><?php the_title(); ?></span>
                            </a>
                        </div>
                    </div>
                    <?php
                }

                wp_reset_postdata();
            } else {



                // Degelijke error maken



                ?>
                <div class="text text--center">
                    <p>Error message.</p>
                </div>
                <?php
            }
        ?>
    </div>
</section>

<?php get_footer(); ?>
