<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
?>
<article <?php post_class(); ?>>
  <?php if ( has_post_thumbnail() ): ?>
    <?php the_post_thumbnail(); ?>
  <?php endif; ?>
  <?php the_title( '<h1>', '</h1>' ); ?>
  <?php $subtitle = get_post_meta( $post->ID, 'subtitle', TRUE ); ?>
  <?php if ( ! empty( $subtitle ) ): ?>
      <h3><?php echo $subtitle; ?></h3>
  <?php endif; ?>
    <p><?php echo get_the_term_list( $post->ID, 'films_category', __( 'Categories: ' ), ', ', '' ); ?></p>
  <?php the_content(); ?>
  <?php $price = get_post_meta( $post->ID, 'woo_price', TRUE ); ?>
    <h3><?php _e( 'Price' ); ?>: <span>$<?php echo $price; ?></span></h3>
</article>