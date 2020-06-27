<?php
if ($this->input->get('open_receipt')):
    ?>
    <script type="text/javascript">
        var win = window.open('<?= site_url(); ?>export/print_/<?= $this->input->get('open_receipt'); ?>', '_blank');
            win.focus();

            top.location.href = '<?= site_url('access'); ?>';
    </script>
    <?php
endif;
?>


<div class="modal fade" id="buy_device">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Sælg tilbehør</h4>
            </div>
            <div class="modal-body">
                <?= form_open(current_url()); ?>
                <div class="row sell_unit">

                    <?php
                    if ($access_extra) {
                        ?>
                        <div class="col-md-12">
                            <div class="alert alert-info">Du er ved at oprette ekstra tilbehør til ordre #<?= $access_extra[0]->id; ?>. <a href="<?= site_url('access'); ?>">Klik her hvis dette er en fejl</a></div>
                        </div>
                        <?php
                    }
                    ?>
                    <div id="access_items">
                        <div class="access_item col-md-12" style="margin-bottom:15px;">
                            <div class="col-md-4">
                                <label>Tilbehør tilhører telefon</label>
                                <select class="form-control selectpicker select_devices" name="model[]" required style="margin-bottom: 10px;  width: 180px;">
                                    <option value="">-</option>
                                    <?php
                                    foreach ($products as $product):
                                        ?>
                                        <option value="<?= $product->id; ?>"><?= $product->name; ?></option>
                                        <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Tilbehør</label>
                                <select class="form-control checkIfNewAccess2 selectpicker select_gbs" name="access[]" disabled required style="margin-bottom: 10px; width: 180px;">
                                    <option value="">-</option>

                                </select>
                                <div class="newAccess hidden">
                                  <label>Tilbehør navn</label>
                                  <input type="text" name="newAccessName[]" style="width: 180px;height: 28px" value="" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3 pris_div">
                                <label>Pris</label>
                                <input class="form-control item_pris" value="0" type="number" required name="item_pris[]" style=" height: 28px;" />
                            </div>
                            <div class="col-md-1 item_del_div">


                            </div>
                        </div>
                    </div>


                    <div class="col-md-12" style="margin-top:20px;">
                      <button class="btn btn-info pull-right btn-xs" style='margin-top:-10px;' id="access_prod_add">+Add Product</button>
                      <hr />
                    </div>


                    <div class="col-md-6">

                        <label>
                            Betalingsmetode
                        </label>
                        <?php
                        if (strpos($rank_permissions, 'hidden_btn') !== false || strpos($rank_permissions, 'all') !== false) {
                            ?>
                            <div style="float: right; display: none;" class="hidden_checkboks"><input type="checkbox"  name="hidden" value="1" /></div>
                            <?php
                        }
                        ?>
                        <select class="form-control" name="payment_type" required style="margin-bottom: 10px">
                            <option value="">-</option>
                            <option value="cash">Kontant</option>
                            <option value="webshop">Webshop</option>
                            <option value="card">Kort</option>
                            <option value="mobilepay">Mobilepay</option>
                            <option value="invoice">Faktura</option>
                        </select>

                    </div>
                    <div class="col-md-6">
                      <label>Garanti</label>
                      <select class="form-control" name="garanti" required style="margin-bottom: 10px">
                          <option value="1">Standard</option>
                          <option value="2">Vandskade</option>
                      </select>
                    </div>
                    <div class="name_number" style="display: none">
                        <div class="col-md-6">
                            <label>Navn</label>
                            <input type="text"  class="form-control" name="buyer_name" style="margin-bottom: 10px" />
                        </div>
                        <div class="col-md-6">
                            <label>Tlf. nummer</label>
                            <input type="text"  class="form-control" name="number" style="margin-bottom: 10px" />
                        </div>
                    </div>

                    <div class="order_id_webshop" style="display: none">
                        <div class="col-md-6">
                            <label>Ordre ID fra webshop</label>
                            <input type="text" class="form-control" name="order_id" />
                        </div>
                    </div>
                    <div class="col-md-12"><hr style="margin-bottom:0px;" /></div>
                    <div class="col-md-6"></div>
                    <div class="col-md-3">

                        <div style=" margin-top: 20px;">Total Pris</div>
                        <div class="total_pris" style="font-weight:bold; font-size:18px;">
                          0 kr
                        </div>

                    </div>

                    <div class="col-md-3">
                        <input type="hidden" name="bought_order_id" value="" />
                        <input type="hidden" name="extra_access_to_order_id" value="<?= $this->uri->segment(3); ?>" />
                        <input type="submit" class="btn btn-success col-md-12 pull-right" name="sold_access" value="Sælg" style="margin-top: 25px" />

                      <!--  <div class="pull-right" style="margin-top: 25px;">
                            <input type="checkbox" name="extra_access" value="1" style="padding-right: 20px" /> Tilføj ekstra tilbehør
                        </div>
                      -->
                    </div>


                </div>
                <?= form_close(); ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="edit_access">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Rediger solgt tilbehør</h4>
            </div>
            <div class="modal-body">

                <div class="loader">
                    <center><img src="<?= base_url(); ?>assets/images/loader.gif" /></center>
                </div>

                <div class="editContent" style="display: none">

                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="reason_for_creditnote">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Krediter salg</h4>
            </div>
            <div class="modal-body">

                <?= form_open('access/cancel'); ?>

                <label>Skriv et par linjer omkring hvorfor salget er blevet annulleret</label><br />
                <textarea class="form-control" name="reason" rows="5" required="true"></textarea>

                <input type="submit" class="btn btn-danger" style="margin-top: 10px;" value="Krediter salg" />

                <input type="hidden" name="line_id" class="cancelCreditLineID" value="" />

                <?= form_close(); ?>

                <div class="editContentSold" style="display: none">

                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<h1 class="page-header">
    Solgt tilbehør
    <div class="pull-right">
        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#buy_device">Sælg tilbehør</a>
    </div>
