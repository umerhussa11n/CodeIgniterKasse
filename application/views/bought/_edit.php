<?=form_open(current_url());?>
<div class="row">
    <div class="col-md-6">
        <label>Model</label>
        <select class="form-control" id="devices_edit" name="model" required style="margin-bottom: 10px">
        	<option value="">-</option>
        	<?php
        	foreach($products as $product):
        	?>
        	<option <?php if($order_info->product_id == $product->id): echo 'selected="true"'; endif; ?> value="<?=$product->id;?>"><?=$product->name;?></option>
        	<?php
        	endforeach;
        	?>
        </select>
    </div>
    
    <div class="col-md-6">
        <label>GB</label>
        <select class="form-control" id="gbs_edit" name="gb" required style="margin-bottom: 10px">
        	<option value="">-</option>
        	<?php
        	foreach($gbs_list as $gbview):
        	?>
        	<option <?php if($order_info->gb_id == $gbview->id): echo 'selected="true"'; endif; ?> value="<?=$gbview->id;?>" class="<?=$gbview->product_id;?>"><?=$gbview->name;?></option>
        	<?php
        	endforeach;
        	?>
        </select>
    </div>
    
    <div class="col-md-6">
        <label>IMEI</label>
        <input type="text" class="form-control" name="imei" value="<?=$order_info->imei;?>" required name="password" required style="margin-bottom: 10px" />
    </div>
    <div class="col-md-6">
        <label>Farve</label>
        <input type="text" class="form-control" name="color" value="<?=$order_info->color;?>"  required name="password" required style="margin-bottom: 10px" />
    </div>
    <hr />
    
    <div class="col-md-6">
        <label>Sælger navn</label>
        <input type="text" class="form-control" name="seller_name" value="<?=$order_info->name;?>"  required name="password" required style="margin-bottom: 10px" />
    </div>
    
    <div class="col-md-6">
        <label>Sælger ID</label>
        <input type="text" class="form-control" name="seller_id" required value="<?=$order_info->seller_id;?>"  name="password" required placeholder="Pas eller kørekort" style="margin-bottom: 10px" />
    </div>
    
    <div class="col-md-6">
        <label>Tlf nummer på sælger</label>
        <input type="text" class="form-control" name="number" required name="password" value="<?=$order_info->number;?>"  required style="margin-bottom: 10px" />
    </div>
    
    <div class="col-md-6">
        <label>Email på sælger</label>
        <input type="text" class="form-control" name="seller_email" required name="password" value="<?=$order_info->seller_email;?>"  required style="margin-bottom: 10px" />
    </div>
    
    <div class="col-md-6">
		<label>Sælger firmanavn (valgfrit)</label>
		<input type="text" class="form-control" value="<?=$order_info->company;?>" name="company_name" required style="margin-bottom: 10px" />
	</div>
    
    <div class="col-md-12"><hr /></div>
    
    <div class="col-md-12">
       
        <label>Evt. fejl og mangler</label>
        <textarea class="form-control" rows="6" name="errors" style="margin-bottom: 10px"><?=$order_info->errors;?></textarea>
    
    </div>
    
    <div class="col-md-12">
	        
        <label>Tilstand</label>
        <select class="form-control" name="condition" style="margin-bottom: 10px" required>
        	<option value="">-</option>
        	<option value="1" <?php if($order_info->condition == 1): echo 'selected="true"'; endif; ?>>Helt ny</option>
        	<option value="2" <?php if($order_info->condition == 2): echo 'selected="true"'; endif; ?>>God stand</option>
        	<option value="3" <?php if($order_info->condition == 3): echo 'selected="true"'; endif; ?>>Slidt</option>
        	<option value="4" <?php if($order_info->condition == 4): echo 'selected="true"'; endif; ?>>Defekt</option>
        </select>
    
    </div>
    
    <div class="col-md-12">
    
        <label>Pris</label>
        <input type="text" class="form-control" name="price" required name="password" value="<?=$order_info->price;?>"  required style="margin-bottom: 10px" />
    
    </div>
    
    <div class="col-md-12"><hr /></div>
    
    <div class="col-md-12">
	        
    	<input type="checkbox" class="exchangePrice" name="exchange" <?php if($order_info->exchange == 1): echo 'checked="true"'; endif; ?> value="1" />
        <label>Byttepris</label>
    
    </div>
    
    <div class="col-md-12">
    <label>Kontoinformationer</label>
    </div>
    
    <div class="col-md-6">
    	<input type="text" class="form-control" name="reg_nr" required name="password" placeholder="Reg. nr" value="<?php if(!$order_info->reg_nr): echo ''; else: echo $order_info->reg_nr; endif; ?>"  required style="margin-bottom: 10px" />
    </div>
    <div class="col-md-6">
    	<input type="text" class="form-control" name="account_nr" required name="password" placeholder="Konto nr" value="<?php if(!$order_info->account_nr): echo ''; else: echo $order_info->account_nr; endif; ?>"  required style="margin-bottom: 10px" />
    </div>
    
    <div class="col-md-12">
    	<input type="hidden" name="id" value="<?=$order_info->id;?>" />
    	<input type="submit" class="btn btn-success" name="edit_device" value="Rediger" style="margin-top: 20px" />
    </div>

</div>
<?=form_close();?>