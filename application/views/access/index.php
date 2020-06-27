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
                        <div class="access_item col-md-12 form-inline" style="margin-bottom:15px;">
                            <div class="form-group">
                                <label>Tilbehør tilhører telefon</label><br />
                                <select class="form-control selectpicker select_devices" name="model[]" required style="margin-bottom: 10px;  width: 180px;">
                                    <option value="">-</option>
                                    <option value="TELEFON">TELEFON</option>
                                    <?php
                                    foreach ($products as $product):
                                        ?>
                                        <option value="<?= $product->id; ?>"><?= $product->name; ?></option>
                                        <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tilbehør</label><br />
                                <select class="form-control checkIfNewAccess2 selectpicker select_gbs" name="access[]" disabled required style="margin-bottom: 10px; width: 150px;">
                                    <option value="">-</option>

                                </select>

                            </div>
                            <div class="form-group">
                                <label>Qty</label><br />
                                <select class="form-control" name="qty[]" style="height:28px; width: 60px;">
                                  <?php for($i=1;$i<=10;$i++){ ?>
                                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                  <?php } ?>
                                </select>
                            </div>
                            <div class="form-group pris_div">
                                <label>Pris</label><br />
                                <input class="form-control item_pris" value="0" type="number" required name="item_pris[]" style=" height: 28px; width:60px;" />
                            </div>

                            <div class="form-group discount_div">
                                <label>Discount</label><br />
                                <input class="form-control item_discount" value="0" type="number" required name="item_discount[]" style=" height: 28px; width:60px;" />
                            </div>
                            <div class="form-group item_del_div">


                            </div>
                            <div class="newAccess hidden form-group" style='padding-left: 185px; padding-top:10px;'>

                              <input type="text" placeholder="Tilbehør navn" name="newAccessName[]" style="width: 150px;height: 28px;" value="" class="form-control" />
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12" style="margin-top:20px;">
                      <button class="btn btn-info pull-right btn-xs" style='margin-top:-10px;' id="access_prod_add">+Add Product</button>
                      <hr />
                    </div>

                    <div class="col-md-12">
                        <label><input type="checkbox" class="show_name" value="1" name="show_name" /> Navn på kvittering?</label>

                    </div>
                    <div style="display:none" class="name_checked">
                      <div class="col-md-6">
                          <label>Navn</label>
                          <input type="text"  class="form-control" name="buyer_name" style="margin-bottom: 10px" />
                      </div>
                      <div class="col-md-6">
                          <label>Phone</label>
                          <input type="text"  class="form-control" id="buyer_phone" name="number" style="margin-bottom: 10px" />
                      </div>
                      <div class="col-md-4">
                          <label>E-mail</label> <input type="checkbox" value="1" name="send_email" />
                          <input type="text"  class="form-control" id="buyer_email" name="email" style="margin-bottom: 10px" />
                      </div>
                      <div class="col-md-4">
                          <label>Evt. firma navn</label>
                          <input type="text"  class="form-control" name="company_name" style="margin-bottom: 10px" />
                      </div>
                      <div class="col-md-4">
                          <label>CVR nummer</label>
                          <input type="text"  class="form-control" name="cvr" style="margin-bottom: 10px" />
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr />
                    </div>
                    <div id="payment_method_div" class='row'>
                      <div class="payment_method col-md-12">
                        <div class="col-md-6">

                            <label>
                                Betalingsmetode
                            </label>
                            <?php
                              if (strpos($rank_permissions, 'hidden_btn') !== false || strpos($rank_permissions, 'all') !== false) {
                                  ?>
                                  <div style="float: right; display: none;" class="hidden_checkboks"><input type="checkbox"  name="hidden[]" value="1" /></div>
                                  <?php
                              }
                            ?>
                            <select class="form-control payment_type" name="payment_type[]" required style="margin-bottom: 10px">
                                <option value="">-</option>
                                <?php
                                  $payment = $this->global_model->get_payment();
                                  foreach($payment as $row){
                                ?>
                                <option value="<?php echo $row['name']; ?>"><?php echo $row['label']; ?></option>
                                <?php } ?>
                            </select>

                        </div>

                        <div class="col-md-6">

                            <label>
                                Amount
                            </label>

                            <input type='text' name='payment_amount[]' value='0' required class='form-control payment_amount' />
                        </div>
                      </div>
                  </div>

                    <div class="col-md-12" style="margin-top:20px;">
                      <button class="btn btn-info pull-right btn-xs" style='margin-top:-10px;' id="payment_method_add">+Add Payment Method</button>

                    </div>



                    <div class="order_id_webshop" style="display: none">
                        <div class="col-md-6">
                            <label>Ordre ID fra webshop</label>
                            <input type="text" class="form-control" name="order_id" />
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
                          <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                        <?php } ?>
                      </select>
                      <a href="#" id="show_comment_div">Tilføj kommentar</a>
                    </div>
                    <div class="col-md-3">

                        <div style="margin-top: 20px;">Total Pris</div>
                        <div class="total_pris" style="font-weight:bold; font-size:18px;">
                          0 kr
                        </div>

                    </div>

                    <div class="col-md-3">
                        <input type='hidden' name='subtotal' class='subtotal' value='0' />
                        <input type="hidden" name="bought_order_id" value="" />
                        <input type="hidden" name="extra_access_to_order_id" value="<?= $this->uri->segment(3); ?>" />
                        <input type="submit" class="btn btn-success col-md-12 pull-right" name="sold_access" value="Sælg" style="margin-top: 25px" />

                      <!--  <div class="pull-right" style="margin-top: 25px;">
                            <input type="checkbox" name="extra_access" value="1" style="padding-right: 20px" /> Tilføj ekstra tilbehør
                        </div>
                      -->
                    </div>

                    <div class="col-md-12 hidden" id='new_comment_div'>
                      <hr />
                      <label>Kommentar</label><br />
                      <textarea class="form-control" name="comment" id="comment_new" rows="3"></textarea>
					  <label class="pull-right" style="margin-top:20px;"><input type="checkbox" name="show_pumping_in_receipt" value="1" /> På kvittering</label>
                      <div class="order_comment_image_container" style="width:400px; float: left;">
                      <div id="order_comment_image_upload_new" class="fileuploader" style="float: left">+ Add Image</div>
                      <div id="order_comment_image_upload_response_new"></div>

                      </div>
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

