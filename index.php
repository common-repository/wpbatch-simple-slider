<?php 
/*
Plugin Name: WPBatch simple Slider
Plugin URI: http://dreamwebit.com/simple-slider/
Description: This plugin will give you a shortcode to show simple carousel Slider
Author: MTM Sujan
Version: 1.0
Author URI: http://dreamwebit.com
*/



function wpbatch_simple_jquery_register() {
	wp_enqueue_script('jquery');
}
add_action('init', 'wpbatch_simple_jquery_register');
add_filter('widget_text', 'do_shortcode');
function wpbatch_simple_external_files() {
    wp_enqueue_script( 'wpbatch-simple-slider', plugins_url( '/js/jquery.divas-1.1.min.js', __FILE__ ), array('jquery'), 1.0, false);
	
	
    wp_enqueue_style( 'wpbatch-simple-CSSreset', plugins_url( '/css/CSSreset.min.css', __FILE__ ));
    wp_enqueue_style( 'wpbatch-simple-font-awesome', plugins_url( '/css/font-awesome.min.css', __FILE__ ));
    wp_enqueue_style( 'simple-free-skin', plugins_url( '/css/divas_free_skin.css', __FILE__ ));
}
add_action('wp_enqueue_scripts','wpbatch_simple_external_files');
function neccessary_codes_for_wpbatch_slider(){
?>
<script type="text/javascript">
	jQuery(document).ready(function()
	{
		jQuery("#slider").divas({
			slideTransitionClass: "divas-slide-transition-left",
			titleTransitionClass: "divas-title-transition-left",
			titleTransitionParameter: "left",
			titleTransitionStartValue: "-999px",
			titleTransitionStopValue: "0px",
			wingsOverlayColor: "rgba(0,0,0,0.6)",
			start: "<?php global $slideroption; echo $slideroption['play']; ?>",
			mainImageWidth: "<?php if($slideroption['sliderwidth']) : ?><?php global $slideroption; echo $slideroption['sliderwidth']; ?><?php else: ?>60<?php endif; ?>%",
			slideInterval: <?php if($slideroption['slidercount']) : ?><?php global $slideroption; echo $slideroption['slidercount']; ?>000<?php else: ?>5000<?php endif; ?>,
			titleTransitionDuration: 4000,
			titleTransitionEasing: "linear",
		});
	});
</script>
<?php 
}
add_action('wp_head', 'neccessary_codes_for_wpbatch_slider');
function custom_wpbatch_simple_slider() {
  $labels = array(
    'name'               => _x( 'Simple Slider', 'wpbatchgallery' ),
    'singular_name'      => _x( 'Slider', 'wpbatchgallery' ),
    'add_new'            => _x( 'Add New', 'Slider' ),
    'add_new_item'       => __( 'Add New Slider' ),
    'edit_item'          => __( 'Edit Slider' ),
    'new_item'           => __( 'New Slider' ),
    'all_items'          => __( 'All Sliders' ),
    'view_item'          => __( 'View Sliders' ),
    'search_items'       => __( 'Search Sliders' ),
    'not_found'          => __( 'No Slider found' ),
    'not_found_in_trash' => __( 'No Slider Images found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'Simple Slider'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our Simple Sliders',
    'public'        => true,
    'menu_position' => 20,
    'menu_icon'     => plugins_url( '/images/icon.jpg', __FILE__ ),
    'supports'      => array( 'title', 'editor', 'thumbnail' ),
    'has_archive'   => true,
  );
  register_post_type( 'simple-slider', $args ); 
}
add_action( 'init', 'custom_wpbatch_simple_slider' );
function wpbatch_simple_slider_shortcode($atts, $content=null){
	$query = new WP_Query( array(
        'post_type' => 'simple-slider',
        'posts_per_page' => -1
    ) );
    if ( $query->have_posts() ) { ?>
		<div id="slider" class="divas-slider">
		<ul class="divas-slide-container">
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<li class="divas-slide"><img src="<?php plugins_url(); ?>/wpbatch-simple-slider/images/placeholder.gif" alt="" data-src="<?php
$imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , 'full');
echo $imgsrc[0];
?>" data-title="<h1><?php the_title(); ?></h1><p><?php the_content(); ?></p>" /></li>
		<?php endwhile;
		wp_reset_postdata(); ?>
		</ul>
		<div class="divas-navigation">
			<span class="divas-prev">&nbsp;</span>
			<span class="divas-next">&nbsp;</span>
		</div>
		<div class="divas-controls">
			<span class="divas-start"><i class="fa fa-play"></i></span>
			<span class="divas-stop"><i class="fa fa-pause"></i></span>
		</div>
		</div>
    <?php return $myvariable;
    }
}
add_shortcode('simple_slider', 'wpbatch_simple_slider_shortcode');


add_action('admin_menu', 'simple_slider_options_page');

function simple_slider_options_page() {
	add_submenu_page('edit.php?post_type=simple-slider', 'Simple Slider Options', 'Slider Options', 'administrator', basename(__FILE__), 'simple_slider_options_display');
}


$slideroption = get_option('slideroption');
function simple_slider_options_display(){
	global $slideroption;
	ob_start();
?>
<form action="options.php" method="POST">
<?php settings_fields('slidergroup'); ?>
<h1>Slider Options<h1>
<?php settings_errors(); ?>
<hr />
<table class="form-table">
<tbody>
<tr>
<th scope="row"><label for="count">Slide Delay</label></th>
<td><input name="slideroption[slidercount]" id="count" value="<?php echo $slideroption['slidercount']; ?>" class="regular-text" type="number"> sec</td>
</tr>
<tr>
<th scope="row"><label for="play">Play Mode</label></th>
<td><input name="slideroption[play]" id="play" value="<?php echo $slideroption['play']; ?>" class="regular-text" type="text"> (auto/manual)</td> 
</tr>
<tr>
<th scope="row"><label for="width">Main Slider Width</label></th>
<td><input name="slideroption[sliderwidth]" id="width" value="<?php echo $slideroption['sliderwidth']; ?>" class="regular-text" type="text"> %</td> 
</tr>
<tr>
<td><input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit"></td>
</tr>
</tbody></table>
</form>
<?php
echo ob_get_clean();
}
function third_step(){
	register_setting('slidergroup', 'slideroption');
}
add_action('admin_init', 'third_step');
?>