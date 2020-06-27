<?=form_open(current_url());?>
<div class="row">
    <div class="col-md-12">
        <label>Vælg dele der er brugt herunder</label>
        <select class="form-control" id="choosed_part" name="model" style="margin-bottom: 10px">
        	<option value="">-</option>
        	<?php
        	foreach($parts as $part):
        	?>
        	<option value="<?=$part->id;?>" raw-text="<?=$part->name;?>" price="<?=$part->price;?>"><?=$part->name;?> (<?=$part->price;?> kr - <?=$part->inventory;?> på lager)</option>
        	<?php
        	endforeach;
        	?>
        </select>
        
        <div class="parts_used_area">
        	<?php
        	$this->db->where('order_id',$order_info->id);
        	$parts_used = $this->db->get('parts_used')->result();
        	
        	foreach($parts_used as $part_used){
        	
        	// get part info
        	$this->db->where('id',$part_used->part_id);
        	$part_info = $this->db->get('parts')->result();
        	
        	if($part_info){
	        	$part_name = $part_info[0]->name;
	        	$part_price = $part_info[0]->price;
	        	$part_id    = $part_info[0]->id;
        	}else{
	        	$part_name = '';
	        	$part_price = '';
	        	$part_id = 0;
        	}
        	
        	?>
        	<div class="added_part_wrapper"><div class="remove_added_part" style="width: 200px; float: left;"><?=$part_name;?></div>
				<div style="width: 150px; float: left"><?=$part_price;?> kr</div>
				<div style="width: 50px; float: left"><a href="#" class="remove_part_from_list">Slet</a></div>
			<input type="hidden" name="used_parts[]" value="<?=$part_id;?>" /></div>
			<?php
			}
			?>
        </div>
        
    </div>
    

    <div class="col-md-12">
    	<input type="hidden" name="id" value="<?=$order_info->id;?>" />
    	<input type="submit" class="btn btn-success" name="parts_used" value="Rediger" style="margin-top: 20px" />
    </div>

</div>
<?=form_close();?>