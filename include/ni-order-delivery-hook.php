<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_Order_Delivery_Hook' ) ) {
include_once('ni-order-delivery-function.php'); 
	class Ni_Order_Delivery_Hook extends Ni_Order_Delivery_Function{
		public function __construct(){
			add_action( 'woocommerce_after_order_notes', array(&$this,'add_order_date_after_order_notes') );
			add_action('woocommerce_checkout_process',  array(&$this,'ni_woocommerce_checkout_process'));
			add_action( 'woocommerce_checkout_update_order_meta',  array(&$this,'ni_woocommerce_checkout_update_order_meta') );
			
			add_filter('woocommerce_email_order_meta_keys',  array(&$this,'ni_woocommerce_email_order_meta_keys'));
			add_action( 'woocommerce_admin_order_data_after_billing_address',  array(&$this,'ni_woocommerce_admin_order_data_after_billing_address'), 10, 1 );
			
			add_action( 'wp_head', array(&$this,'wp_head'), 12 );
			add_action( 'wp_footer', array(&$this,'wp_footer'), 12 );
		}
		function add_order_date_after_order_notes( $checkout ){
			
			echo '<div id="my_custom_checkout_field">';
			
			woocommerce_form_field( 'ni_order_delivery_date', array(
				'type'          => 'text',
				'class'         => array('my-field-class form-row-wide'),
				'label'         => __('Delivery Date'),
				'placeholder'   => __('Delivery Date'),
			), $checkout->get_value( 'ni_order_delivery_date' ));
			
			echo '</div>';
		}
		function ni_woocommerce_checkout_process(){
			if ( ! $_POST['ni_order_delivery_date'] )
				 wc_add_notice( __( '<strong>Delivery Date</strong> is a required field.' ), 'error' );
			}
		function ni_woocommerce_checkout_update_order_meta( $order_id ){
			if ( ! empty( $_POST['ni_order_delivery_date'] ) ) {
				update_post_meta( $order_id, '_ni_order_delivery_date', sanitize_text_field( $_POST['ni_order_delivery_date'] ) );
			}
		}
		function ni_woocommerce_admin_order_data_after_billing_address($order){
			//$this->print_data($order);
			//$woocommerce->version;
			 global $woocommerce;
			if ( $woocommerce->version<"3.0.0") {
				
				echo '<p><strong>'.__('Order Delivery Date').':</strong> ' . get_post_meta( $order->id, '_ni_order_delivery_date', true ) . '</p>';
			}else{
				echo '<p><strong>'.__('Order Delivery Date').':</strong> ' . get_post_meta( $order->get_id(), '_ni_order_delivery_date', true ) . '</p>';
			}
		
		}
		function ni_woocommerce_email_order_meta_keys($keys ){
			// $keys[] = '_ni_order_delivery_date'; // This will look for a custom field called 'Tracking Code' and add it to emails
				// $keys[] = 'Delivary Date'; // This will look for a custom field called 'Tracking Code' and add it to emails
				
				$keys["Delivary Date"] = '_ni_order_delivery_date';
			return $keys;
		}
		function wp_head(){
			if (is_checkout()){
				wp_enqueue_script( 'jquery-ui-datepicker' );
				// You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
				wp_register_style( 'ni-od-jquery-ui', plugins_url( '../css/jquery-ui.css', __FILE__ ) );
				wp_enqueue_style( 'ni-od-jquery-ui' );  
				wp_enqueue_script( 'ni-order-delivery', plugins_url( '../js/ni-order-delivery.js', __FILE__ ) , array( 'jquery' ) );
				
				?>
				<style type="text/css">
				#ui-datepicker-div { font-size:16px; }
				</style>
				<?php
			}
		}
		function wp_footer(){
			$ni_order_delivery_option = get_option('ni_order_delivery_option');
			$delivary_days = array();
			foreach($ni_order_delivery_option['delivery_day'] as $key=>$value){
				$delivary_days[$value] = $key;
			}
			//echo json_encode( get_option('ni_order_delivery_option'));
			?>
			<script type="text/javascript">
				var ni_order_delivery_option = <?php echo json_encode( get_option('ni_order_delivery_option'));?>;
				var delivary_days2  = <?php echo json_encode($delivary_days);?>;
				//alert("2");
			</script>
			<?php
		}	
	}
}