<div class="modal fade" id="order_comment_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tilføj kommentar</h4>
            </div>
            <div class="modal-body">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<h1 class="page-header">
    Rep i Tilbehør
    <div class="pull-right">
        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#buy_device">Sælg tilbehør</a>
    </div>
</h1>

<div class=""> <!--table-responsive-->
    <table class="table table-striped" id="dtable_access">
        <thead>
            <tr>
                <th>#</th>
                <th>Tilbehør</th>
                <th>Tidspunkt</th>
                <th>Køber</th>
                <th>Betalingsform</th>
                <th>Pris</th>
                <th>Fortjeneste</th>
                <th width="350px"></th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<link href="<?= base_url(); ?>assets/plugin/magnific-popup/magnific-popup.css" rel="stylesheet" />
<script src="<?= base_url(); ?>assets/plugin/magnific-popup/jquery.magnific-popup.min.js"></script>


<link href="<?= base_url(); ?>assets/plugin/uploadfile/css/uploadfile.min.css" rel="stylesheet" />
<script src="<?= base_url(); ?>assets/plugin/uploadfile/js/jquery.uploadfile.min.js"></script>

<style>
.ajax-upload-dragdrop{
	border: 0px;
}
.ajax-file-upload-container{
	float: left;
}
.ajax-upload-dragdrop{
	border: 0px !important;
	padding: 0px !important;
	margin-top: 10px;
}
.ajax-file-upload{
	height: 30px !important;
}

.form-control{
  padding: 6px !important;
}
</style>

<script>
var columns_full = [{"db": "id", "dt": 0, "field": "id"},
        {"db": "part", "dt": 1, "field": "part"},
        {"db": "created_timestamp", "dt": 2, "field": "created_timestamp"},
        {"db": "name", "dt": 3, "field": "name"},
        {"db": "payment_type", "dt": 4, "field": "payment_type"},
        {"db": "price", "dt": 5, "field": "price"},
        {"db": "extra_access_to_order_id", "dt": 6, "field": ""},
        {"db": "address", "dt": 7, "field": ""}
    ];

