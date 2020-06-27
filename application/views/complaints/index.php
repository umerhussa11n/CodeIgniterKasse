<?php
if($this->input->get('open_receipt')):
?>
<script type="text/javascript">
var win = window.open('<?=site_url();?>complaints/export_a4/<?=$this->input->get('open_receipt');?>', '_blank');
win.focus();

top.location.href = '<?=site_url('complaints');?>';
</script>
<?php
endif;
?>

<h1 class="page-header">
	Reklamationer
	<div class="pull-right"><a href="<?=site_url('complaints/create');?>" class="btn btn-success">Opret reklamation</a></div>
</h1>

<div class="table-responsive">
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Enhed</th>
      <th>Info</th>
      <th>Tlf. nummer</th>
      <th>E-mail</th>
      <th>Ordre ID</th>
      <th>IMEI</th>
      <th>Status</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  	<?php

  	foreach($complains as $complain):

  	
  	?>
    <tr>
      <td><?=$complain->id;?></td>
      <td><?=$complain->model;?>, <?=$complain->color;?></td>
      <td><?=$complain->name;?><br /><?=$complain->address;?><br /><?=$complain->zipcode;?> <?=$complain->city;?></td>
      <td><?=$complain->number;?></td>
      <td><?=$complain->email;?></td>
      <td><?=$complain->order_id;?></td>
      <td><?=$complain->imei;?></td>
      <td><span class="label label-<?php if($complain->status == 'returned'): echo 'success'; elseif($complain->status == 'completed'): echo 'primary'; elseif($complain->status == 'office'): echo 'danger'; else: echo 'danger'; endif; ?>"><?=$complain->status;?></span>
      <td align="right"><a href="<?=site_url('complaints/show/'.$complain->id);?>" class="btn btn-primary btn-xs">Gå til reklamation</a></td>
    </tr>
    <?php
    endforeach;
    ?>
  </tbody>
</table>

<?php /*$this->pagination->create_links(); */ ?>

</div>

<input type="hidden" class="disableChained" value="1" />