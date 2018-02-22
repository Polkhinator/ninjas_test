<?php

class Films_Product_Data_Store_CPT extends WC_Product_Data_Store_CPT implements WC_Object_Data_Store_Interface, WC_Product_Data_Store_Interface {


  /**
   * Method to read a product from the database.
   */
  public function read( &$product ) {
    $product->set_defaults();
    $post_object = get_post( $product->get_id() );


    $product->set_props( [
      'name'              => $post_object->post_title,
      'slug'              => $post_object->post_name,
      'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : NULL,
      'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : NULL,
      'status'            => $post_object->post_status,
      'description'       => $post_object->post_content,
      'short_description' => $post_object->post_excerpt,
      'parent_id'         => $post_object->post_parent,
      'menu_order'        => $post_object->menu_order,
      'reviews_allowed'   => 'open' === $post_object->comment_status,
    ] );

    $this->read_attributes( $product );
    $this->read_downloads( $product );
    $this->read_visibility( $product );
    $this->read_product_data( $product );
    $this->read_extra_data( $product );
    $product->set_object_read( TRUE );
  }
}