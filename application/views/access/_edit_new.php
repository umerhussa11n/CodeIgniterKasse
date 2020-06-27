<?=form_open(current_url());?>
<div class="row sell_unit">
  <div id="access_items">
    <?php
    if($items){

    foreach($items as $item){ ?>
      <div class="access_item col-md-12 form-inline" style="margin-bottom:15px;" id="item_id_<?php echo $item['item_id'];?>">
          <div class="form-group">
              <label>Tilbehør tilhører telefon</label><br/>
              <select class="form-control selectpicker select_devices" name="model[]" required style="margin-bottom: 10px;  width: 180px;">
                  <option value="">-</option>
                  <?php
                  foreach ($products as $product):
                      ?>
                      <option value="<?= $product->id; ?>" <?php if($product->id == $item['product_id']){?>selected<?php }?>><?= $product->name; ?></option>
                      <?php
                  endforeach;
                  ?>
              </select>
          </div>

          <div class="form-group">
              <label>Tilbehør</label><br/>
              <select class="form-control checkIfNewAccess2 selectpicker select_gbs" name="access[]" disabled <?php if(!$item['bought_from_order_id']){ ?>required<?php } ?> style="margin-bottom: 10px; width: 150px;">
                  <option value="">-</option>

              </select>

          </div>
          <div class="form-group">
              <label>Qty</label><br/>
              <select class="form-control" name="qty[]" style="height:28px; width: 60px;">
                <?php if($item['bought_from_order_id']){
                  ?>
                  <option value="1" selected>1</option>
                  <?php
                }else{
                 for($i=1;$i<=10;$i++){ ?>
                  <option value="<?php echo $i;?>" <?php if($item['qty'] == $i){ echo "selected"; } ?>><?php echo $i;?></option>
                <?php }
              }
                 ?>
              </select>
          </div>
          <div class="form-group pris_div">
              <label>Pris</label><br/>
              <input class="form-control item_pris" value="<?php echo $item['price'];?>" type="number" required name="item_pris[]" style=" height: 28px; width: 60px;" />
          </div>

          <div class="form-group discount_div">
              <label>Discount</label><br/>
              <input class="form-control item_discount" value="<?php echo (int)$item['discount'];?>" type="number" required name="item_discount[]" style=" height: 28px; width: 60px;" />
          </div>

          <div class="form-group item_del_div">


          </div>
          <div class="newAccess hidden form-group" style='padding-left: 185px; padding-top:10px;'>

            <input type="text" placeholder="Tilbehør navn" name="newAccessName[]" style="width: 150px;height: 28px" value="" class="form-control" />
          </div>
          <input type='hidden' name='item_id[]' value='<?php echo $item['item_id'];?>' />
      </div>
      <script>
        $("#item_id_<?php echo $item['item_id'];?>").find(".select_devices").trigger('change',[<?php echo $item['part_id'];?>]);
      </script>
    <?php }
  }
     ?>
  </div>

    <div class="col-md-12"><hr /></div>
    <div class="col-md-12">
        <label><input type="checkbox" class="show_name" value="1" name="show_name" <?php if($order->show_name){ echo "checked"; } ?> /> Navn på kvittering?</label>

    </div>
    <div style="<?php if(!$order->show_name){ ?>display:none<?php } ?>" class="name_checked">
      <div class="col-md-6">
          <label>Navn</label>
          <input type="text" value="<?php echo $order->name; ?>" class="form-control" name="buyer_name" style="margin-bottom: 10px" />
      </div>
      <div class="col-md-6">
          <label>Phone</label>
          <input type="text"  class="form-control" id="buyer_phone_edit" value="<?php echo $order->number; ?>" name="number" style="margin-bottom: 10px" />
      </div>
      <div class="col-md-4">
          <label>E-mail</label> <input type="checkbox" value="1" name="send_email" />
          <input type="text" value="<?php echo $order->email; ?>" class="form-control" name="email" style="margin-bottom: 10px" />
      </div>
      <div class="col-md-4">
          <label>Evt. firma navn</label>
          <input type="text" value="<?php echo $order->company; ?>" class="form-control" name="company_name" style="margin-bottom: 10px" />
      </div>
      <div class="col-md-4">
          <label>CVR nummer</label>
          <input type="text" value="<?php echo $order->cvr; ?>" class="form-control" name="cvr" style="margin-bottom: 10px" />
      </div>
    </div>
    <div class="col-md-12">
      <hr />
    </div>


    <div id="payment_method_div_edit row">
      <?php if($payment_types->num_rows()){
        foreach($payment_types->result_array() as $payment_type){
      ?>
      <div class="payment_method col-md-12">
        <div class="col-md-6">

            <label>
                Betalingsmetode
            </label>

            <select class="form-control payment_type" name="payment_type[]" required style="margin-bottom: 10px">
                <option value="">-</option>
                <?php
                  $payment = $this->global_model->get_payment();
                  foreach($payment as $row){
                ?>
                  <option value="<?php echo $row['name']; ?>" <?php if($payment_type['payment_method'] == $row['name']){ ?> selected <?php } ?>><?php echo $row['label']; ?></option>
                <?php } ?>
            </select>

        </div>

        <div class="col-md-6">

            <label>
                Amount
            </label>

            <input type='text' name='payment_amount[]' value='<?php echo $payment_type['amount']; ?>' required class='form-control payment_amount' />
        </div>
      </div>
    <?php }
      }
     ?>
  </div>





    <div class="order_id_webshop" style="display: <?php if($order->payment_type == 'webshop'): echo 'block'; else: echo 'none'; endif; ?>">
        <div class="col-md-6">
	        <label>Ordre ID fra webshop</label>
	        <input type="text" class="form-control" name="order_id" value="<?=$order->webshop_id;?>" style="margin-bottom: 10px" />
        </div>
    </div>
    <div class="col-md-12"><hr style="margin-bottom:0px;" /></div>
    <div class="col-md-6">
      <label>Garanti</label>
      <select class="form-control" name="garanti" required style="margin-bottom: 10px">
        <?php
          $garanti = $this->global_model->get_garanti();
          foreach($garanti as $row){
        ?>
        <option value="<?php echo $row['id']; ?>" <?php if($order->garanti == $row['id']): echo 'selected="true"'; endif; ?>><?php echo $row['name']; ?></option>
      <?php } ?>
      </select>
    </div>
    <div class="col-md-3">
      <div style=" margin-top: 20px;">Total Pris</div>
      <div class="total_pris" style="font-weight:bold; font-size:18px;">
        <?=$order->price;?> kr
      </div>

    </div>

    <div class="col-md-3">
      <input type='hidden' name='subtotal' class='subtotal' id='subtotal_edit' value='<?=$order->subtotal;?>' />
    	<input type="hidden" name="id" value="<?=$order->id;?>" />
    	<input type="submit" class="btn btn-success  col-md-12 pull-right" name="edit_access" value="Sælg" style="margin-top: 25px" />
    </div>

	<?php if($order->comment){ ?>
   <div class="col-md-12">
	  <hr />
	  <label>Kommentar</label><br />
	  <textarea class="form-control" name="comment" rows="3"><?php echo $order->comment; ?></textarea>
   </div>
	<?php } ?>

</div>
<?=form_close();?>


<script>
$('.selectpicker').select2();

payment_type();
</script>
