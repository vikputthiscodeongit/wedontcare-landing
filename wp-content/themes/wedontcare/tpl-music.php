<?php
    // Template name: Music

    get_header();
?>

<section class="container container--align-center container--below-wide-max-width">
    <div class="row">
        <div class="title">
            <h1 class="visually-hidden"><?php the_title(); ?></h1>
        </div>
    </div>

    <div class="row row--below-wide-justify-center x-scroller x-scroller--wide">
        <?php
            $query_args = array(
                "post_type" => "music",
                "post_status" => "publish",
                "posts_per_page" => -1
            );

            $the_query = new WP_Query($query_args);

            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();

                    $attrs = get_field("music_attrs");
                    // var_dump($attrs);


                    // Box
                    $box_class = "box box--wide-4";

                    $id = $attrs["id"];

                    if (!empty($id)) {
                        $box_class .= " box--" . $id;
                    }

                    // var_dump($box_class);


                    // Artwork
                    $artwork = get_the_post_thumbnail($post->ID, "small");
                    // var_dump($artwork);


                    // Link
                    $url = get_permalink();
                    // var_dump($url);

                    $link_class = "stretched-link";

                    if (empty($artwork)) {
                        $link_class .= " flex-center title text";
                    }

                    // var_dump($link_class);


                    // Title
                    $title_class = "visually-hidden";

                    if (empty($artwork)) {
                        $title_class = false;
                    }

                    // var_dump($title_class);
                    ?>
                    <div class="<?php echo $box_class; ?>">
                        <div class="media media--filter media--filter-grayscale" style="--aspect-ratio: 1 / 1">
                            <?php
                                if (!empty($artwork)) {
                                    echo $artwork;
                                }
                            ?>

                            <a class="<?php echo $link_class; ?>" href="<?php echo $url; ?>" target="_self">
                                <h2 class="<?php echo $title_class; ?>"><?php the_title(); ?></h2>
                            </a>
                        </div>
                    </div>
                    <?php
                }

                wp_reset_postdata();
            } else {
                ?>
                <div class="text text--center">
                    <p>No music found.</p>
                </div>
                <?php
            }
        ?>
    </div>
</section>

<?php get_footer(); ?>
