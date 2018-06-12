<?php
function getInvoice($args = array()){
	$filter = "";
	if(isset($args['trash'])){
		if($args['trash']!=''){
			joinFilter($filter, "i_trash=".$args['trash']);
		}
	} else{
		joinFilter($filter, "i_trash=0");
	}

	if(isset($args['filter'])){
		joinFilter($filter, $args['filter']);
	}

	$extra = "";
	if(isset($args['start']) && isset($args['start'])){
		$extra = "LIMIT $start, $offset";
	}

	return selectp("a.*, (SELECT SUM(ii_qty*ii_price) FROM bill_invoice_item WHERE ii_invoice=a.id AND ii_trash=0) total, 
		(SELECT ifnull(SUM(ip_amount),0) FROM bill_invoice_payment WHERE ip_invoice=a.id AND ip_trash=0) paid,
		getName(i_customer_type, i_customer_id) customer", "bill_invoice a", "$filter", $extra);
}

function getExpenseCategory($select = '', $parent = 0, $depth = 0, $group = false){
	$options = "";
	$categories = select("*", "exp_category", "ec_parent=$parent");
	if($categories->num_rows){		
		while ($category = mysqli_fetch_object($categories)) {
			$subOptions = getExpenseCategory($select, $category->id, $depth + 1, $group);
			if($group && $subOptions!=""){
				$options .= "<optgroup label='".space($depth*3)."$category->ec_name'></optgroup>".$subOptions;
			} else{
				$options .= "<option value='$category->id'";
				if($select==$category->id){
					$options .= " selected";
				}
				$options .= ">".space($depth*3)."$category->ec_name</option>".$subOptions;
			}
		}
	}
	return $options;
}

function docNo($object, $id, $function = 'print'){
	$ret = "";
	if($object=='bill_invoice'){
		$ret = "INV".zerofill($id, 5);
	} elseif($object=='bill_invoice_payment'){
		$ret = "OR".zerofill($id, 5);
	} elseif($object=='bill_invoice_refund'){
		$ret = "RR".zerofill($id, 5);
	}
	if(hasAccess($object, $function)){
		$ret = "<a href='".BASEDIR."/$object/$function/$id' target='_blank'>$ret</a>";
	}

	return $ret;
}