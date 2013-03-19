<?php
$slides = get_posts('post_type=happycol_slides&orderby=rand&posts_per_page=-1');

if(count($slides) > 0):
?>

<div id="picture_viewer">
<ul>
<?
foreach($slides as $slide){
	?><li><img src="<?php echo_image_url($slide->ID,'slide');?>" /></li><?php
}
?>
</ul>
</div><!--picture_viewer-->
<?php endif;