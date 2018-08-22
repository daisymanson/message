<?php
//修改訂單已付款
function db_mod_pb_order_list($db,$arr_input,$arr_def)
{
	$res = $db->table('pb_order_list')
					->update($arr_input)
					->where('pol_order_id = ? ',$arr_def)
					->get();
	return $res;
}

//新增訂單歷程
function db_add_pb_order_date_list($db,$arr_input)
{
    $res = $db->table('pb_order_date')
        ->insert($arr_input)
        ->set();
    return $res;
}


//取得倉到櫃訂單資訊
function db_get_pb_order_list($db,$arr_input)
{
	$res = $db->table("pb_order_list")
				->select('pol_express,pol_order_id,pol_order_status_type')
				->where("pol_del = 0
						AND pol_pat_status_type = '0'
						AND pol_piceup_type = '2'
						AND pol_add_date >= '".date('Y-m-d 00:00:00',strtotime('-30 day'))."' "$arr_input)
				->get();
	return $res;
}
?>
