<?php
/**
 * Activation handler
 *
 * @package     Cooked\ActivationHandler
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
 * Cooked Extension Activation Handler Class
 *
 * @since       1.0.0
 */
class Cooked_Addon_Activation {

    public $plugin_name, $plugin_path, $plugin_file, $has_cooked, $cooked_base;

	/**
	 * Setup the activation class
	 *
	 * @access      public
	 * @since       1.0.0
	 *
	 * @param $plugin_path
	 * @param $plugin_file
	 */
    public function __construct( $plugin_path, $plugin_file ) {
        // We need plugin.php!
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        $plugins = get_plugins();

        // Set plugin directory
        $plugin_path = array_filter( explode( '/', $plugin_path ) );
        $this->plugin_path = end( $plugin_path );

        // Set plugin file
        $this->plugin_file = $plugin_file;

        // Set plugin name
        if( isset( $plugins[$this->plugin_path . '/' . $this->plugin_file]['Name'] ) ) {
            $this->plugin_name = str_replace( 'Cooked - ', '', $plugins[$this->plugin_path . '/' . $this->plugin_file]['Name'] );
        } else {
            $this->plugin_name = __( 'This plugin', 'cooked' );
        }

        // Is Cooked installed?
        foreach( $plugins as $plugin_path => $plugin ) {
            if( $plugin['Name'] == 'Cooked' ) {
                $this->has_cooked = true;
                $this->cooked_base = $plugin_path;
                break;
            }
        }
    }


    /**
     * Process plugin deactivation
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function run() {
        // Display notice
        add_action( 'admin_notices', array( $this, 'missing_cooked_notice' ) );
    }


    /**
     * Display notice if Cooked isn't installed
     *
     * @access      public
     * @since       1.0.0
     * @return      string The notice to display
     */
    public function missing_cooked_notice() {
        if( $this->has_cooked ) {
            $url  = esc_url( wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . $this->cooked_base ), 'activate-plugin_' . $this->cooked_base ) );
            $link = '<a href="' . $url . '">' . __( 'activate it', 'cooked-extension-activation' ) . '</a>';
        } else {
            $url  = esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=cooked' ), 'install-plugin_cooked' ) );
            $link = '<a href="' . $url . '">' . __( 'install it', 'cooked-extension-activation' ) . '</a>';
        }
        
        echo '<div class="error"><p>' . $this->plugin_name . sprintf( __( ' requires Cooked! Please %s to continue!', 'cooked-extension-activation' ), $link ) . '</p></div>';
    }
}
