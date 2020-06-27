<h1 class="page-header">
	Reservation

	<div class="pull-right">

	</div>

</h1>

<?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
<div id="calendar">

</div>

<div class="modal fade" id="reservation_edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>


        <h4 class="modal-title">Reservation</h4>

      </div>
      <div class="modal-body">
				<form id="booking_edit_form">
					<table class='table table-striped'>
						<tr>
							<td>Dato:</td><td><input id='booking_date_edit' type="text" name="booking_date" class='form-control ' /></td>
						</tr>
						<tr>
							<td>Tidspunkt:</td><td><input id='booking_time_edit' type="text" name="booking_time" class='form-control' /></td>
						</tr>
						<tr>
							<td>Notes:</td><td><textarea id='booking_note_edit' type="text" name="booking_note" class='form-control'></textarea></td>
						</tr>
						<tr>
							<td><input type="hidden" name="booking_id" id="booking_id_edit" /></td>
							<td><button type='submit' name='submit' class='btn btn-info btn-sm'>Save</button></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="reservation_detail">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>


        <h4 class="modal-title">Reservation</h4>

      </div>
      <div class="modal-body">
				<table class='table table-striped'>
					<tr>
						<td>Navn:</td><td id='booking_name'></td>
					</tr>
					<tr>
						<td>Email:</td><td id='booking_email'></td>
					</tr>
					<tr>
						<td>Tlf nummer:</td><td id='booking_phone'></td>
					</tr>
					<tr>
						<td>Butik :</td><td id='booking_store'></td>
					</tr>
					<tr>
						<td>Reparation:</td><td id='booking_repair'></td>
					</tr>
					<tr>
						<td>Dato:</td><td id='booking_date'></td>
					</tr>
					<tr>
						<td>Tidspunkt:</td><td id='booking_time'></td>
					</tr>
					<tr>
						<td>Besked:</td><td id='booking_message'></td>
					</tr>
					<tr>
						<td>Note:</td><td id='booking_note'></td>
					</tr>
				</table>
				<div id="booking_made_from"></div>
				<a class="btn btn-danger btn-sm pull-right" id="event_delete" data-id=''><span class='fa fa-trash'></span></a>
				<a class="btn btn-warning btn-sm pull-right" id="event_edit" data-id='' style="margin-right: 10px;"><span class='fa fa-edit'></span></a>
				<div class="pull-right" style="margin-right: 10px;">

					<form method="get" action="<?php echo base_url('receipt'); ?>">
						<input name="action" type="hidden" value="create" />
						<input name="name" type="hidden" id="receipt_name" value="" />
						<input name="phone" type="hidden" id="receipt_phone" value="" />
						<input name="email" type="hidden" id="receipt_email" value="" />
						<input name="repair" type="hidden" id="receipt_repair" value="" />
						<input name="product" type="hidden" id="receipt_product" value="" />
						<button type='submit' name='submit' class='btn btn-info btn-sm'>+</button>
					</form>
				</div>

				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
<link href="<?= base_url(); ?>assets/plugin/fullcalendar/fullcalendar.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>assets/plugin/fullcalendar/lib/moment.min.js"></script>
<script src="<?= base_url(); ?>assets/plugin/fullcalendar/fullcalendar.min.js"></script>
<script src="<?= base_url(); ?>assets/plugin/fullcalendar/locale/da.js"></script>
<style>
.fc-day-grid-event, tr.fc-list-item{
	cursor: pointer;
}
.fc-event.past{
	background-color: #afafaf !important;
    border-color: #afafaf !important;
}
</style>
<script>
$(function() {

  // page is now ready, initialize the calendar...
	var bookings = <?php echo $bookings; ?>;
  $('#calendar').fullCalendar({
		header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,basicWeek,list'
      },
		events: bookings,
		defaultView: 'month',
		eventBackgroundColor: '#f24e9c',
		eventBorderColor: '#f24e9c',
		eventClick: function(data, jsEvent, view) {
			$("#booking_name").html(data.name);
			$("#booking_email").html(data.email);
			$("#booking_phone").html(data.phone);
			$("#booking_store").html(data.store);
			$("#receipt_name").val(data.name);
			$("#receipt_phone").val(data.phone);
			$("#receipt_product").val(data.model);
			if(data.brand && data.model){
				var repair_brand = data.brand+"-"+data.model+"<br />";
			}else{
				var repair_brand = "";
			}
			
			$("#receipt_repair").val(data.repair);
			$("#receipt_email").val(data.email);
			$("#booking_repair").html(data.repair);
			$("#booking_date").html(data.date);
			$("#booking_date_edit").val(data.date);
			$("#booking_time").html(data.time);
			$("#booking_time_edit").val(data.time);
			$("#booking_message").html(data.message);
			$("#booking_note_edit").val(data.note);
			$("#booking_note").html(data.note);
			$("#booking_made_from").html("<strong>Booking made from:</strong> <a href='"+data.page_source+"' target='_blank'>" + data.page_source + "</a>");
			$("#event_delete").data('id',data.id);
			$("#event_edit").data('id',data.id);
			$("#booking_id_edit").val(data.id);
			$("#reservation_detail").modal('show');
	  },
	   eventRender: function (calev, elt, view) {
			elt.attr('data-date',calev.date);
			var ntoday = new Date();
			if (calev.start._d.getTime() < ntoday.getTime()) {
				elt.addClass("past");
				elt.children().addClass("past");
			}
		},
		eventAfterAllRender: function( view ) {
			//console.log(view);

		}
  });

	$(document).on("click","#event_edit",function(e){
		e.preventDefault();
		$("#reservation_detail").modal('hide');
		$("#reservation_edit").modal('show');

	});

	$(document).on("click","#event_delete",function(e){
			e.preventDefault();
			var id = $(this).data('id');
			if(!confirm('Er du sikker?')){
				return false;
			}else{
				$.ajax({
					url: "<?php echo base_url('booking/delete_booking'); ?>",
					type: "post",
					data: {id:id},
					success: function(result){
						if(result=='ok'){
							location.reload();
						}
					}
				});
			}

	});

	$(document).on("submit","#booking_edit_form",function(e){
			e.preventDefault();
			$.ajax({
				url: "<?php echo base_url('booking/update_booking'); ?>",
				type: "post",
				data: $(this).serialize(),
				success: function(result){
					if(result=='ok'){
						location.reload();
					}
				}
			});
	});

});
</script>