$("#dtable_access").DataTable({
        "ajax": {
            "type": "POST",
            "url": "<?php echo base_url('dataTable/getTable'); ?>",
            "data": {"table": "orders", "primary_key": 'id', "page": "access", "columns": columns_full, "action": "<?php echo $this->input->get('action'); ?>", "customer_id":"<?php echo $this->input->get('id'); ?>"}
        },
        "processing": true,
        "serverSide": true,
        "bStateSave": true,
        "aaSorting": [[0, 'desc']],
        "columnDefs": [
          {
            "targets": 1,
            "render": function (data, type, full, meta) {
                return full['item_name'];
            }
          },
          {
            "targets": 6,
            "render": function (data, type, full, meta) {
              full['profit'] = parseFloat(full['profit']);
              if(full['profit'] > 0){
                return '<span style="color: green">+' + full['profit'] + ' kr</span>';
              }else{
                return '<span style="color: red">' + full['profit'] + ' kr</span>';
              }
            }
          },
          {
            "targets": 7,
            "render": function(data, type, full, meta){
                var comment_icon = "";
                if(full['comment_count'] > 0){
                  comment_icon = " <a href='#' data-id='"+full[0]+"' class='order_comment_modal'><img src='<?php echo base_url('assets/images/chat-icon.png'); ?>' data-id='"+full[0]+"' style='width:25px;' class='comment_icon' /></a>";
                }
                return  '<a href="#" data-toggle="modal" data-id="'+full[0]+'" data-target="#edit_access" class="btn btn-info btn-xs">Rediger</a> ' +
                        '<a href="#" data-toggle="modal" data-id="'+full[0]+'" data-target="#reason_for_creditnote"  class="btn btn-default btn-xs">Krediter</a> '+
                        '<a href="#" data-id="'+full[0]+'" class="btn btn-default btn-xs order_comment_modal">Tilføj kommentar</a> ' +
                        '<a target="_blank" href="<?= site_url('export/print_'); ?>/'+full[0]+'" class="btn btn-default btn-xs">Kvittering</a>' +
                        comment_icon;
            }
          }
        ]
    });

    $(document).on("click",".order_comment_modal",function(e){
      e.preventDefault();

      var id = $(this).data('id');
      $.ajax({
        url: "<?php echo base_url('access/order_comment_modal'); ?>",
        type: "post",
        data: {id: id},
        success: function(result){
          $("#order_comment_modal").find(".modal-body").html(result);
        },
        complete: function(a,b,c){
          $("#order_comment_modal").modal('show');
          //$("img.comment_icon[data-id='"+id+"']").hide();
        }
      });
    });

