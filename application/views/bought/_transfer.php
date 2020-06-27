<?=form_open(current_url());?>
<div class="row">
    <div class="col-md-6">
        <label>Vælg butik</label>
        <select class="form-control" id="devices_edit" name="boutique" required style="margin-bottom: 10px">
        	<option value="">-</option>
        	<?php
        	foreach($boutiques as $boutique):
        	if($order_info->boutique_id == $boutique->id){}else{
        	?>
        	<option value="<?=$boutique->id;?>"><?=$boutique->name;?></option>
        	<?php
        	}
        	endforeach;
        	?>
        </select>
    </div>

    <div class="col-md-12">
    	<input type="hidden" name="id" value="<?=$order_info->id;?>" />
    	<input type="submit" class="btn btn-success" name="transfer" value="Overfør" style="margin-top: 20px" />
    </div>

</div>
<?=form_close();?>