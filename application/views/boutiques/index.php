<div class="modal fade" id="new_user">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Opret butik</h4>
      </div>
      <div class="modal-body">

        <?=form_open(current_url());?>

        <label>Navn</label>
        <input type="text" class="form-control" name="name" required style="margin-bottom: 10px" />

        <label>Initial</label>
        <input type="text" class="form-control" name="initial" required style="margin-bottom: 10px" />

        <label>Adresse</label>
        <textarea name="address" class="form-control" name="address" style="margin-bottom: 10px"></textarea>

        <label>Tlf, CVR og email</label>
        <textarea name="tlcvremail" class="form-control"></textarea>

        <div class="row" style="margin-top:10px;">
          <div class="col-md-6">
          <label>SMTP Username</label>
          <input type="text" class="form-control" name="smtp_username" style="margin-bottom: 10px" />
          </div>

          <div class="col-md-6">
          <label>SMTP Password</label>
          <input type="text" class="form-control" name="smtp_password" style="margin-bottom: 10px" />
          </div>

          <div class="col-md-6">
          <label>SMTP Host</label>
          <input type="text" class="form-control" name="smtp_host" style="margin-bottom: 10px" />
          </div>

          <div class="col-md-6">
          <label>SMTP Port</label>
          <input type="text" class="form-control" name="smtp_port" style="margin-bottom: 10px" />
          </div>
        </div>
        <input type="submit" class="btn btn-success" name="create_boutique" value="Opret" style="margin-top: 20px" />

        <?=form_close();?>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="edit_boutique">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Rediger butik</h4>
      </div>
      <div class="modal-body">

        <div class="loader">
        <center><img src="<?=base_url();?>assets/images/loader.gif" /></center>
        </div>

        <div class="editContent" style="display: none">

        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<h1 class="page-header">
	Butikker

	<?php
	if($this->global_model->check_permission('create_boutique',FALSE)){
	?>
	<div class="pull-right">
		<a href="#" class="btn btn-success" data-toggle="modal" data-target="#new_user">Opret ny butik</a>
	</div>
	<?php
	}
	?>

</h1>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Navn</th>
      <th>Initial</th>
      <th>Adresse</th>
      <th>Oprettet</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  	<?php
  	foreach($boutiques as $boutique):
  	?>
    <tr>
      <th><?=$boutique->id;?></th>
      <td><?=$boutique->name;?></td>
      <td><?=$boutique->initial;?></td>
      <td><?=nl2br($boutique->address);?></td>
      <td><?=date("d/m/Y H:i",$boutique->created_timestamp);?></td>
      <td width="150px">
      	<a href="#" class="btn btn-info btn-xs" data-toggle="modal" data-id="<?=$boutique->id;?>" data-target="#edit_boutique">Rediger</a>
      	<a href="<?=site_url('boutiques/cancel/'.$boutique->id);?>" class="confirm btn btn-default btn-xs">Deaktiver</a>
      </td>
    </tr>
    <?php
    endforeach;
    ?>
  </tbody>
</table>
