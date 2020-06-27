<h1 class="page-header">
	Kundekartotek

	<div class="pull-right">
			<a href="#" id="add_customer_btn" class="btn btn-success">Opret</a>
		<a href="<?php echo base_url('customer/export_csv'); ?>" class="btn btn-info">csv eksport</a>
	</div>

</h1>

<?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
<div class="">
<table class="table table-striped datatable">
  <thead>
    <tr>
      <th>Navn</th>
			<th>Type</th>
      <th>Email</th>
	  <th>Tlf nummer</th>
		 <th>Discount</th>
		 <th>Action</th>
    </tr>
  </thead>
  <tbody>
  	<?php
  	foreach($customers as $row):
  	?>
    <tr>
      <td><?=$row['name'];?>
				<br />
				<small><a href='<?php echo base_url('receipt?action=filter_by_customer&id='.$row['id']); ?>' target="_blank">Indleveringskvitteringer</a> | <a href='<?php echo base_url('access?action=filter_by_customer&id='.$row['id']); ?>' target="_blank">Tilbehør</a></small>
			</td>
			<td><?php if($row['type'] == 'private'){echo "Privat";}else{ echo "Virksomhed";} ?></td>
	  <td><?=$row['email'];?></td>
	  <td><?=$row['phone'];?></td>
		<td><?=$row['discount'];?> %</td>
		<td>
			<a href="#" data-json='<?php echo json_encode($row); ?>' data-id="<?php echo $row['id']; ?>" class="btn btn-sm btn-success customer_edit">Rediger</a>
			<a href="<?php echo base_url('customer/delete_customer/'.$row['id']); ?>" class="btn btn-sm btn-danger confirm delete customer_delete">Slet</a>
		</td>
    </tr>
    <?php
    endforeach;
    ?>
  </tbody>
</table>
</div>

<div class="modal fade" id="customer_edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Rediger</h4>
      </div>
      <div class="modal-body">
				<form class="form-signin" id="customer_edit_form" action="<?=base_url('customer/update');?>" method="POST">

	        <div class="row">

	        <div class="col-md-6" style="margin-bottom: 10px;">
	        	<label>Navn</label>
	        	<input type="text" class="form-control" required name="name" placeholder="Navn">
	        </div>


					<div class="col-md-6" style="margin-bottom: 10px;">
	        	<label>Email</label>
	        	<input type="email" class="form-control" required name="email" placeholder="Email">
	        </div>

	        <div class="col-md-6" style="margin-bottom: 10px;">
	        	<label>Tlf kode</label>
	        	<input type="text" class="form-control" name="phone_code" placeholder="Tlf kode">
	        </div>

			<div class="col-md-6" style="margin-bottom: 10px;">
	        	<label>Tlf nummer</label>
	        	<input type="text" class="form-control" name="phone" placeholder="Telefon">
	        </div>

			<div class="col-md-6" style="margin-bottom: 10px;">
	        	<label>Pin</label>
	        	<input type="text" class="form-control" name="pin" placeholder="Pin">
	        </div>
					<div class="col-md-6" style="margin-bottom: 10px;">
			        	<label>Discount (%)</label>
			        	<input type="text" class="form-control" value="0" name="discount" placeholder="Discount">
			        </div>

							<div class="col-md-6" style="margin-bottom: 10px;">
								<label>Type</label>
								<select class="form-control" name="type">
									<option value="private" selected>Privat</option>
									<option value="company">Virksomhed</option>
								</select>
							</div>
							<div class="col-md-6 company_only" style="margin-bottom: 10px;">
								<label>CVR</label>
								<input type="text" class="form-control" name="cvr" placeholder="CVR">
							</div>

							<div class="col-md-6 company_only" style="margin-bottom: 10px;">
								<label>EAN</label>
								<input type="number" class="form-control" name="ean" placeholder="EAN">
							</div>

							<div class="col-md-6 company_only" style="margin-bottom: 10px;">
								<label>Reference</label>
								<input type="text" class="form-control" name="reference" placeholder="Reference">
							</div>

							<div class="col-md-6 company_only" style="margin-bottom: 10px;">
								<label>Kontaktperson</label>
								<input type="text" class="form-control" name="contact_person" placeholder="Kontaktperson">
							</div>

							<div class="col-md-6 company_only" style="margin-bottom: 10px;">
								<label>Adresse</label>
								<input type="text" class="form-control" name="address" placeholder="Adresse">
							</div>

							<div class="col-md-12">
								<input type='hidden' name='id' value='0' />
								<input type="submit" class="btn btn-success" name="create" value="Opret" />
							</div>
				</div>
			</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="customer_add">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Opret</h4>
      </div>
      <div class="modal-body">
				<form class="form-signin" id="customer_add_form" action="<?=base_url('customer/save');?>" method="POST">

	        <div class="row">

	        <div class="col-md-6" style="margin-bottom: 10px;">
	        	<label>Navn</label>
	        	<input type="text" class="form-control" required name="name" placeholder="Navn">
	        </div>


					<div class="col-md-6" style="margin-bottom: 10px;">
	        	<label>Email</label>
	        	<input type="email" class="form-control" required name="email" placeholder="Email">
	        </div>

	        <div class="col-md-6" style="margin-bottom: 10px;">
	        	<label>Tlf kode</label>
	        	<input type="text" class="form-control" name="phone_code" placeholder="Tlf kode">
	        </div>

			<div class="col-md-6" style="margin-bottom: 10px;">
	        	<label>Tlf nummer</label>
	        	<input type="text" class="form-control" name="phone" placeholder="Telefon">
	        </div>

			<div class="col-md-6" style="margin-bottom: 10px;">
	        	<label>Pin</label>
	        	<input type="text" class="form-control" name="pin" placeholder="Pin">
	        </div>
					<div class="col-md-6" style="margin-bottom: 10px;">
			        	<label>Discount (%)</label>
			        	<input type="text" class="form-control" value="0" name="discount" placeholder="Discount">
			        </div>
							<div class="col-md-6" style="margin-bottom: 10px;">
								<label>Type</label>
								<select class="form-control" name="type">
									<option value="private" selected>Privat</option>
									<option value="company">Virksomhed</option>
								</select>
							</div>
							<div class="col-md-6 company_only" style="margin-bottom: 10px;">
			        	<label>CVR</label>
			        	<input type="text" class="form-control" name="cvr" placeholder="CVR">
			        </div>

					<div class="col-md-6 company_only" style="margin-bottom: 10px;">
								<label>EAN</label>
								<input type="number" class="form-control" name="ean" placeholder="EAN">
							</div>

							<div class="col-md-6 company_only" style="margin-bottom: 10px;">
								<label>Reference</label>
								<input type="text" class="form-control" name="reference" placeholder="Reference">
							</div>

							<div class="col-md-6 company_only" style="margin-bottom: 10px;">
								<label>Kontaktperson</label>
								<input type="text" class="form-control" name="contact_person" placeholder="Kontaktperson">
							</div>

							<div class="col-md-6 company_only" style="margin-bottom: 10px;">
								<label>Adresse</label>
								<input type="text" class="form-control" name="address" placeholder="Adresse">
							</div>
							<div class="col-md-12">
								<input type="submit" class="btn btn-success" name="create" value="Opret" />
							</div>
				</div>
			</form>
			</div>
		</div>
	</div>
