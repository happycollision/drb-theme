<?php get_header(); ?>
	<div role="main" class="clearfix">
	<div id="background_image"></div>
	
	<div id="front_extras">
		<?php get_template_part('pictures'); ?>
		
	<?php $praise = new WP_Query("post_type=praise&showposts=1&orderby=rand");
		if($praise->have_posts()): while ($praise->have_posts()) : $praise->the_post(); 
			$custom = get_post_custom($post->ID);
	?>
		<div id="praise_for_diana">
			<img src="<?php echo get_bloginfo('template_url') . '/images/straight_line.png';?>" />
			<span class="quote"><?php the_content();?></span>
			<span class="quote_source"><?php echo $custom['praise_author'][0];?></span>
		    <?php if ($custom['praise_topic'][0] != ''){ ?>
		    	<span class="quote_topic">on <span class="show_title"><?php echo $custom['praise_topic'][0];?></span></span>			    
		    <?php } ?>
			<img src="<?php echo get_bloginfo('template_url') . '/images/curved_line.png';?>" />	
		</div>
	<?php endwhile; endif; // ending if $praise->have_posts()
	wp_reset_query();?>

		
	</div><!--front_extras-->
			
	<div role="content">
			<?php happycol_content_nav('nav-above');?>
	
			<?php if ( have_posts() ) : ?>
	
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
	
					<?php get_template_part( 'productions', get_post_format() ); ?>
	
				<?php endwhile; ?>
	
			<?php else : ?>
	
				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h3 class="entry-title"><?php echo 'Nothing Found'; ?></h3>
					</header><!-- .entry-header -->
	
					<div class="entry-content">
						<p><?php echo 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.'; ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->
	
			<?php endif; ?>
	<?php happycol_content_nav('nav-below');?>
	
	</div><!--role:content-->
	
	</div><!--role:main-->
<?php get_footer(); ?>