<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_Order_Delivery_Settings' ) ) {
include_once('ni-order-delivery-function.php'); 
class Ni_Order_Delivery_Settings extends Ni_Order_Delivery_Function{
	 	 private $options = array(); // Define $options property
		 public function __construct(){
			 add_action( 'admin_menu', array( $this, 'add_setting_page' ) );
			 //add_action( 'admin_init', array( $this, 'admin_init' ) );
			 add_action( 'admin_init', array( $this, 'admin_init_save'),100 );
		 }
		 function add_setting_page(){
			add_submenu_page('ni-order-delivary', 'Delivery Settings', 'Delivery Settings', 'manage_options', 'ni-order-delivery-settings' , array(&$this,'add_menu_page'));
		}
		function admin_init_save(){
			//echo json_encode($_REQUEST);
		//die;
			if (isset($_REQUEST["ni_order_delivery_option"])){
				$this->options = get_option('ni_order_delivery_option');
				update_option('ni_order_delivery_option',$_REQUEST["ni_order_delivery_option"]);
			//die;
			}
		}
		function add_menu_page(){
			// Set class property
			$this->options = get_option('ni_order_delivery_option');
			//$this->options = get_option( 'invoice_setting_option' );
			//echo json_encode($_REQUEST)
			?>
			<div class="wrap">
				<form  method="post">
				<?php
					// This prints out all hidden setting fields
					settings_fields( 'ni_order_delivery_option_group' );   
					do_settings_sections( 'ni-order-delivery-section' );
					//submit_button(); 
				?>
                <table>
                	<tr>
                    	<td>
                        	<table>
                            	<tr>
                                	<td>Enable Order Delivery</td>
                                    <td><input type="checkbox" name="ni_order_delivery_option[ni_enable_order_delivery]"  
                        value="<?php echo isset($this->options['ni_enable_order_delivery'])?'yes' :'no' ?>"
									<?php 
									echo	isset($this->options['ni_enable_order_delivery'])?' checked="checked"' :'';	
									 ?>  
                                     /> 
                         			</td>
                                </tr>
                                <tr>
                                	<td colspan="2">
                                    	Check for enable the order delivery functionality.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                        	<table>
                            	<tr>
                                	<td>Start Days</td>
                                    <td><input type="text" name="ni_order_delivery_option[ni_delivery_start_day]"
                                    	value="<?php echo isset( $this->options['ni_delivery_start_day'])?esc_attr($this->options['ni_delivery_start_day']):'1'; ?>"
                                     />
                                     </td>
                                    
                                    <td>End Days</td>
                                    <td><input type="text" name="ni_order_delivery_option[ni_delivery_end_day]"
                                    	value="<?php echo isset( $this->options['ni_delivery_end_day'])?esc_attr($this->options['ni_delivery_end_day']):'10'; ?>"
                                     />
                                     </td>
                                    
                                </tr>
                                <tr>
                                	<td colspan="2">  Start delivery day for example if you put 0 then delivery date start form today, 1 for tomorrow and so on.
                                   </td>
                                    <td colspan="2">  Delivery end days for example if you put 10 then it’s how next 10 delivery dates in delivery date calendar.</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                        	<table>
                            	<tr>
                                	<td>Delivery Days</td>
                                    <td><table>
                            	<tr>
                                	<td><input type="checkbox" name="ni_order_delivery_option[delivery_day][sunday]"  value="0"
									<?php 
								echo	$this->is_checked("sunday");	
									 ?>  
                                     /> 
                                    </td>
                                	<td>Sunday</td>
                                </tr>
                                <tr>
                                	<td><input type="checkbox" name="ni_order_delivery_option[delivery_day][monday]"  value="1"
                                   <?php 
										echo	$this->is_checked("monday");	
									 ?> 
                                      
                                     /> 
                                    </td>
                                	<td>Monday</td>
                                </tr>
                                <tr>
                                	<td><input type="checkbox" name="ni_order_delivery_option[delivery_day][tuesday]" value="2"
                                     <?php 
									echo	$this->is_checked("tuesday");	
									 ?>  
                                     /> 
                                    </td>
                                	<td>Tuesday</td>
                                </tr>
                                <tr>
                                	<td><input type="checkbox" name="ni_order_delivery_option[delivery_day][wednesday]" value="3"
                                     <?php 
									echo	$this->is_checked("wednesday");	
									 ?>  
                                     /> 
                                	<td>Wednesday</td>
                                </tr>
                                <tr>
                                	<td><input type="checkbox" name="ni_order_delivery_option[delivery_day][thursday]"  value="4"
									<?php 
									echo	$this->is_checked("thursday");	
									 ?>  
                                     /> 
                                	<td>Thursday</td>
                                </tr>
                                <tr>
                                	<td><input type="checkbox" name="ni_order_delivery_option[delivery_day][friday]" value="5"
                                     <?php 
									echo	$this->is_checked("friday");	
									 ?>  
                                     /> 
                                	<td>Friday</td>
                                </tr>
                                <tr>
                                	<td><input type="checkbox" name="ni_order_delivery_option[delivery_day][saturday]"  value="6"
                                     <?php 
									echo	$this->is_checked("saturday");	
									 ?>  
                                     /> 
                                	<td>Saturday</td>
                                </tr>
                            </table></td>
                                </tr>
                                <tr>
                                	<td colspan="2">Check the available delivery days and only selected days is enable in the delivery date calendar.
</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                        	<table>
                            	<tr>
                                	<td></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
       			<?php submit_button(); ?>
				</form>
			</div>
			<?php
		}
		function is_checked($day){
			return	isset($this->options['delivery_day'][$day])?' checked="checked"' :'';
		}
		function admin_init(){
			
			register_setting(
				'ni_order_delivery_option_group', // Option group
				'ni_order_delivery_option', // Option name
				array( $this, 'sanitize' ) // Sanitize
			);
			
			add_settings_section(
				'setting_section_id', // ID
				'Invoice Settings', // Title
				array( $this, 'print_section_info' ), // Callback
				'ni-order-delivery-section' // Page
			);
			
			/*Add Store Name*/ 
			add_settings_field(
				'store_name', 
				'Store Name', 
				array( $this, 'add_store_name' ), 
				'ni-order-delivery-section', 
				'setting_section_id'
			);    
		}
		function print_section_info(){
		}
		function add_store_name(){
			echo "dsadsa";
			printf(
				'<input type="text" id="start_day" name="ni_order_delivery_option[start_day]" value="%s" />',
				isset( $this->options['start_day'] ) ? esc_attr( $this->options['start_day']) : ''
				//esc_attr( $this->options['name'])
			);
			echo "dsadsa";
			printf(
				'<input type="text" id="end_day" name="ni_order_delivery_option[end_day]" value="%s" />',
				isset( $this->options['end_day'] ) ? esc_attr( $this->options['end_day']) : ''
				//esc_attr( $this->options['name'])
			);
		}
		function sanitize( $input ){
			
			/*
			if( !is_numeric( $input['id_number'] ) )
				$input['id_number'] = '';  
		
			if( !empty( $input['title'] ) )
				$input['title'] = sanitize_text_field( $input['title'] );
				
			if( !empty( $input['color'] ) )
				$input['color'] = sanitize_text_field( $input['color'] );
				*/
			return $input;
		}
  	}
}

