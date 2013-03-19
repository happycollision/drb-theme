<?php
/**
 * The default template for displaying content
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
		<?php if(is_page('resume')):else:?>
		<header class="entry-header">
			<?php if ( is_sticky() ) : ?>
				<hgroup>
					<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
					<h4 class="entry-format"><?php _e( 'Featured', 'happycol' ); ?></h4>
				</hgroup>
			<?php else : ?>
			<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
			<?php endif; ?>

			<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php happycol_posted_on(); ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>

			<?php if ( comments_open() && ! post_password_required() ) : ?>
			<div class="comments-link">
				<?php comments_popup_link( '<span class="leave-reply">' . 'Reply' . '</span>', '1', '%' ); ?>
			</div>
			<?php endif; ?>
		</header><!-- .entry-header -->
		<?php endif;?>

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php the_content( 'Continue reading <span class="meta-nav">&rarr;</span>' ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . 'Pages:' . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>

		<?php edit_post_link( 'Edit', '<span class="edit-link">', '</span>' ); ?>
	</article><!-- #post-<?php the_ID(); ?> -->
