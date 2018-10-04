<?php
/**
 * The template part for displaying fancy image carousels
 *
 * @package Checkout
 */
?>

				<?php
					/**
					 * Grab the first gallery from the post
					 */
					$gallery = get_post_gallery( $post->ID, false );

					/**
					 * Make sure we have gallery ID's
					 */
					if ( !empty( $gallery['ids'] ) ) {

						$gallery_ids = explode( ',', $gallery['ids'] );

						if( $gallery_ids ) :

							/**
							 * Wrap the output in the carousel divs
							 */
							$image_output = '<div class="rslides-container"><ul id="lightGallery' . $post->ID . '" class="rslides">';

							foreach( $gallery_ids as $id ) {

								$gallery_image = wp_get_attachment_image_src( $id, 'full' );

								// Get the attachment's caption stored in post_excerpt
								$excerpt = get_post_field( 'post_excerpt', $id );

								// Only show a caption if there is one
								if ( ! empty( $excerpt ) ) {

									$image_excerpt_caption = $excerpt;
									$alt = $excerpt;

								} else {

									$image_excerpt_caption = null;
									$alt = get_the_title( $id );

								}

								// Output the image with captions
								$image_output .= '<li data-sub-html="<h3>'. $image_excerpt_caption . '</h3>" data-src=" ' . $gallery_image[0] . ' "><img src=" ' . $gallery_image[0] . ' " alt="' . esc_attr( $alt ) . '"/></li>';

							}

							$image_output .= '</ul></div>';

							/**
							 * Display the gallery output
							 */
							echo $image_output;

						endif; // $gallery

					}

			?>
