<h1 class="page-header">
	Opret reklamation
</h1>

<div class="row">
<div class="col-md-7">
<div class="table-responsive">
<?=form_open(current_url());?>
<table class="table table-striped">
  <tbody>
  	<tr>
  		<td style="padding-top: 15px" width="200px">Navn</td>
  		<td><input type="text" name="name" class="form-control" /></td>
  	</tr>
  	<tr>
  		<td style="padding-top: 15px" width="200px">Tlf. nummer</td>
  		<td><input type="text" name="number" class="form-control" /></td>
  	</tr>
  	<tr>
  		<td style="padding-top: 15px" width="200px">E-mail</td>
  		<td><input type="text" name="email" class="form-control" /></td>
  	</tr>
  	<tr>
  		<td style="padding-top: 15px" width="200px">Ordre ID</td>
  		<td><input type="text" name="order_id" class="form-control" /></td>
  	</tr>
  	<tr>
  		<td style="padding-top: 15px" width="200px">IMEI nummer</td>
  		<td><input type="text" name="imei" class="form-control" /></td>
  	</tr>
  	<tr>
  		<td style="padding-top: 15px" width="200px">Model</td>
  		<td><input type="text" name="model" class="form-control" /></td>
  	</tr>
  	<tr>
  		<td style="padding-top: 15px" width="200px">Farve</td>
  		<td><input type="text" name="color" class="form-control" /></td>
  	</tr>
  	<tr>
  		<td style="padding-top: 15px" width="200px">Postnummer</td>
  		<td><input type="text" name="zipcode" class="form-control" /></td>
  	</tr>
  	<tr>
  		<td style="padding-top: 15px" width="200px">By</td>
  		<td><input type="text" name="city" class="form-control" /></td>
  	</tr>
  	<tr>
  		<td style="padding-top: 15px" width="200px">Adresse</td>
  		<td><input type="text" name="address" class="form-control" /></td>
  	</tr>
  	<tr>
  		<td style="padding-top: 15px" width="200px">Grundig fejlbeskrivelse</td>
  		<td><textarea class="form-control" name="description" rows="6"></textarea></td>
  	</tr>
  	<tr>
  		<td style="padding-top: 15px" width="200px"></td>
  		<td><input type="submit" class="btn btn-success" name="create" value="Opret reklamation" /></td>
  	</tr>
  </tbody>
</table>
<?=form_close();?>
</div>
</div>

</div>

<input type="hidden" class="disableChained" value="1" />