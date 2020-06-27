<h1 class="page-header">
	Reklamation #<?=$this->uri->segment(3);?>
</h1>

<div class="row">
	<div class="col-md-7">
		<div class="table-responsive">
		<?=form_open(current_url());?>
		<table class="table table-striped">
		  <tbody>
		  	<tr>
		  		<td width="200px">Navn</td>
		  		<td><?=$complain[0]->name;?></td>
		  	</tr>
		  	<tr>
		  		<td  width="200px">Tlf. nummer</td>
		  		<td><?=$complain[0]->number;?></td>
		  	</tr>
		  	<tr>
		  		<td  width="200px">E-mail</td>
		  		<td><?=$complain[0]->email;?></td>
		  	</tr>
		  	<tr>
		  		<td  width="200px">Ordre ID</td>
		  		<td><?=$complain[0]->order_id;?></td>
		  	</tr>
		  	<tr>
		  		<td  width="200px">IMEI nummer</td>
		  		<td><?=$complain[0]->imei;?></td>
		  	</tr>
		  	<tr>
		  		<td  width="200px">Model</td>
		  		<td><?=$complain[0]->model;?>, <?=$complain[0]->color;?></td>
		  	</tr>
		  	<tr>
		  		<td  width="200px">Adresse</td>
		  		<td><?=$complain[0]->address;?>, <?=$complain[0]->zipcode;?> <?=$complain[0]->city;?></td>
		  	</tr>
		  	<tr>
		  		<td  width="200px">Grundig fejlbeskrivelse</td>
		  		<td><?=nl2br($complain[0]->description);?></td>
		  	</tr>
		  </tbody>
		</table>
		<?=form_close();?>
		</div>
	</div>
	
	<div class="col-md-4 col-md-offset-1">
		<div class="table-responsive">
		<table class="table table-striped">
		  <tbody>
		  	<tr>
		  		<td width="200px">Oprettet af</td>
		  		<td><?=$by_name;?></td>
		  	</tr>
		  	<tr>
		  		<td  width="200px">Taget ind på</td>
		  		<td><?=$boutique_name;?></td>
		  	</tr>
		  	<tr>
		  		<td  width="200px">Oprettet d.</td>
		  		<td><?=date("d/m/Y H:i",$complain[0]->created_timestamp);?></td>
		  	</tr>
		  </tbody>
		</table>
		</div>
	</div>
	
	<div class="clearfix"></div>
	
	<div class="col-md-7">
		
		<b>Status</b>
		
		<?=form_open(current_url());?>
		<select class="form-control" name="status">
			<option value="created" <?php if($complain[0]->status == 'created'): echo 'selected="true"'; endif; ?>>Reklamation oprettet</option>
			<option value="office" <?php if($complain[0]->status == 'office'): echo 'selected="true"'; endif; ?>>Reklamation på kontor</option>
			<option value="completed" <?php if($complain[0]->status == 'completed'): echo 'selected="true"'; endif; ?>>Reklamation fuldført</option>
			<option value="returned" <?php if($complain[0]->status == 'returned'): echo 'selected="true"'; endif; ?>>Reklamation sendt retur</option>
		</select>
		<input type="submit" class="btn btn-success" name="status_update" style="margin-top: 15px" value="Opdater status" />
		<?=form_close();?>
		
		<br /><br />
		<hr />
		
		<b>Kommentarer</b>
		<?=form_open(current_url());?>
		<textarea class="form-control" name="comment" rows="5"></textarea>
		<input type="submit" class="btn btn-success" value="Opret kommentar" style="margin-top: 15px" name="create_comment" />
		<?=form_close();?>
		<br /><br />
		<?php
		$this->db->where('complain_id',$complain[0]->id);
		$this->db->order_by('id','desc');
		$comments = $this->db->get('complain_comments')->result();
		
		foreach($comments as $comment):
		
		$this->db->where('id',$comment->uid);
		$userinfo = $this->db->get('users_kasse')->result();
		
		if($userinfo){
			$username = $userinfo[0]->name;
		}else{
			$username = '?';
		}
		?>
		<b><?=$username;?>, <?=date("d/m/Y H:i",$comment->created_timestamp);?></b><br />
		<?=$comment->comment;?>
		<hr />
		<?php
		endforeach;
		?>
		
	</div>

</div>

<input type="hidden" class="disableChained" value="1" />