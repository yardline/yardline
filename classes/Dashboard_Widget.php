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
        ?>
        <div class="yardline-dashboard-widget">
        <h3>Your Simple Stats as of <?php echo current_time( 'Y-m-d' ); ?></h3>
            <div class="yardline-stat-wrap">
                <div class="yardline-stat">
                    <h3>Visitors</h3>
                    <span class="stat">45</span>
                    <span>Last 7 days <span class="stat-up dashicons dashicons-arrow-up-alt"></span>2%</span>
                </div>
                <div class="yardline-stat">
                    <h3>Form Submissions</h3>
                    <span class="stat">21</span>
                    <span>Last 7 days <span class="stat-down dashicons dashicons-arrow-down-alt"></span>5%</span>
                </div>
                <div class="yardline-stat">
                    <h3>Total Sales</h3>
                    <span class="stat">$21,023</span>
                    <span>Last 7 days <span class="stat-up dashicons dashicons-arrow-up-alt"></span>10%</span>
                </div>
                <div class="yardline-stat">
                    <h3>Users</h3>
                    <span class="stat">312</span>
                    <span>Total <span class="stat-up dashicons dashicons-arrow-up-alt"></span>10%</span>
                </div>
            </div>
        </div>
        <?php
    }
}