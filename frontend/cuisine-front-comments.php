<?php

/**
 * Cuisine Front comments functions
 * 
 * Handles the front comments functions.
 *
 * @author 		Chef du Web
 * @category 	Front
 * @package 	Cuisine
 */

	/**
	* get comments
	*
 	* @access public
	* @return html
	*/
	function cuisine_comment( $comment, $args, $depth ) {
		
		$GLOBALS['comment'] = $comment;
		$template_url = get_bloginfo('template_url','raw');?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">

					<div class="author-avatar">
						<?php 

						$avatar_size = 50;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 40;

						echo get_avatar( $comment, $avatar_size );

						?>
					</div>
					<?php

						/* translators: 1: comment author, 2: date and time */
						echo '<div class="comment-user-info">';
		
							echo '<span class="fn">'.get_comment_author_link().'</span> <span class="says">zei:</span>';
							edit_comment_link( 'Bewerk', '<span class="edit-link">', '</span>' );

						echo '</div>';
					?>

				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation">UW reactie wordt eerst goedgekeurd.</em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php echo cuisine_relative_time($comment->comment_date_gmt)?> | <?php comment_reply_link( array_merge( $args, array( 'reply_text' => 'Reageer <span>&uarr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
			<div class="clearfix"></div>
		</article><!-- #comment-## -->

	<?php
	
	}
	

?>