<?php
namespace Yardline;

class Dashboard_Widget {

    const WIDGET_ID = 'yardline';
    const WIDGET_NAME = 'Yardline';
    const WIDGET_CSS = 'yardline-widget-css';
    public function __construct() {
        add_action( 'wp_dashboard_setup', [ $this, 'register' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
    }

    public function register() {
        wp_add_dashboard_widget( 
            self::WIDGET_ID, 
            self::WIDGET_NAME,  
            [ $this, 'widget' ] 
        );
    }

    public function admin_enqueue_scripts() {
        wp_enqueue_style( self::WIDGET_CSS, YARDLINE_ASSETS_URL . 'admin/css/dashboard-widget.css', array(), YARDLINE_VERSION );
    }

    public function widget() {
        //to do
    }
}