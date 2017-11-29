<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 */

global $SMTheme;
?>
        <div class="entry-content">

                <h1 class="post-title"><?php the_title(); ?></h1>

                <?php the_post_thumbnail(
                                'post-thumbnail',
                                array("class" => $SMTheme->get( 'layout','imgpos' ) . " featured_image")
                ); ?>
                <?php the_content( ); ?>
                <?php wp_link_pages( array(
                                        'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'letheme' ) . '</span>',
                                        'after'       => '</div>',
                                        'link_before' => '<span>',
                                        'link_after'  => '</span>',
                                ) ); ?>

        </div><!-- .entry-content -->