$(document).on("change",".select_devices",function(e, access_id){

    var select_gbs = $(this).parents(".access_item").find(".select_gbs");
    var product_id = $(this).val();
    if(product_id){
      if(product_id == 'TELEFON'){
        $.ajax({
          url: "<?php echo base_url('access/select_bought_phone_ids'); ?>",
          type: "get",
          data: {},
          success: function(result){
            select_gbs.html(result);
            select_gbs.removeAttr('disabled');
          }
        });

        $(this).parents(".access_item").find("select[name='qty[]']").val(1);
        $(this).parents(".access_item").find("select[name='qty[]']").find("option").not('[value=1]').prop('disabled',true);
      }else{
        $.ajax({
          url: "<?php echo base_url('access/select_gbs'); ?>",
          type: "post",
          data: {product_id: product_id, access_id: access_id},
          success: function(result){
            select_gbs.html(result);
            select_gbs.removeAttr('disabled');
          }
        });
        $(this).parents(".access_item").find("select[name='qty[]']").find("option").prop('disabled',false);
      }

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
  var qty = 0;
  var unit_price = 0;
  var item_total = 0;
  var item_discount = 0;
  var total_discount = 0;
  if($("#discount_edit").length){
    var discount = $("#discount_edit").val();
  }else{
    var discount = $(".discount").val();
  }

  $('.item_pris').each(function(i,n){
      qty = $(n).parents('.access_item').find('select[name="qty[]"]').val();
      qty = parseFloat(qty);
      item_discount = $(n).parents('.access_item').find('.item_discount').val();
      item_discount = parseFloat(item_discount);
      unit_price = $(n).val()?parseFloat($(n).val()):0;


      item_total = (unit_price * qty);

      item_discount = (item_total*item_discount)/100;

      total_discount = total_discount + item_discount;
      totalPrice = totalPrice + (unit_price * qty);
    });

    $(".subtotal").val(totalPrice);
    //totalPrice = totalPrice - (totalPrice * parseFloat(discount) / 100);
    totalPrice = totalPrice - total_discount;
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
$(document).on("keyup",".item_discount",function(e){
  calc_total_price();
});
$(document).on("change",".discount",function(e){
  calc_total_price();
});

$(document).on("change",".item_pris",function(e){
  calc_total_price();
});

$(document).on("keyup",".discount",function(e){
  calc_total_price();
});

$(document).on("change","select[name='qty[]']",function(){
  calc_total_price();
});

  $("#access_prod_add").on("click",function(e){
    e.preventDefault();
    var new_item = $(".access_item:first").clone();
    $("#access_items").append(new_item);
    $(new_item).find(".item_del_div").html('<button class="btn btn-danger btn-xs item_row_delete" style="margin-top:28px;">X</button>');
     $(new_item).find(".select2-container").remove();
     var select2 = $(new_item).find("select.selectpicker");
     $(new_item).find(".newAccess").addClass('hidden');
     $(new_item).find('.item_pris').val('0');
     $(new_item).find('.item_discount').val('0');
     calc_total_price();
     $(new_item).find("select.select_gbs").attr('disabled','disabled');
     select2.select2();
  });

  $("#payment_method_add").on("click",function(e){
    e.preventDefault();
    var new_item = $(".payment_method:first").clone();
    $("#payment_method_div").append(new_item);
    $(new_item).find(".item_del_div").html('<button class="btn btn-danger btn-xs item_row_delete" style="margin-top:48px;">X</button>');
     $(new_item).find(".select2-container").remove();
     var select2 = $(new_item).find("select.selectpicker");
     $(new_item).find('.payment_amount').val('0');
     $(new_item).find('.hidden_checkboks').css('display','none');
     calc_total_price();
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


  $(document).on("blur","#buyer_phone,#buyer_phone_edit",function(e){
      var phone = $(this).val();
      var form = $(this).parents("form");
      if(phone.length > 5){
        $.ajax({
          url: "<?php echo base_url('customer/get_json_by_phone'); ?>",
          type: "GET",
          dataType: "json",
          data: {phone: phone},
          success: function(data){
            if(data.id){
              form.find("input[name='buyer_name']").val(data.name);
        			form.find("input[name='email']").val(data.email);
              form.find("input[name='discount']").val(data.discount);
              calc_total_price();
            }
          }
        });
      }
  });

  $(document).on("change",".show_name",function(){

    if($(this).is(':checked')){

      $(".name_checked").css("display","block");
    }else{
      $(".name_checked").css("display","none");
    }
  });

  $(document).on("click","#show_comment_div",function(e){
    e.preventDefault();
    $("#new_comment_div").toggleClass('hidden');
  });

  $("#order_comment_image_upload_new").uploadFile({
  	url: "<?php echo base_url('access/comment_image_temp_upload'); ?>",
  	fileName: "order_comment_image",
  	multiple: false,
  	maxFileSize: 15 * 1024 * 1024,
  	showStatusAfterSuccess: false,
  	allowedTypes: "jpg,jpeg,png,gif",
  	onSuccess: function (files, data, xhr) {
  		var data = JSON.parse(data);

  		if (data.error) {
  			alert(data.error);
  		} else if (data) {
  			var file = data.upload_data.file_name;
  			var content = "<strong>File Attached</strong> <a data-file='"+file+"' class='order_comment_image_remove_new' href='#'>[remove]</a><input type='hidden' name='image' value='"+file+"' />";
  			$("#order_comment_image_upload_response_new").html(content);
  		}
  	},
  	uploadStr: "+ Add Image"

  });

  $(document).on("click",".order_comment_image_remove_new",function(e){
  	e.preventDefault();
  	var file = $(this).data('file');
  	  $.ajax({
  		url: "<?php echo base_url('access/comment_image_temp_remove'); ?>",
  		type: "post",
  		data: {file: file},
  		success: function(result){

  		},
  		complete: function(){
  		  $("#order_comment_image_upload_response_new").html("");
  		}
  	  });

  });
</script>
