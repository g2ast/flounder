<?php
/**
 * The template for displaying image attachments.
 *
 * @package Flounder
 */

get_header();
?>

	<div id="primary" class="content-area image-attachment">
		<div id="content" class="site-content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-area">
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">

					<div class="entry-attachment">
						<div class="attachment">
							<?php
								/**
								 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
								 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
								 */
								$attachments = array_values( get_children( array(
									'post_parent'    => $post->post_parent,
									'post_status'    => 'inherit',
									'post_type'      => 'attachment',
									'post_mime_type' => 'image',
									'order'          => 'ASC',
									'orderby'        => 'menu_order ID'
								) ) );
								foreach ( $attachments as $k => $attachment ) {
									if ( $attachment->ID == $post->ID )
										break;
								}
								$k++;
								// If there is more than 1 attachment in a gallery
								if ( count( $attachments ) > 1 ) {
									if ( isset( $attachments[ $k ] ) )
										// get the URL of the next image attachment
										$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
									else
										// or get the URL of the first image attachment
										$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
								} else {
									// or, if there's only 1 image, get the URL of the image
									$next_attachment_url = wp_get_attachment_url();
								}
							?>

							<a href="<?php echo $next_attachment_url; ?>" title="<?php the_title_attribute(); ?>" rel="attachment"><?php
								$attachment_size = apply_filters( 'flounder_attachment_size', array( 1200, 1200 ) ); // Filterable image size.
								echo wp_get_attachment_image( $post->ID, $attachment_size );
							?></a>
						</div><!-- .attachment -->

						<?php if ( ! empty( $post->post_excerpt ) ) : ?>
						<div class="entry-caption">
							<?php the_excerpt(); ?>
						</div><!-- .entry-caption -->
						<?php endif; ?>
					</div><!-- .entry-attachment -->

					<?php the_content(); ?>
					<?php
						wp_link_pages( array(
							'before' => '<div class="page-links">' . __( 'Pages:', 'flounder' ),
							'after'  => '</div>',
						) );
					?>

					</div><!-- .entry-content -->
					
					<?php if ( is_singular() ) {
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() )
							comments_template();
					} else {
						flounder_comment_link( '<div class="comment-links clearfix">', '</div>' ); 
					} ?>
					
				</div><!-- .entry-area -->
				
				<div class="entry-meta sidebar-bg"></div>
				<footer class="entry-meta">
					<i class="icon format-icon dashicons dashicons-format-image"></i>
					<?php flounder_posted_on(); ?>
					<?php
						$metadata = wp_get_attachment_metadata();
						
						printf( '<div class="meta full-size"><a href="%1$s">%2$s &times; %3$s</a></div>',
							esc_url( wp_get_attachment_url() ),
							$metadata['width'],
							$metadata['height']
						);

						printf( '<div class="meta parent-post"><a href="%1$s" title="Return to %2$s" rel="gallery">%3$s</a></div>',
							esc_url( get_permalink( $post->post_parent ) ),
							esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
							get_the_title( $post->post_parent )
						);
					?>

					<?php edit_post_link( __( 'Edit This', 'flounder' ), '<div class="meta edit-link">', '</div>' ); ?> 

				</footer><!-- .entry-meta -->
			</article><!-- #post-<?php the_ID(); ?> -->
			
			<nav role="navigation" id="image-navigation" class="navigation image-navigation">
				<div class="nav-previous"><?php previous_image_link( false, __( '<i class="icon inline  dashicons dashicons-arr-left"></i> Previous', '_s' ) ); ?></div>
				<div class="nav-next"><?php next_image_link( false, __( 'Next <i class="icon inline  dashicons dashicons-arr-right"></i>', '_s' ) ); ?></div>
			</nav><!-- #image-navigation -->

		<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>