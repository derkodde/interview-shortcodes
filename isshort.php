<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://derkod.de
 * @since             0.0.0
 * @package           isshort
 *
 * @wordpress-plugin
 * Plugin Name:       Interview Styles shortcode
 * Plugin URI:        http://interviewstyles.derkod.de
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           0.3.0
 * Author:            Sebastian Kotte
 * Author URI:        http://derkod.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       isshort
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
*================================
* Frontend
*================================
*/

function enqueue_isshort_styles() {

	/**
	 * This function is provided for demonstration purposes only.
	 *
	 * An instance of this class should be passed to the run() function
	 * defined in isshort_Loader as all of the hooks are defined
	 * in that particular class.
	 *
	 * The isshort_Loader will then create the relationship
	 * between the defined hooks and the functions defined in this
	 * class.
	 */

	wp_enqueue_style( 'isshort', plugin_dir_url( __FILE__ ) . 'css/isshort-public.css');
	wp_enqueue_style('animate',  plugin_dir_url( __FILE__ ) . 'css/animate.min.css');
	wp_enqueue_style('hover',  plugin_dir_url( __FILE__ ) . 'css/hover.css');


	// scripts
	wp_enqueue_script('wowjs',  plugin_dir_url( __FILE__ ) . 'js/wow.min.js');
	wp_enqueue_script('main',  plugin_dir_url( __FILE__ ) . 'js/isshort.js', array('jquery') );

}
add_action( 'wp_enqueue_scripts', 'enqueue_isshort_styles' );

public $isshort_options;

/*
*
*Add Shortcodes
*
*/
function caption_shortcode($atts, $content=null ) {

	// Attributes
	 $atts = shortcode_atts( array(
		 'color' =>  $isshort_options['cap']['color'],
		 'style' => 'circle',
		 'text-color'=> 'white',
		 'size' => '4em',
		 'font-family' => 'Georgia',
		'hover' => 'hvr-buzz-out',
	 	), $atts );
    return caption_shortcode_html($atts, $content);
}
add_shortcode('cap', 'caption_shortcode');

function caption_shortcode_html($atts, $content){
    	$styles ="background-color:" .  $atts['color'] . ";color:" .  $atts['text-color'] . ";height:".  $atts['size'] .";width:".  $atts['size'];

    ob_start(); ?>
	<!-- <div class="clearfix"></div> -->
    <p><div class="isshort-caption <?php echo $atts['hover']; ?>  <?php echo $atts['style'];?>" style="<?php echo $styles; ?>">
        <div class="isshort-inner" style="font-size:<?php echo ($atts['size']/2.3); ?>em;font-family:<?php echo $atts['font-family'];?>"><?php echo $content; ?></div>
    </div></p>

    <?php return ob_get_clean();
}
function question_shortcode( $atts , $content = null ) {

	// Attributes
	 $atts = shortcode_atts( array(
		 'color' => '#eee',
		 'corner' => 'top-left',
		 'radius' => '5px',
		 'text-color'=> 'grey',
		 'animate' => 'pulse',
	 	), $atts );

return isshort_shortcode_html( $atts, $content) ;
}

add_shortcode( 'question', 'question_shortcode' );

function answer_shortcode( $atts , $content = null ) {

	// Attributes
	 $atts = shortcode_atts( array(
		 'color' => '#DCF8C6',
		 'corner' => 'bottom-right',
		 'radius' => '5px',
		 'text-color'=> 'grey',
		 'hover' => '',
	 	), $atts );

return isshort_shortcode_html( $atts, $content) ;
}
add_shortcode( 'answer', 'answer_shortcode' );

function isshort_shortcode_html( $atts, $content) {
	$color=$atts['color'];
	if ($atts['corner']=='top-left') {
		$tip='transparent '.$color.' transparent transparent;';
	}
	if ($atts['corner']=='bottom-left'){
		$tip='transparent transparent '.$color.' transparent ;';
	}
	if ($atts['corner']=='top-right') {
		$tip= $color.' transparent transparent transparent;';
	}
	if ($atts['corner']=='bottom-right') {
		$tip= 'transparent transparent transparent '.$color;
	}

	ob_start();
	?>
        <div class="isshort-wrapper <?php echo $atts['hover']; ?> " >
            <div class="isshort-side <?php echo $atts['corner']; ?>">
                <div class="isshort-tip tip-<?php echo $atts['corner']; ?>" style="border-color:<?php echo $tip;?>"></div>
            </div>
            <div class="isshort-content content-<?php echo $atts['corner']; ?>" style="border-<?php echo $atts['corner']; ?>-radius:0;color:<?php echo $atts['text-color']; ?>;background-color:<?php echo $atts['color']; ?>;">
                <?php echo $content; ?>
            </div>
        </div>
        <?php
	return ob_get_clean();
}


/*
*================================
* Backend
*================================
*/


// Defaultwerte seite
add_action('admin_menu', 'isshort_defaults');
function isshort_defaults(){
	add_plugins_page( 'interviewstyles', 'Interview Styles', 'manage_options', 'isshort-options', 'isshort_adminpage');
}

// settings form markup
include( plugin_dir_path( __FILE__ ) . '/admin/admin.php');
