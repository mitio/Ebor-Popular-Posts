<?php

/*
Plugin Name: Ebor Popular Posts Widget
Plugin URI: http://www.madeinebor.com
Description: Adds a popular posts widget which displays a specified amount of posts by comment count.
Version: 1.1
Author: TommusRhodus
Author URI: http://www.madeinebor.com
*/	


/*-----------------------------------------------------------------------------------*/
/*	POPULAR POSTS WIDGET
/*-----------------------------------------------------------------------------------*/
add_action('widgets_init', 'ebor_popular_load_widgets');
function ebor_popular_load_widgets()
{
	register_widget('ebor_popular_Widget');
}

class ebor_popular_Widget extends WP_Widget {
	
	function ebor_popular_Widget()
	{
		$widget_ops = array('classname' => 'ebor_popular', 'description' => '');

		$control_ops = array('id_base' => 'ebor_popular-widget');

		$this->WP_Widget('ebor_popular-widget', 'Ebor: Popular Posts', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;

		if($title) {
			echo  $before_title.$title.$after_title;
		} ?>
		
			<?php if( wp_get_theme() == 'Seabird' ) : ?>
				<ul class="post-list">
				
				<?php query_posts('post_type=post&posts_per_page=' . $instance['amount'] . '&orderby=comment_count&order=DESC'); while ( have_posts() ): the_post(); ?>
				
				  <li>
				    <figure class="overlay">
				    	<a href="<?php the_permalink(); ?>">
				    		<?php the_post_thumbnail('thumbnail'); ?>
				     		<div></div>
				        </a>
				    </figure>
				    <div class="meta">
				      <h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
				      <em><?php the_time(get_option('date_format')); ?></em>
				    </div>
				  </li>
				  
				  <?php endwhile; wp_reset_query(); ?> 
				</ul>
			<?php else : ?>
				<dl>
				  <?php $popular = new WP_Query('post_type=post&posts_per_page=' . $instance['amount'] . '&orderby=comment_count&order=DESC'); if( $popular->have_posts() ) : while ( $popular->have_posts() ): $popular->the_post(); ?>
				
				  <dt><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></dt>
				  <dd><?php the_time(get_option('date_format')); ?></dd>
				  
				  <?php endwhile; endif; wp_reset_query(); ?> 
				</dl>
			<?php endif; ?>
		
		
		<?php echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		if( is_numeric($new_instance['amount']) ){
			$instance['amount'] = $new_instance['amount'];
		} else {
			$new_instance['amount'] = '3';
		}

		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Popular Posts', 'amount' => '3');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('amount'); ?>">Amount of Posts:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('amount'); ?>" name="<?php echo $this->get_field_name('amount'); ?>" value="<?php echo $instance['amount']; ?>" />
		</p>
	<?php
	}
}
