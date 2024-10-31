<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_Order_Delivery_Dashboard' ) ) {
	include_once('ni-order-delivery-function.php'); 
	class Ni_Order_Delivery_Dashboard extends Ni_Order_Delivery_Function{
		public function __construct(){
			$this->get_next_delivery();
	 	}
		function page_init(){
		?>
		<div class="parent_content">
        		
            	<div class="box-title"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard - Sales Analysis</div>
				<div style="border-bottom:1px solid #b1232f;"></div>
				<div class="box-data">
					<div class="columns-box">
						<div class="columns-title">Total Sales</div>
						<div>
							<div class="columns-icon" style="color:#bbbbbb"><i class="fa fa-cart-plus fa-4x"></i></div>
							<div class="columns-value" style="color:#bbbbbb"><?php  echo wc_price( $this->get_total_sales("ALL")); ?></div>	
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">This Year Sales</div>
						<div>
							<div class="columns-icon"  style="color:#aba3a1"><i class="fa fa-cart-plus fa-4x"></i></div>
							<div class="columns-value"  style="color:#aba3a1"><?php  echo wc_price( $this->get_total_sales("YEAR")); ?></div>	
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">This Month Sales</div>
						<div>
							<div class="columns-icon"  style="color:#bbbbbb"><i class="fa fa-cart-plus fa-4x"></i></div>
							<div class="columns-value" style="color:#bbbbbb"><?php  echo wc_price( $this->get_total_sales("MONTH")); ?></div>	
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">This Week Sales</div>
						<div>
							<div class="columns-icon" style="color:#aba3a1"><i class="fa fa-cart-plus fa-4x"></i></div>
							<div class="columns-value" style="color:#aba3a1"><?php  echo wc_price( $this->get_total_sales("WEEK")); ?></div>	
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">Today Sales</div>
						<div>
							<div class="columns-icon"  style="color:#bbbbbb"><i class="fa fa-cart-plus fa-4x"></i></div>
							<div class="columns-value"  style="color:#bbbbbb"><?php  echo wc_price( $this->get_total_sales("DAY")); ?></div>	
						</div>
					</div>
					<div style="clear:both"></div>
				</div>
                <div class="box-data">
					<div class="columns-box">
						<div class="columns-title">Total Sales Count</div>
						<div>
							<div class="columns-icon" style="color:#aba3a1"><i class="fa fa-line-chart fa-3x"></i></div>
							<div class="columns-value" style="color:#aba3a1"><?php echo $this->get_total_sales_count("ALL"); ?></div>	
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">This Year Sales Count</div>
						<div>
							<div class="columns-icon" style="color:#bbbbbb"><i class="fa fa-line-chart fa-3x"></i></div>
							<div class="columns-value" style="color:#bbbbbb"><?php echo $this->get_total_sales_count("YEAR"); ?></div>	
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">This Month Sales Count</div>
						<div>
							<div class="columns-icon"  style="color:#aba3a1"><i class="fa fa-line-chart fa-3x"></i></div>
							<div class="columns-value"  style="color:#aba3a1"><?php echo $this->get_total_sales_count("MONTH"); ?></div>
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">This Week Sales Count</div>
						<div>
							<div class="columns-icon" style="color:#bbbbbb"><i class="fa fa-line-chart fa-3x"></i></div>
							<div class="columns-value" style="color:#bbbbbb"><?php echo $this->get_total_sales_count("WEEK"); ?></div>
						</div>
					</div>
					<div class="columns-box">
						<div class="columns-title">Today Sales Count</div>
						<div>
							<div class="columns-icon" style="color:#aba3a1"><i class="fa fa-line-chart fa-3x"></i></div>
							<div class="columns-value" style="color:#aba3a1"><?php echo $this->get_total_sales_count("DAY"); ?></div>
						</div>
					</div>
					<div style="clear:both"></div>
				</div>
                <div style="height:50px;">
				<div style="border-bottom:1px solid #b1232f; padding-top:25px"></div>
			</div>
            	<div class="content">
				<div class="box-title"><i class="fa fa-pie-chart" aria-hidden="true"></i> Next Order Delivery</div>
			
				<div class="box-data">
					<table class="data-table">
						<tr>
                           <th>Next Order Delivery</th>
                            <th>Order Count</th>
                            <th  style="text-align:right">Order Total</th>
						</tr>
					   <?php $order_data = $this->get_next_delivery();   ?>
					   <?php foreach($order_data as $key=>$v){ ?>
                       <tr>
                            <td><?php echo $v->order_period; ?></td>
                            <td><?php echo $v->order_count; ?></td>
                            <td style="text-align:right"><?php echo wc_price($v->order_total); ?></td>
                        </tr>
                        <?php } ?>
					</table>
				</div>
			</div>
                
		</div>
		<?php	
		}
		function get_next_delivery(){
			global $wpdb;
			$today = date_i18n("Y-m-d");
			$query = "";
			$query .= " SELECT ";
			$query .= " SUM(order_total.meta_value) as order_total ";
			$query .= " ,COUNT(*) as order_count ";
			$query .= " ,'Today' as order_period";
			$query .= " FROM {$wpdb->prefix}posts as posts ";
			$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as ni_order_delivery_date ON ni_order_delivery_date.post_id=posts.ID  ";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID  ";
			
			
			$query .= " WHERE 1=1 ";
			$query .= " AND posts.post_type ='shop_order' ";
			$query .= " AND order_total.meta_key='_order_total' ";
			
			$query .= " AND ni_order_delivery_date.meta_key ='_ni_order_delivery_date' ";
			
			$query .= " AND ni_order_delivery_date.meta_value='{$today}' ";
			
			$query .= " GROUP BY ni_order_delivery_date.meta_value ";
			
			
			/*Tomorrow */
			$query .= " UNION ALL";
			$query .= " SELECT ";
			$query .= " SUM(order_total.meta_value) as order_total ";
			$query .= " ,COUNT(*) as order_count ";
			$query .= " ,'Tomorrow' as order_period";
			$query .= " FROM {$wpdb->prefix}posts as posts ";
			$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as ni_order_delivery_date ON ni_order_delivery_date.post_id=posts.ID  ";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID  ";
			
			
			$query .= " WHERE 1=1 ";
			$query .= " AND posts.post_type ='shop_order' ";
			$query .= " AND order_total.meta_key='_order_total' ";
			
			$query .= " AND ni_order_delivery_date.meta_key ='_ni_order_delivery_date' ";
			
			$query .= " AND ni_order_delivery_date.meta_value=DATE_ADD('{$today}', INTERVAL 1 day)"  ;
			
			$query .= " GROUP BY ni_order_delivery_date.meta_value ";
			
			
			/*3rd Day */
			$query .= " UNION ALL";
			$query .= " SELECT ";
			$query .= " SUM(order_total.meta_value) as order_total ";
			$query .= " ,COUNT(*) as order_count ";
			$query .= " ,'Next 3rd Day' as order_period";
			$query .= " FROM {$wpdb->prefix}posts as posts ";
			$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as ni_order_delivery_date ON ni_order_delivery_date.post_id=posts.ID  ";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID  ";
			
			
			$query .= " WHERE 1=1 ";
			$query .= " AND posts.post_type ='shop_order' ";
			$query .= " AND order_total.meta_key='_order_total' ";
			
			$query .= " AND ni_order_delivery_date.meta_key ='_ni_order_delivery_date' ";
			
			$query .= " AND ni_order_delivery_date.meta_value=DATE_ADD('{$today}', INTERVAL 2 day)"  ;
			
			$query .= " GROUP BY ni_order_delivery_date.meta_value ";
			
			/*4th Day */
			$query .= " UNION ALL";
			$query .= " SELECT ";
			$query .= " SUM(order_total.meta_value) as order_total ";
			$query .= " ,COUNT(*) as order_count ";
			$query .= " ,'Next 4th Day' as order_period";
			$query .= " FROM {$wpdb->prefix}posts as posts ";
			$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as ni_order_delivery_date ON ni_order_delivery_date.post_id=posts.ID  ";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID  ";
			
			
			$query .= " WHERE 1=1 ";
			$query .= " AND posts.post_type ='shop_order' ";
			$query .= " AND order_total.meta_key='_order_total' ";
			
			$query .= " AND ni_order_delivery_date.meta_key ='_ni_order_delivery_date' ";
			
			$query .= " AND ni_order_delivery_date.meta_value=DATE_ADD('{$today}', INTERVAL 3 day)"  ;
			
			$query .= " GROUP BY ni_order_delivery_date.meta_value ";
			
			/*5th Day */
			$query .= " UNION ALL";
			$query .= " SELECT ";
			$query .= " SUM(order_total.meta_value) as order_total ";
			$query .= " ,COUNT(*) as order_count ";
			$query .= " ,'Next 5th Day' as order_period";
			$query .= " FROM {$wpdb->prefix}posts as posts ";
			$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as ni_order_delivery_date ON ni_order_delivery_date.post_id=posts.ID  ";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID  ";
			
			
			$query .= " WHERE 1=1 ";
			$query .= " AND posts.post_type ='shop_order' ";
			$query .= " AND order_total.meta_key='_order_total' ";
			
			$query .= " AND ni_order_delivery_date.meta_key ='_ni_order_delivery_date' ";
			
			$query .= " AND ni_order_delivery_date.meta_value=DATE_ADD('{$today}', INTERVAL 4 day)"  ;
			
			$query .= " GROUP BY ni_order_delivery_date.meta_value ";
			
			//echo $query;
			$row = $wpdb->get_results($query);
			//$this->print_data($row);
			//$this->print_data($wpdb);	
			
			return $row;
		}
		function get_total_sales($period="CUSTOM",$start_date=NULL,$end_date=NULL){
			global $wpdb;	
			$query = "SELECT
					SUM(order_total.meta_value)as 'total_sales'
					FROM {$wpdb->prefix}posts as posts			
					LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID 
					
					WHERE 1=1
					AND posts.post_type ='shop_order' 
					AND order_total.meta_key='_order_total' ";
					
			$query .= " AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed')
						
						";
			if ($period =="DAY"){		
				//$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) "; 
				//$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') = SUBDATE(date_format(CURDATE(), '%Y-%m-%d'),1) "; 
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) "; 
			}
			if ($period =="WEEK"){		
				//$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 WEEK) "; 
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND 
      WEEK(date_format( posts.post_date, '%Y-%m-%d')) = WEEK(CURRENT_DATE()) ";
			}
			if ($period =="MONTH"){		
				//$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 MONTH) "; 
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND 
      MONTH(date_format( posts.post_date, '%Y-%m-%d')) = MONTH(CURRENT_DATE()) ";
			}
			if ($period =="YEAR"){		
				//$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
				$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
			}
			
			
			//echo $query;		
					
			//$query .=" AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
					
			$results = $wpdb->get_var($query);
			$results = isset($results) ? $results : "0";
			return $results;
		}
		function get_total_sales_count($period="CUSTOM",$start_date=NULL,$end_date=NULL){
			global $wpdb;	
			$query = "SELECT
					count(order_total.meta_value)as 'sales_count'
					FROM {$wpdb->prefix}posts as posts			
					LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID 
					
					WHERE  1 = 1
					AND posts.post_type ='shop_order' 
					AND order_total.meta_key='_order_total' ";
					//if ($start_date!=NULL && $end_date!=NULL)
					//$query .=" AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$start_date}' AND '{$end_date}'";
			$query .= " AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed') ";
			
			if ($period =="DAY"){		
				//$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) "; 
			//$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') = SUBDATE(date_format(CURDATE(), '%Y-%m-%d'),1) "; 
			$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) "; 
			}
			if ($period =="WEEK"){		
				//$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 WEEK) "; 
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND 
      WEEK(date_format( posts.post_date, '%Y-%m-%d')) = WEEK(CURRENT_DATE()) ";
			}
			if ($period =="MONTH"){		
			//	$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 MONTH) "; 
			
			$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND 
      MONTH(date_format( posts.post_date, '%Y-%m-%d')) = MONTH(CURRENT_DATE()) ";
			
			}
			if ($period =="YEAR"){		
				//$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
				$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
			}
			//echo $query;
			$results = $wpdb->get_var($query);	
			$results = isset($results) ? $results : "0";	
			return $results;
		}
	}
}