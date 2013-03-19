<?php 
if(class_exists('HCProductionDates')):
$upcoming_current = HCProductionDates::setup_upcoming_current();
if ($upcoming_current !== false):

foreach($upcoming_current as $group_name => $group){
	if(count($group) > 0){
		echo '<div class="'.$group_name.'">';
		
		foreach($group as $show){
?>

<article class="production">
	<header>
		<?php echo $show->post_title;?>
	</header>
	<div class="clearfix">
	<div class="show_info">
		<img src="<?php echo_image_url($show->ID) ?>" />
		<div class="show_info_text">
		<?php 
			//Location
			$theatre_name = get_post_meta($show->ID, 'hc_theatre_name', true);
			$theatre_url = get_post_meta($show->ID, 'hc_theatre_url', true);
			if($theatre_name != null && $theatre_url != null){
				echo '<div class="theatre_info"><a href="' . $theatre_url . '">' . $theatre_name . '</a></div>';
			}elseif ($theatre_name){
				echo '<div class="theatre_info">' . $theatre_name . '</div>';
			}
			if ($theatre_location = get_post_meta($show->ID, 'hc_theatre_location', true)){
				echo '<div class="location">' . $theatre_location . '</div>';
			}
			
			//Dates
			if (isset($show->preview_date)){
				echo '<div class="dates">Previews begin ' . date('F d, Y', $show->preview_date) . '</div>';
			}
			if (isset($show->opening_date) && isset($show->closing_date)){
				echo '<div class="dates">' . date('F d, Y', $show->opening_date) . ' &mdash; '  . date('F d, Y', $show->closing_date) . '</div>';
			}elseif(isset($show->opening_date)){
				echo '<div class="dates">Opening ' . date('F d, Y', $show->opening_date) . '</div>';
			}
			
			//Tickets
			$tickets_url = get_post_meta($show->ID, 'hc_tickets_url', true);
			if($tickets_url != null){
				echo '<div class="tickets"><a href="' . $tickets_url . '">Buy Tickets</a></div>';
			}
			
			
		?>
		</div>
	</div>
	<div class="show_content">
		<?php 
		$show->post_content = apply_filters('the_content', $show->post_content);
		$show->post_content = str_replace(']]>', ']]&gt;', $show->post_content);
		echo $show->post_content;
		?>
	</div>
	</div>
</article>

<?php
		}
		
		echo '</div><!--'.$group_name.'-->';
	}
}

endif;endif;