</h1>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Tilbehør</th>
                <th>Tidspunkt</th>
                <th>Køber</th>
                <th>Betalingsform</th>
                <th>Pris</th>
                <th>Fortjeneste</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $this->db->order_by('id', 'desc');
            $this->db->where('boutique_id', $this->session->userdata('active_boutique'));
            $this->db->where('type', 'access');
            $this->db->where('cancelled', 0);
            $this->db->where('hidden', 0);
          //  $this->db->limit(10); //testing
            $orders = $this->db->get('orders')->result();

            foreach ($orders as $order):
                ?>
                <tr>
                    <td><?= $order->id; ?></td>
                    <td>
                        <?php
                        /*if($order->part){
                          echo $order->part;
                        }else{
                          echo $this->device_model->get_item_names_concat($order->id);
                        }
                        */
                        echo $this->device_model->get_item_names_concat($order->id);
                        ?>
                        <?php //echo $order->part; ?>
                        <?php
                        if ($order->extra_access_to_order_id) {
                            ?>
                            <br />
                            <small>Koblet sammen med ordre #<?= $order->extra_access_to_order_id; ?></small>
                            <?php
                        }
                        ?>
                    </td>
                    <td><?= date("d/m/Y H:i", $order->created_timestamp); ?></td>
                    <td>
                        <?= $order->name; ?><br />
                        <?= $order->address; ?>
                    </td>
                    <td>
                        <?php
                        echo $this->global_model->payment_type($order->payment_type);
                        ?>
                    </td>
                    <td><?= $order->price; ?></td>
                    <td>
                        <?php
                        $profit = number_format($this->global_model->calculate_earnings_on_phone($order->id), 2, ',', '.');
                        if ($profit > 0) {
                            echo '<span style="color: green">+' . $profit . ' kr</span>';
                        } else {
                            echo '<span style="color: red">' . $profit . ' kr</span>';
                        }
                        ?>
                    </td>
                    <td width="280px">
                        <a href="#" data-toggle="modal" data-id="<?= $order->id; ?>" data-target="#edit_access" class="btn btn-info btn-xs">Rediger</a>
                        <a href="#" data-toggle="modal" data-id="<?= $order->id; ?>" data-target="#reason_for_creditnote"  class="btn btn-default btn-xs">Krediter</a>
                        <a href="<?= site_url('export/print_/' . $order->id); ?>" class="btn btn-default btn-xs">Kvittering</a>
                    </td>
                </tr>
                <?php
            endforeach;
            ?>
        </tbody>
    </table>
</div>


<script>

$(document).on("change",".select_devices",function(e, access_id){

    var select_gbs = $(this).parents(".access_item").find(".select_gbs");
    var product_id = $(this).val();
    if(product_id){
      $.ajax({
        url: "<?php echo base_url('access/select_gbs'); ?>",
        type: "post",
        data: {product_id: product_id, access_id: access_id},
        success: function(result){
          select_gbs.html(result);
          select_gbs.removeAttr('disabled');
        }
      });
    }else{
      select_gbs.attr('disabled','disabled');
    }

});

$(document).on("change",'.checkIfNewAccess2',function(){

  var newAccess = $(this).parents(".access_item").find(".newAccess");
  if($(this).val() == 'new_access'){
    newAccess.removeClass('hidden');
  }else{
    newAccess.addClass('hidden');
  }
});

function calc_total_price(){
  var totalPrice = 0;
  $('.item_pris').each(function(i,n){
      totalPrice += $(n).val()?parseFloat($(n).val()):0;
    });

    $(".total_pris").html(totalPrice+" kr");
}

$(document).on("click",".item_row_delete",function(e){
  e.preventDefault();
  $(this).parents(".access_item").remove();
  calc_total_price();
});


calc_total_price();
$(document).on("keyup",".item_pris",function(e){
  calc_total_price();
});
$(document).on("change",".item_pris",function(e){
  calc_total_price();
});

  $("#access_prod_add").on("click",function(e){
    e.preventDefault();
    var new_item = $(".access_item:first").clone();
    $("#access_items").append(new_item);
    $(new_item).find(".item_del_div").html('<button class="btn btn-danger btn-xs item_row_delete" style="margin-top:25px;">X</button>');
     $(new_item).find(".select2-container").remove();
     var select2 = $(new_item).find("select.selectpicker");
     $(new_item).find(".newAccess").addClass('hidden');
     $(new_item).find('.item_pris').val('0');
     calc_total_price();
     $(new_item).find("select.select_gbs").attr('disabled','disabled');
     select2.select2();
  });

  $("#buy_device").on('hidden.bs.modal', function () {
    $(this).data('bs.modal', null);
    $(this).find(".modal-body form")[0].reset();
    calc_total_price();
  });
  $("#edit_access").on('hidden.bs.modal', function () {
    $(this).data('bs.modal', null);
    $(this).find(".editContent").html("");
    calc_total_price();
  });
</script>
