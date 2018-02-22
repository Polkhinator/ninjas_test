<?php
/**
 * The Template for displaying single film.
 *
 * @version     1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
get_header();
?>
    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

              <?php do_action( 'films_before_main_content' ); ?>

              <?php while ( have_posts() ) : the_post(); ?>

                <?php films_get_template_part( 'content', 'single-films' ); ?>

              <?php endwhile; // end of the loop. ?>

              <?php do_action( 'films_after_main_content' ); ?>
            </main><!-- #main -->
        </div><!-- #primary -->
      <?php get_sidebar(); ?>
    </div>
<?php get_footer();