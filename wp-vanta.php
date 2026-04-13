<?php
/**
 * Plugin Name: WP Vanta Background
 * Plugin URI: 
 * Description: Adds animated Vanta.js background effects to all WordPress pages. Choose from multiple effects (Birds, Clouds, Fog, Waves, etc.) with full customization via admin settings.
 * Version: 1.0.0
 * Author: 
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-vanta
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Activation hook
register_activation_hook( __FILE__, 'wp_vanta_activate' );
function wp_vanta_activate() {
    // Set default options
    $defaults = array(
        'effect' => 'clouds',
        'mouseControls' => true,
        'touchControls' => true,
        'gyroControls' => false,
        'minHeight' => 200.00,
        'minWidth' => 200.00,
        'skyColor' => '0x1ea6e6',
        'cloudColor' => '0xa525eb',
        'sunColor' => '0xff0000',
        'sunGlareColor' => '0x4830ff',
        'sunlightColor' => '0x25cce3',
        'speed' => 0.60
    );
    add_option( 'wp_vanta_options', $defaults );
}

// Deactivation hook
register_deactivation_hook( __FILE__, 'wp_vanta_deactivate' );
function wp_vanta_deactivate() {
    // Optionally remove options on deactivate
    // delete_option( 'wp_vanta_options' );
}

// Add Vanta canvas div to the top of the DOM
add_action( 'wp_body_open', 'wp_vanta_add_canvas_div' );
function wp_vanta_add_canvas_div() {
    echo '<div class="vanta-canvas"></div>';
}

// Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'wp_vanta_enqueue_scripts' );
function wp_vanta_enqueue_scripts() {
    // Enqueue CSS
    wp_enqueue_style( 'wp-vanta-style', plugin_dir_url( __FILE__ ) . 'assets/css/vanta-style.css', array(), '1.0.0' );
    
    // Get selected effect
    $options = get_option( 'wp_vanta_options', array() );
    $effect = isset( $options['effect'] ) ? strtolower( $options['effect'] ) : 'clouds';
    
    // Enqueue Three.js or p5.js depending on effect
    $p5_effects = array( 'trunk' );
    if ( in_array( $effect, $p5_effects ) ) {
        wp_enqueue_script( 'p5-js', 'https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.4.0/p5.min.js', array(), '1.4.0', true );
        $vanta_deps = array( 'p5-js' );
    } else {
        wp_enqueue_script( 'three-js', 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js', array(), 'r134', true );
        $vanta_deps = array( 'three-js' );
    }
    
    // Enqueue the selected Vanta effect
    $vanta_url = 'https://cdn.jsdelivr.net/npm/vanta/dist/vanta.' . $effect . '.min.js';
    wp_enqueue_script( 'vanta-effect', $vanta_url, $vanta_deps, null, true );
    
    // Enqueue custom init script
    wp_enqueue_script( 'wp-vanta-init', plugin_dir_url( __FILE__ ) . 'assets/js/vanta-init.js', array( 'vanta-effect' ), '1.0.0', true );
    
    // Localize options
    wp_localize_script( 'wp-vanta-init', 'wpVantaOptions', $options );
    wp_localize_script( 'wp-vanta-init', 'wpVantaEffect', $effect );
}

// Admin menu
add_action( 'admin_menu', 'wp_vanta_add_admin_menu' );
function wp_vanta_add_admin_menu() {
    add_options_page( 'WP Vanta Settings', 'WP Vanta', 'manage_options', 'wp-vanta', 'wp_vanta_options_page' );
}

// Settings page
function wp_vanta_options_page() {
    ?>
    <div class="wrap">
        <h1><?php _e( 'WP Vanta Settings', 'wp-vanta' ); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'wp_vanta_options_group' );
            do_settings_sections( 'wp-vanta' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Settings init
add_action( 'admin_init', 'wp_vanta_settings_init' );
function wp_vanta_settings_init() {
    register_setting( 'wp_vanta_options_group', 'wp_vanta_options' );
    
    add_settings_section( 'wp_vanta_section', __( 'Vanta Settings', 'wp-vanta' ), null, 'wp-vanta' );
    
    // Effect selection
    add_settings_field( 'effect', __( 'Effect', 'wp-vanta' ), 'wp_vanta_effect_render', 'wp-vanta', 'wp_vanta_section' );
    
    // Controls
    add_settings_field( 'mouseControls', __( 'Mouse Controls', 'wp-vanta' ), 'wp_vanta_mouse_controls_render', 'wp-vanta', 'wp_vanta_section' );
    add_settings_field( 'touchControls', __( 'Touch Controls', 'wp-vanta' ), 'wp_vanta_touch_controls_render', 'wp-vanta', 'wp-vanta_section' );
    add_settings_field( 'gyroControls', __( 'Gyro Controls', 'wp-vanta' ), 'wp_vanta_gyro_controls_render', 'wp-vanta', 'wp-vanta_section' );
    
    // Dimensions
    add_settings_field( 'minHeight', __( 'Min Height', 'wp-vanta' ), 'wp_vanta_min_height_render', 'wp-vanta', 'wp-vanta_section' );
    add_settings_field( 'minWidth', __( 'Min Width', 'wp-vanta' ), 'wp_vanta_min_width_render', 'wp-vanta', 'wp-vanta_section' );
    
    // Colors
    add_settings_field( 'skyColor', __( 'Sky Color (hex, e.g. 0x1ea6e6)', 'wp-vanta' ), 'wp_vanta_sky_color_render', 'wp-vanta', 'wp-vanta_section' );
    add_settings_field( 'cloudColor', __( 'Cloud Color (hex)', 'wp-vanta' ), 'wp_vanta_cloud_color_render', 'wp-vanta', 'wp-vanta_section' );
    add_settings_field( 'sunColor', __( 'Sun Color (hex)', 'wp-vanta' ), 'wp_vanta_sun_color_render', 'wp-vanta', 'wp-vanta_section' );
    add_settings_field( 'sunGlareColor', __( 'Sun Glare Color (hex)', 'wp-vanta' ), 'wp_vanta_sun_glare_color_render', 'wp-vanta', 'wp-vanta_section' );
    add_settings_field( 'sunlightColor', __( 'Sunlight Color (hex)', 'wp-vanta' ), 'wp_vanta_sunlight_color_render', 'wp-vanta', 'wp-vanta_section' );
    
    // Speed
    add_settings_field( 'speed', __( 'Speed', 'wp-vanta' ), 'wp_vanta_speed_render', 'wp-vanta', 'wp-vanta_section' );
}

// Render functions
function wp_vanta_effect_render() {
    $options = get_option( 'wp_vanta_options' );
    $selected = isset( $options['effect'] ) ? $options['effect'] : 'clouds';
    
    $effects = array(
        'birds' => 'Birds',
        'cells' => 'Cells',
        'clouds' => 'Clouds',
        'clouds2' => 'Clouds 2',
        'dots' => 'Dots',
        'fog' => 'Fog',
        'globe' => 'Globe',
        'halo' => 'Halo',
        'net' => 'Net',
        'rings' => 'Rings',
        'topology' => 'Topology',
        'trunk' => 'Trunk',
        'waves' => 'Waves'
    );
    
    echo '<select name="wp_vanta_options[effect]">';
    foreach ( $effects as $key => $label ) {
        $is_selected = $selected === $key ? 'selected' : '';
        echo '<option value="' . esc_attr( $key ) . '" ' . $is_selected . '>' . esc_html( $label ) . '</option>';
    }
    echo '</select>';
    echo '<p class="description">Select which Vanta.js effect to display as the background.</p>';
}

function wp_vanta_mouse_controls_render() {
    $options = get_option( 'wp_vanta_options' );
    $checked = isset( $options['mouseControls'] ) && $options['mouseControls'] ? 'checked' : '';
    echo '<input type="checkbox" name="wp_vanta_options[mouseControls]" value="1" ' . $checked . ' />';
}

function wp_vanta_touch_controls_render() {
    $options = get_option( 'wp_vanta_options' );
    $checked = isset( $options['touchControls'] ) && $options['touchControls'] ? 'checked' : '';
    echo '<input type="checkbox" name="wp_vanta_options[touchControls]" value="1" ' . $checked . ' />';
}

function wp_vanta_gyro_controls_render() {
    $options = get_option( 'wp_vanta_options' );
    $checked = isset( $options['gyroControls'] ) && $options['gyroControls'] ? 'checked' : '';
    echo '<input type="checkbox" name="wp_vanta_options[gyroControls]" value="1" ' . $checked . ' />';
}

function wp_vanta_min_height_render() {
    $options = get_option( 'wp_vanta_options' );
    echo '<input type="number" step="0.01" name="wp_vanta_options[minHeight]" value="' . esc_attr( $options['minHeight'] ?? 200 ) . '" />';
}

function wp_vanta_min_width_render() {
    $options = get_option( 'wp_vanta_options' );
    echo '<input type="number" step="0.01" name="wp_vanta_options[minWidth]" value="' . esc_attr( $options['minWidth'] ?? 200 ) . '" />';
}

function wp_vanta_sky_color_render() {
    $options = get_option( 'wp_vanta_options' );
    echo '<input type="text" name="wp_vanta_options[skyColor]" value="' . esc_attr( $options['skyColor'] ?? '0x1ea6e6' ) . '" />';
}

function wp_vanta_cloud_color_render() {
    $options = get_option( 'wp_vanta_options' );
    echo '<input type="text" name="wp_vanta_options[cloudColor]" value="' . esc_attr( $options['cloudColor'] ?? '0xa525eb' ) . '" />';
}

function wp_vanta_sun_color_render() {
    $options = get_option( 'wp_vanta_options' );
    echo '<input type="text" name="wp_vanta_options[sunColor]" value="' . esc_attr( $options['sunColor'] ?? '0xff0000' ) . '" />';
}

function wp_vanta_sun_glare_color_render() {
    $options = get_option( 'wp_vanta_options' );
    echo '<input type="text" name="wp_vanta_options[sunGlareColor]" value="' . esc_attr( $options['sunGlareColor'] ?? '0x4830ff' ) . '" />';
}

function wp_vanta_sunlight_color_render() {
    $options = get_option( 'wp_vanta_options' );
    echo '<input type="text" name="wp_vanta_options[sunlightColor]" value="' . esc_attr( $options['sunlightColor'] ?? '0x25cce3' ) . '" />';
}

function wp_vanta_speed_render() {
    $options = get_option( 'wp_vanta_options' );
    echo '<input type="number" step="0.01" name="wp_vanta_options[speed]" value="' . esc_attr( $options['speed'] ?? 0.60 ) . '" />';
}