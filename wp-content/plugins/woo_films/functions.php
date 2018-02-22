<?php

function films_get_template_part( $slug, $name = NULL, $load = TRUE ) {
  // Execute code for this part
  do_action( 'get_template_part_' . $slug, $slug, $name );
  // Setup possible parts
  $templates = [];
  if ( isset( $name ) ) {
    $templates[] = $slug . '-' . $name . '.php';
  }
  $templates[] = $slug . '.php';
  // Allow template parts to be filtered
  $templates = apply_filters( 'films_get_template_part', $templates, $slug, $name );

  // Return the part that is found
  return films_locate_template( $templates, $load, FALSE );
}

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
 * inherit from a parent theme can just overload one file. If the template is
 * not found in either of those, it looks in the theme-compat folder last.
 *
 * @param string|array $template_names Template file(s) to search for, in order.
 * @param bool $load If true the template file will be loaded if it is found.
 * @param bool $require_once Whether to require_once or require. Default true.
 *                            Has no effect if $load is false.
 *
 * @return string The template filename if one is located.
 */
function films_locate_template( $template_names, $load = FALSE, $require_once = TRUE ) {
  // No file found yet
  $located = FALSE;
  // Try to find a template file
  foreach ( (array) $template_names as $template_name ) {
    // Continue if template is empty
    if ( empty( $template_name ) ) {
      continue;
    }
    // Trim off any slashes from the template name
    $template_name = ltrim( $template_name, '/' );
    // Check child theme first
    if ( file_exists( trailingslashit( get_stylesheet_directory() ) . 'films-templates/' . $template_name ) ) {
      $located = trailingslashit( get_stylesheet_directory() ) . 'films-templates/' . $template_name;
      break;
      // Check parent theme next
    } elseif ( file_exists( trailingslashit( get_template_directory() ) . 'films-templates/' . $template_name ) ) {
      $located = trailingslashit( get_template_directory() ) . 'films-templates/' . $template_name;
      break;
      // Check theme compatibility last
    } elseif ( file_exists( dirname( __FILE__ ) . '/templates/' . $template_name ) ) {
      $located = dirname( __FILE__ ) . '/templates/' . $template_name;
      break;
    }
  }
  if ( ( TRUE == $load ) && ! empty( $located ) ) {
    load_template( $located, $require_once );
  }

  return $located;
}