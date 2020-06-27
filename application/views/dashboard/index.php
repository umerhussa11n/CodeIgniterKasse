<?php
$rank_permissions = $this->global_model->get_rank_permissions();
?>
<h1 class="page-header">Vælg handling</h1>
<?php
if($this->session->flashdata('error')){
?>
<div class="alert alert-danger"><?=$this->session->flashdata('error');?></div>
<?php
}
?>

<div class="row">
	
	<div class="col-md-4 dash">
		<a href="<?=site_url('bought?modal=true');?>"><div class="jumbotron dashboardbox">Køb enhed</div></a>
	</div>
	
	<?php
  	if (strpos($rank_permissions,'sold_devices_overview') !== false || strpos($rank_permissions,'all') !== false) {
  	?>
	<div class="col-md-4 dash">
		<a href="<?=site_url('sold?modal=true');?>"><div class="jumbotron dashboardbox">Sælg enhed</div></a>
	</div>
	<?php
	}
	if (strpos($rank_permissions,'sell_access_overview') !== false || strpos($rank_permissions,'all') !== false) {
	?>
	
	<div class="col-md-4 dash">
		<a href="<?=site_url('access');?>"><div class="jumbotron dashboardbox">Sælg tilbehør</div></a>
	</div>
	<?php
	}
	if (strpos($rank_permissions,'tranfer_overview') !== false || strpos($rank_permissions,'all') !== false) {
	?>
	<div class="col-md-4 dash">
		<a href="<?=site_url('transfer');?>"><div class="jumbotron dashboardbox">Overførsler</div></a>
	</div>
	<?php
	}
	if (strpos($rank_permissions,'statistic') !== false || strpos($rank_permissions,'all') !== false) {
	?>
	<div class="col-md-4 dash">
		<a href="<?=site_url('statistic');?>"><div class="jumbotron dashboardbox">Statistik</div></a>
	</div>
	<?php
	}
	if (strpos($rank_permissions,'boutique_overview') !== false || strpos($rank_permissions,'all') !== false) {
	?>
	<div class="col-md-4 dash">
		<a href="<?=site_url('boutiques');?>"><div class="jumbotron dashboardbox">Butikker</div></a>
	</div>
	<?php
	}
	if (strpos($rank_permissions,'users_overview') !== false || strpos($rank_permissions,'all') !== false) {
	?>
	<div class="col-md-4 dash">
		<a href="<?=site_url('users');?>"><div class="jumbotron dashboardbox">Brugere</div></a>
	</div>
	<?php
	}
	if (strpos($rank_permissions,'inventory_overview') !== false || strpos($rank_permissions,'all') !== false) {
	?>
	<div class="col-md-4 dash">
		<a href="<?=site_url('products');?>"><div class="jumbotron dashboardbox">Enheder</div></a>
	</div>
	<?php
	?>
	<div class="col-md-4 dash">
		<a href="<?=site_url('products/inventory');?>"><div class="jumbotron dashboardbox">Lagerstyring</div></a>
	</div>
	<?php
	}
	?>
</div>