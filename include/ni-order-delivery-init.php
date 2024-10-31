<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_Order_Delivery_Init' ) ) {
include_once('ni-order-delivery-function.php'); 
class Ni_Order_Delivery_Init extends Ni_Order_Delivery_Function{
		 var $ni_od_constant = array();
		 public function __construct($ni_od_constant = array()){
			$enable_order_delivery = $this->check_ni_enable_order_delivery();
			if ($enable_order_delivery == 'yes'){
				$this->add_ni_order_delivery_hook();
			}
			add_action( 'admin_menu',  array(&$this,'admin_menu' ));
			$this->add_order_delivery_setting_page();
			
			add_action( 'admin_enqueue_scripts',  array(&$this,'admin_enqueue_scripts' ));
			add_action( 'wp_ajax_ni_order_delivery_ajax',  array(&$this,'ni_order_delivery_ajax' )); /*used in form field name="action" value="my_action"*/
			add_action('admin_init', array( &$this, 'admin_init' ) );
		 }
		 function add_ni_order_delivery_hook(){
			 include_once("ni-order-delivery-hook.php");
			 $odh =  new Ni_Order_Delivery_Hook();
			 
		 }
		
		 
		 function add_order_delivery_setting_page(){
			 include_once('ni-order-delivery-settings.php'); 
			 $obj = new  Ni_Order_Delivery_Settings();
		 }
		 function admin_menu(){
			add_menu_page('Ni Order Delivary','Ni Order Delivary','manage_options','ni-order-delivary',array(&$this,'add_menu_page'),'dashicons-products',57.389);
				
				add_submenu_page('ni-order-delivary', 'Dashboard', 'Dashboard', 'manage_options', 'ni-order-delivary' , array(&$this,'add_menu_page'));

				
				add_submenu_page('ni-order-delivary', 'Order List', 'Order List', 'manage_options', 'ni-order-delivery-list' , array(&$this,'add_menu_page'));
				
				//add_submenu_page('ni-order-delivary', 'Addons', 'Addons', 'manage_options', 'ni-addons' , array(&$this,'add_menu_page'));
			
		 }
		 function add_menu_page(){
			if (isset($_REQUEST["page"])){
				$page  = $_REQUEST["page"];
				if ($page  == "ni-order-delivery-list"){
					include_once("ni-order-delivery-list.php");
					$obj = new Ni_Order_Delivery_List();
					$obj->page_init();
				}
				if ($page  == "ni-order-delivary"){
					include_once("ni-order-delivery-dashboard.php");
					$obj = new Ni_Order_Delivery_Dashboard();
					$obj->page_init();
				}
			}
		 }
		 function ni_order_delivery_ajax(){
			 if (isset($_REQUEST["call"])){
				 if($_REQUEST["call"] ==  "ni_order_delivery_list"){
					 	include_once("ni-order-delivery-list.php");
						$obj = new Ni_Order_Delivery_List();
						$obj->get_order_delivery_list();
				 }
			 
			 }
		 }
		 function admin_enqueue_scripts($hook){
			if (isset($_REQUEST["page"])){
				$page = $_REQUEST["page"];
				if ($page=="ni-order-delivery-list"){
					wp_enqueue_script('ni-od-list-script', plugins_url( '../js/ni-order-delivery-list.js', __FILE__ ), array('jquery') );
					wp_enqueue_script('ni-od-script', plugins_url( '../js/script.js', __FILE__ ), array('jquery') );
					wp_localize_script('ni-od-script', 'ni_od_ajax_object',array( 'ni_od_ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
					wp_register_style( 'ni-od-admin-css', plugins_url( '../css/ni-admin-order-delivery.css', __FILE__ ));
					wp_enqueue_style( 'ni-od-admin-css' );
				}
				
				
				if ($page=="ni-order-delivary"){
					wp_register_style( 'ni-od-awesome-css', plugins_url( '../css/font-awesome.css', __FILE__ ));
					wp_enqueue_style( 'ni-od-awesome-css' );
				
					wp_register_style( 'ni-od-dashboard-css', plugins_url( '../css/ni-od-dashboard.css', __FILE__ ));
					wp_enqueue_style( 'ni-od-dashboard-css' );
				}
				
				//ni-od-dashboard.css
				
			}
		}
		function admin_init(){
			if(isset($_REQUEST['btn_od_csv_export'])){
				$today = date_i18n("Y-m-d-H-i-s");				
				$FileName = "delivery-order-list"."-".$today.".csv";	
				
				include_once("ni-order-delivery-list.php");
				$obj = new Ni_Order_Delivery_List();
				$obj->ni_order_delivery_export($FileName,"csv");
				die;
			}
		}
  	}
}