<?php 
$fields = ['start_date','end_date','days','publisher','service','page','language','width','height','column_size','min_col_width','max_col_width','bw_unit_price','color_unit_price','min_words','max_words','init_words','additional_word_price','fixed_place_rate','bold_text_rate','first_position_rate','in_screen_rate','in_box_rate','master_agent_commission_bw','agent_commission_bw','master_agent_commission_color','agent_commission_color'];

foreach ($fields as $field) {
	if(isset($post->$field) && nn($post->$field)) {
		$object->$field = $post->$field;
	}
}

$object->min_col = isset($post->min_col)?$post->min_col:0;
$object->max_col = isset($post->max_col)?$post->max_col:0;
$object->daily_approval_limit = isset($post->daily_approval_limit)?$post->daily_approval_limit:0;
R::store($object); 
?>