<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_Order_Delivery_Function' ) ) {
	class Ni_Order_Delivery_Function{
		public function __construct(){
	 	}
		function check_ni_enable_order_delivery(){
			$ni_order_delivery_option  = get_option('ni_order_delivery_option');
			return   isset($ni_order_delivery_option["ni_enable_order_delivery"])?'yes':'no';
		}
		function print_data($r){
			echo '<pre>',print_r($r,1),'</pre>';	
		}
		function get_order_delivery_columns(){
			$columns = array(					
				 "order_id"				=>"#ID"
				,"order_date"			=>"Order Date"
				,"billing_first_name"	=>"Billing First Name"
				,"billing_email"		=>"Billing Email"
				,"billing_country"		=>"Billing Country"
				,"order_status"			=>"Status"
				//,'product_name'			=>"Product Name"
				,"order_currency"		=>"Order Currency"
				,"payment_method_title" =>"Payment Method"
				,"order_total"			=>"Order Total"
				,"ni_order_delivery_date"			=>"Delivery Date"
				
			  );
			return $columns;
		}
		function get_items_id_list($order_items = array(),$field_key = 'order_id', $return_default = '-1' , $return_formate = 'string'){
			$list 	= array();
			$string = $return_default;
			if(count($order_items) > 0){
				foreach ($order_items as $key => $order_item) {
					if(isset($order_item->$field_key))
						$list[] = $order_item->$field_key;
				}
				
				$list = array_unique($list);
				
				if($return_formate == "string"){
					$string = implode(",",$list);
				}else{
					$string = $list;
				}
			}
			return $string;
		}
		public static function get_postmeta($order_ids = '0', $columns = array(), $extra_meta_keys = array(), $type = 'all'){
			
			global $wpdb;
			
			$post_meta_keys = array();
			
			if(count($columns)>0)
			foreach($columns as $key => $label){
				$post_meta_keys[] = $key;
			}
			
			foreach($extra_meta_keys as $key => $label){
				$post_meta_keys[] = $label;
			}
			
			foreach($post_meta_keys as $key => $label){
				$post_meta_keys[] = "_".$label;
			}
			
			$post_meta_key_string = implode("', '",$post_meta_keys);
			
			$sql = " SELECT * FROM {$wpdb->postmeta} AS postmeta";
			
			$sql .= " WHERE 1*1";
			
			if(strlen($order_ids) >0){
				$sql .= " AND postmeta.post_id IN ($order_ids)";
			}
			
			if(strlen($post_meta_key_string) >0){
				$sql .= " AND postmeta.meta_key IN ('{$post_meta_key_string}')";
			}
			
			if($type == 'total'){
				$sql .= " AND (LENGTH(postmeta.meta_value) > 0 AND postmeta.meta_value > 0)";
			}
			
			$sql .= " ORDER BY postmeta.post_id ASC, postmeta.meta_key ASC";
			
			//echo $sql;return '';
			
			$order_meta_data = $wpdb->get_results($sql);			
			
			if($wpdb->last_error){
				echo $wpdb->last_error;
			}else{
				$order_meta_new = array();	
					
				foreach($order_meta_data as $key => $order_meta){
					
					$meta_value	= $order_meta->meta_value;
					
					$meta_key	= $order_meta->meta_key;
					
					$post_id	= $order_meta->post_id;
					
					$meta_key 	= ltrim($meta_key, "_");
					
					$order_meta_new[$post_id][$meta_key] = $meta_value;
					
				}
			}
			
			return $order_meta_new;
			
		}
		public function get_request($name,$default = NULL,$set = false){
			if(isset($_REQUEST[$name])){
				$newRequest = $_REQUEST[$name];
			
			if(is_array($newRequest)){
				$newRequest = implode(",", $newRequest);
			}else{
				$newRequest = trim($newRequest);
			}
			
			if($set) $_REQUEST[$name] = $newRequest;
			
			return $newRequest;
				}else{
					if($set) 	$_REQUEST[$name] = $default;
				return $default;
			}
		}
		function get_country_name($code){	
			$name = "";
			if (strlen($code)>0){
				$name= WC()->countries->countries[ $code];	
				$name  = isset($name) ? $name : $code;
			}
			return $name;
		}
		function ExportToCsv($filename = 'export.csv',$rows =array(),$columns =array(),$format="csv"){				
			global $wpdb;
			$csv_terminated = "\n";
			$csv_separator = ",";
			$csv_enclosed = '"';
			$csv_escaped = "\\";
			$fields_cnt = count($columns); 
			$schema_insert = '';
			
			if($format=="xls"){
				$csv_terminated = "\r\n";
				$csv_separator = "\t";
			}
				
			foreach($columns as $key => $value):
				$l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $value) . $csv_enclosed;
				$schema_insert .= $l;
				$schema_insert .= $csv_separator;
			endforeach;// end for
		 
		   $out = trim(substr($schema_insert, 0, -1));
		   $out .= $csv_terminated;
			
			//printArray($rows);
			
			for($i =0;$i<count($rows);$i++){
				
				//printArray($rows[$i]);
				$j = 0;
				$schema_insert = '';
				foreach($columns as $key => $value){
						
						
						 if ($rows[$i][$key] == '0' || $rows[$i][$key] != ''){
							if ($csv_enclosed == '')
							{
								$schema_insert .= $rows[$i][$key];
							} else
							{
								$schema_insert .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $rows[$i][$key]) . $csv_enclosed;
							}
						 }else{
							$schema_insert .= '';
						 }
						
						
						
						if ($j < $fields_cnt - 1)
						{
							$schema_insert .= $csv_separator;
						}
						$j++;
				}
				$out .= $schema_insert;
				$out .= $csv_terminated;
			}
			
			if($format=="csv"){
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Content-Length: " . strlen($out));	
				header("Content-type: text/x-csv");
				header("Content-type: text/csv");
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=$filename");
			}elseif($format=="xls"){
				
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Content-Length: " . strlen($out));
				header("Content-type: application/octet-stream");
				header("Content-Disposition: attachment; filename=$filename");
				header("Pragma: no-cache");
				header("Expires: 0");
			}
			
			echo $out;
			exit;
		 
		}
	}
}