<?php
/*
Plugin Name: Blogroll Dropdown
Plugin URI: http://blog.casanova.vn/wordpress-blogroll-select-menu-dropdown-widget/
Description: Display Links as select menu dropdown (jump menu). After active plugin please goto <strong>Appearance</strong> --> <strong>Widgets</strong> and drag drop to your sidebar. Configuration it.
Version: 1.0
Author: Nguyen Duc Manh
Author URI: http://casanova.vn
License: GPLv2 or later
*/
class Blogroll_Dropdown extends WP_Widget {
 
    //Khởi tạo contructor của 1 lớp
    function Blogroll_Dropdown(){
        parent::WP_Widget('Blogroll_Dropdown_Widget',
            'Blogroll Dropdown',
            array('description' => 'Display links as select menu dropdown'));
    }
	
	function widget( $args, $instance ) {
		extract($args);
		$title = $instance['title'];
		echo $before_widget;
		if ( !empty( $title ) ) { 
			echo $before_title . $title . $after_title; } ?>	
			<select class="<?php echo $instance['blogroll_class']; ?>" onchange="<?php if($instance['open_in']==0): ?>if(this.value) window.open(this.value,'weblink','');<?php else: ?>if(this.value) window.location.href = this.value;<?php endif;?>">
            <option value=""><?php echo $instance['group_title']; ?></option>
			<?php 
				$bookmarks	=	get_bookmarks(array('orderby'        => $instance['orderby'], 
													'order'          => $instance['order'],
													'limit'          => $instance['limit'], 
													'category'       => $instance['category'],
													'include'        => $instance['include'],
													'exclude'        => $instance['exclude'],
												)
											);	
				foreach ( $bookmarks as $bm ) { 
					printf( '<option value="%s">%s</option>', $bm->link_url, __($bm->link_name) );
				}
			?>
			</select>		
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
	
	function form( $instance ) {
		$title = strip_tags($instance['title']);
		$limit = intval($instance['limit'])?intval($instance['limit']):10;
?>
	  
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php _e('Widget Title:'); ?> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
				name="<?php echo $this->get_field_name('title'); ?>" type="text" 
				value="<?php echo  esc_attr($title);?>" />
		</p>
        
        <p>
			<label for="<?php echo $this->get_field_id('group_title'); ?>">
				<?php _e('Option title:'); ?> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('group_title'); ?>" 
				name="<?php echo $this->get_field_name('group_title'); ?>" type="text" 
				value="<?php echo  esc_attr($instance['group_title']);?>" />
		</p>
        
         <p><label for="<?php echo $this->get_field_id('limit'); ?>">
				<?php _e('Limit:'); ?> </label>
        	<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" 
				name="<?php echo $this->get_field_name('limit'); ?>" type="text" 
				value="<?php echo  esc_attr($limit);?>" />
        </p>
        
        <p><label for="<?php echo $this->get_field_id('blogroll_class'); ?>">
				<?php _e('Class:'); ?><br /><small>Input your class to css your box</small> </label>
        	<input class="widefat" id="<?php echo $this->get_field_id('blogroll_class'); ?>" 
				name="<?php echo $this->get_field_name('blogroll_class'); ?>" type="text" 
				value="<?php echo  esc_attr($instance['blogroll_class']);?>" />
        </p>
        
        <p><label for="<?php echo $this->get_field_id('orderby'); ?>">
				<?php _e('Orderby:'); ?> </label>
        	<select id="<?php echo $this->get_field_id("orderby"); ?>" name="<?php echo $this->get_field_name("orderby"); ?>">
              <option value="name"<?php selected( $instance["orderby"], "name" ); ?>>name (default)</option>
              <option value="link_id"<?php selected( $instance["orderby"], "link_id" ); ?>>link_id</option>
              <option value="url"<?php selected( $instance["orderby"], "url" ); ?>>url</option>
              <option value="owner"<?php selected( $instance["orderby"], "owner" ); ?>>owner</option>
              <option value="rating"<?php selected( $instance["orderby"], "rating" ); ?>>rating</option>
              <option value="visible"<?php selected( $instance["orderby"], "visible" ); ?>>visible</option>
              <option value="length"<?php selected( $instance["orderby"], "length" ); ?>>length</option>
              <option value="rand"<?php selected( $instance["orderby"], "rand" ); ?>>rand</option>
            </select>
        </p>
        
        <p><label for="<?php echo $this->get_field_id('order'); ?>">
				<?php _e('Order:'); ?> </label>
        	<select id="<?php echo $this->get_field_id("order"); ?>" name="<?php echo $this->get_field_name("order"); ?>">
              <option value="ASC "<?php selected( $instance["order"], "ASC " ); ?>>ASC (default)</option>
              <option value="DESC"<?php selected( $instance["order"], "DESC" ); ?>>DESC</option>            
            </select>
        </p>
        
        <p>
			<label for="<?php echo $this->get_field_id('category'); ?>">
				<?php _e('Category ID:'); ?><br /><small>Comma separated list of bookmark category ID's</small> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('category'); ?>" 
				name="<?php echo $this->get_field_name('category'); ?>" type="text" 
				value="<?php echo  esc_attr($instance['category']);?>" />
		</p>
		
        <p>
			<label for="<?php echo $this->get_field_id('include'); ?>">
				<?php _e('Include:'); ?><br /><small>Comma separated list of numeric bookmark IDs to include in the output. For example, 'include=1,3,6'</small> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('include'); ?>" 
				name="<?php echo $this->get_field_name('include'); ?>" type="text" 
				value="<?php echo  esc_attr($instance['include']);?>" />
		</p>
        
       	<p>
			<label for="<?php echo $this->get_field_id('exclude '); ?>">
				<?php _e('Exclude :'); ?><br /><small>Comma separated list of numeric bookmark IDs to exclude. For example, 'exclude=4,12'</small> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" 
				name="<?php echo $this->get_field_name('exclude'); ?>" type="text" 
				value="<?php echo  esc_attr($instance['exclude']);?>" />
		</p>
         <p><label for="<?php echo $this->get_field_id('open_in'); ?>">
				<?php _e('Open in:'); ?> </label>
        	<select id="<?php echo $this->get_field_id("open_in"); ?>" name="<?php echo $this->get_field_name("open_in"); ?>">
              <option value="0 "<?php selected( $instance["open_in"], "0 " ); ?>>New Window</option>
              <option value="1"<?php selected( $instance["open_in"], "1" ); ?>>Current Window</option>            
            </select>
        </p>

<?php
	}
}//--- End class Blogroll widget	
add_action( 'widgets_init', create_function('', 'return register_widget("Blogroll_Dropdown");') );
?>