</div>

<script>
$(".datatable").dataTable();
	$(document).on("click",".customer_edit",function(e){
		e.preventDefault();
		var data = $(this).data('json');
		if(data.id){
			$("#customer_edit_form").find("input[name='name']").val(data.name);
			$("#customer_edit_form").find("input[name='email']").val(data.email);
			$("#customer_edit_form").find("input[name='phone_code']").val(data.phone_code);
			$("#customer_edit_form").find("input[name='phone']").val(data.phone);
			$("#customer_edit_form").find("input[name='pin']").val(data.pin);
			$("#customer_edit_form").find("input[name='discount']").val(data.discount);
			$("#customer_edit_form").find("input[name='id']").val(data.id);
			$("#customer_edit_form").find("select[name='type']").val(data.type);
			if(data.type == 'company'){
				$("#customer_edit_form").find(".company_only").show();
				$("#customer_edit_form").find("input[name='cvr']").val(data.cvr).prop('required',true);
				$("#customer_edit_form").find("input[name='ean']").val(data.ean).prop('required',true);
				$("#customer_edit_form").find("input[name='reference']").val(data.reference).prop('required',true);
				$("#customer_edit_form").find("input[name='contact_person']").val(data.contact_person).prop('required',true);
				$("#customer_edit_form").find("input[name='address']").val(data.address).prop('required',true);
			}else{
				$("#customer_edit_form").find(".company_only").hide();
				$("#customer_edit_form").find("input[name='cvr']").val('').removeAttr('required');
				$("#customer_edit_form").find("input[name='ean']").val('').removeAttr('required');
				$("#customer_edit_form").find("input[name='reference']").val('').removeAttr('required');
				$("#customer_edit_form").find("input[name='contact_person']").val('').removeAttr('required');
				$("#customer_edit_form").find("input[name='address']").val('').removeAttr('required');
			}

			$("#customer_edit").modal('show');
		}

	});

	$(document).on("click","#add_customer_btn",function(e){
		e.preventDefault();
		$("#customer_add").modal('show');
	});

	$(".company_only").hide();
	$("#customer_add").find("select[name='type']").on("change",function(){
		var type = $(this).val();

		if(type == 'company'){
			$("#customer_add").find(".company_only").show();
			$("#customer_add_form").find("input[name='cvr']").prop('required',true);
			$("#customer_add_form").find("input[name='ean']").prop('required',true);
			$("#customer_add_form").find("input[name='reference']").prop('required',true);
			$("#customer_add_form").find("input[name='contact_person']").prop('required',true);
			$("#customer_add_form").find("input[name='address']").prop('required',true);
		}else{
			$("#customer_add").find(".company_only").hide();
			$("#customer_add_form").find("input[name='cvr']").removeAttr('required');
			$("#customer_add_form").find("input[name='ean']").removeAttr('required');
			$("#customer_add_form").find("input[name='reference']").removeAttr('required');
			$("#customer_add_form").find("input[name='contact_person']").removeAttr('required');
			$("#customer_add_form").find("input[name='address']").removeAttr('required');
		}
	});

	$("#customer_edit_form").find("select[name='type']").on("change",function(){
		var type = $(this).val();

		if(type == 'company'){
			$("#customer_edit_form").find(".company_only").show();
		}else{
			$("#customer_edit_form").find(".company_only").hide();
		}
	});

	$(document).on("submit","#customer_add_form",function(e){

		var type = $("#customer_add").find("select[name='type']").val();
		if(type == 'company'){
			if($("#customer_add_form").find("input[name='ean']").val().length != 13){
				alert('EAN should be 13 digit');
				return false;
			}
		}

		return true;
	});

	$(document).on("submit","#customer_edit_form",function(e){

		var type = $("#customer_edit_form").find("select[name='type']").val();
		if(type == 'company'){
			if($("#customer_edit_form").find("input[name='ean']").val().length != 13){
				alert('EAN should be 13 digit');
				return false;
			}
		}

		return true;
	});
</script>
