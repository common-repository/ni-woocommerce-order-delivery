<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_Order_Delivery_List' ) ) {
include_once('ni-order-delivery-function.php'); 
	class Ni_Order_Delivery_List extends Ni_Order_Delivery_Function{
		 var $ni_od_constant = array();
		 public function __construct($ni_od_constant = array()){
			$this->ni_od_constant = $ni_od_constant ;
			//$this->get_order_data();
			//echo "sadsa";
		 }
		 function page_init(){
		 	?>
            <div  class="containt-main">
                <div class="report-title">Order List</div>
                    <form id="frm_ni_order_delivery" class="frm_ni_order_delivery" name="frm_ni_order_delivery" action="" method="post">
                        <table class="ni-main-table">
                            <tr style="display:none">
                            	<td>Search By</td>
                                <td><input type="radio" name="searchby" value="order_date" checked="checked" /></td>		
                                <td><input type="radio" name="searchby"  value="delivery_date"/></td>		
                            </tr>
                            <tr>
                                <td style="width:50%; padding-left:10px;">Select Order</td>
                                <td style="width:50%" ><select name="select_order" id="select_order" style="width:250px" >
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="last_7_days">Last 7 days</option>
                                <option value="last_30_days">Last 30 days</option>
                                <option value="this_year">This year</option>
                                </select>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="text-align:right;padding-right:45px;">
                                <input type="submit" class="ni_btn ni_btn_od" value="Search" id="SearchOrder" /></td>
                            </tr>
                        </table>
                    <input type="hidden" name="action" value="ni_order_delivery_ajax" />
                    <input type="hidden" name="call" value="ni_order_delivery_list" />
                    </form>
                </div>
               <br /> 
            <div class="ajax_content"></div>
            <?php
		 }
		 function get_order_row($type="DEFAULT"){
			global $wpdb;	
			//echo json_encode($_REQUEST);
			//die;
			$today = date_i18n("Y-m-d");
	    	$select_order  = $this->get_request("select_order",$today,true);
			//$searchby 	  = $this->get_request("searchby","order_date",true);
			//$select_order = "today";
			
			$query = "SELECT 
				posts.ID as order_id
				,post_status as order_status
				, date_format( posts.post_date, '%Y-%m-%d') as order_date 
				FROM {$wpdb->prefix}posts as posts			
				WHERE 
						posts.post_type ='shop_order' 
						";
				 switch ($select_order) {
					case "today":
						$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$today}' AND '{$today}'";
						break;
					case "yesterday":
						$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') = date_format( DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d')";
						break;
					case "last_7_days":
						$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 7 DAY), '%Y-%m-%d') AND   '{$today}' ";
						break;
					case "last_30_days":
							$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 30 DAY), '%Y-%m-%d') AND   '{$today}' ";
						break;	
					case "this_year":
						$query .= " AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(CURDATE(), '%Y-%m-%d'))";			
						break;		
					default:
						$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$today}' AND '{$today}'";
				}
			$query .= "order by posts.post_date DESC";	
			
		 if ($type=="ARRAY_A") /*Export*/{
		 	$row = $wpdb->get_results( $query, ARRAY_A );
		 }
		 if($type=="DEFAULT") /*default*/{
		 	$row = $wpdb->get_results( $query);
		 }
		 if($type=="COUNT") /*Count only*/	
		 	$row = $wpdb->get_var($query);		
			return $row;	
		}
		function get_order_data()
		{
			$order_row = $this->get_order_row("DEFAULT");
			$extra_meta_keys 	= array();
			$post_ids 			= $this->get_items_id_list($order_row,'order_id');
			$columns 			= $this->get_order_delivery_columns();
			$postmeta_datas 	= $this->get_postmeta($post_ids, $columns,$extra_meta_keys);
		
			if(count($order_row)> 0){
				foreach($order_row as $k => $v){
					/*Order Data*/
					$order_id =$v->order_id;
					//$order_row[$k]->product_name =  $this->get_product_name($order_id );
					$postmeta_data 	= isset($postmeta_datas[$order_id]) ? $postmeta_datas[$order_id] : array();
										
					foreach($postmeta_data as $postmeta_key => $postmeta_value){
						$order_row[$k]->{$postmeta_key}	= $postmeta_value;
					}
				}
			}
			//$this->print_data($order_row);
			return $order_row;
		}
		function get_order_delivery_list(){
			$order_row = $this->get_order_data();
			$columns   = $this->get_order_delivery_columns();
			$td_value  = "";
			
			?>
            <?php if (count($order_row)>0): ?>
            <div style="text-align:right;margin-bottom:10px">
                <form id="ni_frm_order_export" action="" method="post">
                    <input type="submit" value="CSV" name="btn_od_csv_export" class="ni_btn ni_btn_od" id="btn_od_csv_export" />
                    <input type="hidden" name="select_order" value="<?php echo $this->get_request("select_order");  ?>" />
                </form>
            </div>
            <?php endif;?>
            <div class="ni-od-data-table">
            	<table border="1">
                    <tr>
                        <?php foreach($columns as $kye=>$value) :?>	
                        <th><?php echo $value; ?></th>
                        <?php endforeach; ?>	
                    </tr>
                    <?php if (count($order_row)==0): ?>
                    <td colspan="<?php echo count($columns ); ?>"> No record found</td>
                    <?php die; ?> 
                    <?php endif;?>
                    <?php foreach($order_row as $kye=>$value) :?>
                    <tr>
                        <?php foreach($columns as $ckye=>$cvalue) :?>	
                        <td>
                            <?php //echo isset ($value->$ckye)?$value->$ckye:"";  ?>
                            <?php 
                                switch ($ckye) {
                                    case "order_status":
                                        $td_value = ucfirst ( str_replace("wc-","", $value->$ckye));
                                    break;	
                                    case "billing_country":
                                        $td_value = $this->get_country_name($value->$ckye);
                                    break;
                                default:
                                    $td_value = isset ($value->$ckye)?$value->$ckye:"";
                                }
                            ?>
                            <?php echo $td_value; ?>
                        </td>
                         <?php endforeach; ?>
                     </tr>    	
                    <?php endforeach; ?>	
                </table>
            </div>    
            <?php
			
			//echo json_encode($columns );
			die;
		}
		function ni_order_delivery_export($file_name,$file_format){
			$columns 			= $this->get_order_delivery_columns();
			$rows = $this->get_order_data();
			  //$this->print_data(  $rows);
			$i = 0;
			$export_rows = array();
			foreach ( $rows as $rkey => $rvalue ):	
					foreach($columns as $key => $value):
						switch ($key) {
							case "order_status":
								$export_rows[$i][$key] = ucfirst ( str_replace("wc-","",   $rvalue->$key));
								break;
							case "billing_country":
								$export_rows[$i][$key] = $this->get_country_name($rvalue->billing_country);
								break;	
							default:
								$export_rows[$i][$key] = isset($rvalue->$key) ? $rvalue->$key : '';
								break;
				}
					endforeach;
					$i++;
			endforeach;
			$this->ExportToCsv($file_name ,$export_rows,$columns,$file_format); 
			//die;
		}
	}
}