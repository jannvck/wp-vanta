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
    // Set default options with effect-specific settings
    $defaults = array(
        'effect' => 'clouds',
        'mouseControls' => true,
        'touchControls' => true,
        'gyroControls' => false,
        'minHeight' => 200.00,
        'minWidth' => 200.00,
        // Clouds defaults
        'skyColor' => '0x1ea6e6',
        'cloudColor' => '0xa525eb',
        'sunColor' => '0xff0000',
        'sunGlareColor' => '0x4830ff',
        'sunlightColor' => '0x25cce3',
        'speed' => 0.60,
        // Clouds2 defaults
        'lightColor' => '0xffffff',
        'texturePath' => '',
        // Waves defaults
        'color' => '0x6588',
        'shininess' => 30,
        'waveHeight' => 15,
        'waveSpeed' => 1,
        'zoom' => 1,
        // Fog defaults
        'highlightColor' => '0xffe300',
        'midtoneColor' => '0xff1f00',
        'lowlightColor' => '0x2d10ff',
        'baseColor' => '0xffebeb',
        'blurFactor' => 0.6,
        // Topology defaults
        'scale' => 1.00,
        'scaleMobile' => 1.00,
        // Birds defaults
        'backgroundColor' => '0x0',
        'backgroundAlpha' => 1,
        'color1' => '0xff0000',
        'color2' => '0xffff',
        'colorMode' => 'varianceGradient',
        'quantity' => 5,
        'birdSize' => 1,
        'wingSpan' => 30,
        'speedLimit' => 5,
        'separation' => 20,
        'alignment' => 20,
        'cohesion' => 20,
        // Globe defaults
        'size' => 1,
        // Net defaults
        'points' => 10,
        'maxDistance' => 20,
        'spacing' => 15,
        'showDots' => true,
        // Dots defaults
        'size' => 3,
        'spacing' => 35,
        'showLines' => true,
        // Rings defaults
        // (uses color, backgroundColor, backgroundAlpha)
        // Cells defaults
        'color1' => '0x8c5c',
        'color2' => '0xa6f738',
        // Trunk defaults
        'spacing' => 35,
        'chaos' => 0,
        // Halo defaults
        'amplitudeFactor' => 1,
        'xOffset' => 0,
        'yOffset' => 0
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
    
    // General Controls
    add_settings_field( 'mouseControls', __( 'Mouse Controls', 'wp-vanta' ), 'wp_vanta_mouse_controls_render', 'wp-vanta', 'wp_vanta_section' );
    add_settings_field( 'touchControls', __( 'Touch Controls', 'wp-vanta' ), 'wp_vanta_touch_controls_render', 'wp-vanta', 'wp-vanta_section' );
    add_settings_field( 'gyroControls', __( 'Gyro Controls', 'wp-vanta' ), 'wp_vanta_gyro_controls_render', 'wp-vanta', 'wp-vanta_section' );
    
    // Dimensions
    add_settings_field( 'minHeight', __( 'Min Height', 'wp-vanta' ), 'wp_vanta_min_height_render', 'wp-vanta', 'wp_vanta_section' );
    add_settings_field( 'minWidth', __( 'Min Width', 'wp-vanta' ), 'wp_vanta_min_width_render', 'wp-vanta', 'wp-vanta_section' );
    
    // Shared effect colors/params
    add_settings_field( 'skyColor', __( 'Sky Color', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'skyColor', 'label' => 'Sky Color' ) );
    add_settings_field( 'cloudColor', __( 'Cloud Color', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'cloudColor', 'label' => 'Cloud Color' ) );
    add_settings_field( 'sunColor', __( 'Sun Color', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'sunColor', 'label' => 'Sun Color' ) );
    add_settings_field( 'sunGlareColor', __( 'Sun Glare Color', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'sunGlareColor', 'label' => 'Sun Glare Color' ) );
    add_settings_field( 'sunlightColor', __( 'Sunlight Color', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'sunlightColor', 'label' => 'Sunlight Color' ) );
    add_settings_field( 'lightColor', __( 'Light Color', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'lightColor', 'label' => 'Light Color' ) );
    add_settings_field( 'color', __( 'Color', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'color', 'label' => 'Color' ) );
    add_settings_field( 'color1', __( 'Color 1', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'color1', 'label' => 'Color 1' ) );
    add_settings_field( 'color2', __( 'Color 2', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'color2', 'label' => 'Color 2' ) );
    add_settings_field( 'backgroundColor', __( 'Background Color', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'backgroundColor', 'label' => 'Background Color' ) );
    add_settings_field( 'baseColor', __( 'Base Color', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'baseColor', 'label' => 'Base Color' ) );
    add_settings_field( 'highlightColor', __( 'Highlight Color', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'highlightColor', 'label' => 'Highlight Color' ) );
    add_settings_field( 'midtoneColor', __( 'Midtone Color', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'midtoneColor', 'label' => 'Midtone Color' ) );
    add_settings_field( 'lowlightColor', __( 'Lowlight Color', 'wp-vanta' ), 'wp_vanta_color_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'lowlightColor', 'label' => 'Lowlight Color' ) );
    
    // Numeric params
    add_settings_field( 'speed', __( 'Speed', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'speed', 'min' => 0, 'step' => 0.01 ) );
    add_settings_field( 'scale', __( 'Scale', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'scale', 'min' => 0, 'step' => 0.01 ) );
    add_settings_field( 'scaleMobile', __( 'Scale Mobile', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'scaleMobile', 'min' => 0, 'step' => 0.01 ) );
    add_settings_field( 'zoom', __( 'Zoom', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'zoom', 'min' => 0, 'step' => 0.1 ) );
    add_settings_field( 'shininess', __( 'Shininess', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'shininess', 'min' => 0, 'step' => 1 ) );
    add_settings_field( 'waveHeight', __( 'Wave Height', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'waveHeight', 'min' => 0, 'step' => 0.1 ) );
    add_settings_field( 'waveSpeed', __( 'Wave Speed', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'waveSpeed', 'min' => 0, 'step' => 0.1 ) );
    add_settings_field( 'blurFactor', __( 'Blur Factor', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'blurFactor', 'min' => 0, 'step' => 0.01 ) );
    add_settings_field( 'size', __( 'Size', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'size', 'min' => 0, 'step' => 0.1 ) );
    add_settings_field( 'backgroundAlpha', __( 'Background Alpha', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'backgroundAlpha', 'min' => 0, 'max' => 1, 'step' => 0.1 ) );
    add_settings_field( 'points', __( 'Points', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'points', 'min' => 1, 'step' => 1 ) );
    add_settings_field( 'maxDistance', __( 'Max Distance', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'maxDistance', 'min' => 0, 'step' => 1 ) );
    add_settings_field( 'spacing', __( 'Spacing', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'spacing', 'min' => 0, 'step' => 1 ) );
    add_settings_field( 'quantity', __( 'Quantity', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'quantity', 'min' => 1, 'step' => 1 ) );
    add_settings_field( 'birdSize', __( 'Bird Size', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'birdSize', 'min' => 0.1, 'step' => 0.1 ) );
    add_settings_field( 'wingSpan', __( 'Wing Span', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'wingSpan', 'min' => 0, 'step' => 1 ) );
    add_settings_field( 'speedLimit', __( 'Speed Limit', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'speedLimit', 'min' => 0, 'step' => 0.1 ) );
    add_settings_field( 'separation', __( 'Separation', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'separation', 'min' => 0, 'step' => 1 ) );
    add_settings_field( 'alignment', __( 'Alignment', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'alignment', 'min' => 0, 'step' => 1 ) );
    add_settings_field( 'cohesion', __( 'Cohesion', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'cohesion', 'min' => 0, 'step' => 1 ) );
    add_settings_field( 'amplitudeFactor', __( 'Amplitude Factor', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'amplitudeFactor', 'min' => 0, 'step' => 0.1 ) );
    add_settings_field( 'xOffset', __( 'X Offset', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'xOffset', 'step' => 0.1 ) );
    add_settings_field( 'yOffset', __( 'Y Offset', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'yOffset', 'step' => 0.1 ) );
    add_settings_field( 'chaos', __( 'Chaos', 'wp-vanta' ), 'wp_vanta_number_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'chaos', 'min' => 0, 'step' => 0.1 ) );
    
    // Boolean options
    add_settings_field( 'showDots', __( 'Show Dots', 'wp-vanta' ), 'wp_vanta_checkbox_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'showDots' ) );
    add_settings_field( 'showLines', __( 'Show Lines', 'wp-vanta' ), 'wp_vanta_checkbox_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'showLines' ) );
    add_settings_field( 'colorMode', __( 'Color Mode', 'wp-vanta' ), 'wp_vanta_text_input_render', 'wp-vanta', 'wp_vanta_section', array( 'key' => 'colorMode' ) );
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

// Generic color input render
function wp_vanta_color_input_render( $args = array() ) {
    $key = isset( $args['key'] ) ? $args['key'] : '';
    if ( empty( $key ) ) return;
    
    $defaults = array(
        'skyColor' => '0x1ea6e6',
        'cloudColor' => '0xa525eb',
        'sunColor' => '0xff0000',
        'sunGlareColor' => '0x4830ff',
        'sunlightColor' => '0x25cce3',
        'lightColor' => '0xffffff',
        'color' => '0x6588',
        'color1' => '0xff0000',
        'color2' => '0xffff',
        'backgroundColor' => '0x0',
        'baseColor' => '0xffebeb',
        'highlightColor' => '0xffe300',
        'midtoneColor' => '0xff1f00',
        'lowlightColor' => '0x2d10ff'
    );
    
    $options = get_option( 'wp_vanta_options' );
    $value = isset( $options[ $key ] ) ? $options[ $key ] : ( isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
    
    echo '<input type="text" name="wp_vanta_options[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '" placeholder="0x000000" style="width: 150px;" />';
    echo '<p class="description">Hex color format (e.g. 0x1ea6e6)</p>';
}

// Generic number input render
function wp_vanta_number_input_render( $args = array() ) {
    $key = isset( $args['key'] ) ? $args['key'] : '';
    if ( empty( $key ) ) return;
    
    $options = get_option( 'wp_vanta_options' );
    $value = isset( $options[ $key ] ) ? $options[ $key ] : '';
    
    $min = isset( $args['min'] ) ? $args['min'] : '';
    $max = isset( $args['max'] ) ? $args['max'] : '';
    $step = isset( $args['step'] ) ? $args['step'] : '0.01';
    
    echo '<input type="number" name="wp_vanta_options[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '" ';
    if ( ! empty( $min ) ) echo 'min="' . esc_attr( $min ) . '" ';
    if ( ! empty( $max ) ) echo 'max="' . esc_attr( $max ) . '" ';
    echo 'step="' . esc_attr( $step ) . '" style="width: 150px;" />';
}

// Generic checkbox input render
function wp_vanta_checkbox_input_render( $args = array() ) {
    $key = isset( $args['key'] ) ? $args['key'] : '';
    if ( empty( $key ) ) return;
    
    $options = get_option( 'wp_vanta_options' );
    $checked = isset( $options[ $key ] ) && $options[ $key ] ? 'checked' : '';
    
    echo '<input type="checkbox" name="wp_vanta_options[' . esc_attr( $key ) . ']" value="1" ' . $checked . ' />';
}

// Generic text input render
function wp_vanta_text_input_render( $args = array() ) {
    $key = isset( $args['key'] ) ? $args['key'] : '';
    if ( empty( $key ) ) return;
    
    $options = get_option( 'wp_vanta_options' );
    $value = isset( $options[ $key ] ) ? $options[ $key ] : '';
    
    echo '<input type="text" name="wp_vanta_options[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '" style="width: 200px;" />';
}