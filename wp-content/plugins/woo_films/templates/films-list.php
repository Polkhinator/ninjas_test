<?php
/**
 * The Template for displaying [films_list] shortcode
 * This template can be overridden by copying it to
 * yourtheme/films-templates/films-list.php.
 *
 * @version    1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
} ?>
<article <?php post_class(); ?>>
    <a href="<?php the_permalink(); ?>">
      <?php if ( has_post_thumbnail() ): ?>
        <?php the_post_thumbnail(); ?>
      <?php endif; ?>
      <?php the_title( '<h3>', '</h3>' ); ?>
    </a>
    <p><?php echo get_the_term_list( $post->ID, 'films_category', __( 'Categories: ' ), ', ', '' ); ?></p>
  <?php $subtitle = get_post_meta( $post->ID, 'subtitle', TRUE ); ?>
  <?php if ( ! empty( $subtitle ) ): ?>
      <p><?php echo $subtitle; ?></p>
  <?php endif; ?>
  <?php the_excerpt(); ?>
  <?php $price = get_post_meta( $post->ID, 'woo_price', TRUE ); ?>
    <h3>Price: <span>$<?php echo $price; ?></span></h3>
    <hr>
</article>