
<h1 class="page-header">
	Test af enhed #<?=$order->id;?>
</h1>

<table width="350px">
	<tr>
		<td>Tekniker</td>
		<td width=""><?=$me[0]->name;?></td>
	</tr>
	<tr>
		<td>Enhed</td>
		<td><?=$order->product;?></td>
	</tr>
	<tr>
		<td>GB</td>
		<td><?=$order->gb;?></td>
	</tr>
	<tr>
		<td>IMEI</td>
		<td><?=$order->imei;?></td>
	</tr>
</table>

<?=form_open('bought/test/'.$order->id);?>

<table class="test_device_list table" width="80%">
	
	<!--<tr>
		<td width="20px"><input type="checkbox" required="true" value="1" class="choose_all" name="choose_all" /></td>
		<td colspan="10">Vælg alle</td>
	</tr>-->
	
	<tr>
		<td width="20px"><input type="checkbox" required="true" value="1" name="signal" /></td>
		<td>Signal test</td>
		
		<td width="20px"><input type="checkbox" required="true" value="1" name="lcd" /></td>
		<td>LCD test</td>
		
		<td width="20px"><input type="checkbox" required="true" value="1" name="microphone" /></td>
		<td>Microphone test</td>
	</tr>
	<tr>
		<td width="20px"><input type="checkbox" required="true" value="1" name="wifi" /></td>
		<td>WiFi test</td>
		
		<td width="20px"><input type="checkbox" required="true" value="1" name="earpiece" /></td>
		<td>Ear-piece test</td>
		
		<td width="20px"><input type="checkbox" required="true" value="1" name="buttons" /></td>
		<td>Buttons test</td>
	</tr>
	<tr>
		<td width="20px"><input type="checkbox" required="true" value="1" name="digitizer" /></td>
		<td>Digitizer test</td>
		
		<td width="20px"><input type="checkbox" required="true" value="1" name="speaker" /></td>
		<td>Speaker test</td>
		
		<td width="20px"><input type="checkbox" required="true" value="1" name="chargin" /></td>
		<td>Chargin test</td>
	</tr>
	<tr>
		<td width="20px"><input type="checkbox" required="true" value="1" name="onoff" /></td>
		<td>On / off test</td>
		
		<td width="20px"><input type="checkbox" required="true" value="1" name="vibrate" /></td>
		<td>Vibrate test</td>
		
		<td width="20px"><input type="checkbox" required="true" value="1" name="frontcamera" /></td>
		<td>Front camera test</td>
	</tr>
	<tr>
		<td width="20px"><input type="checkbox" required="true" value="1" name="backcamera" /></td>
		<td>Back camera test</td>
		
		<td width="20px"><input type="checkbox" required="true" value="1" name="proximity_sensor" /></td>
		<td>Proximity sensor test</td>
		
		<td width="20px"><input type="checkbox" required="true" value="1" name="volume" /></td>
		<td>Volume test</td>
	</tr>
	<tr>
		<td width="20px"><input type="checkbox" value="1" name="touchid" /></td>
		<td>Touch-ID test</td>
		
		<td width="20px"><input type="checkbox" required="true" value="1" name="simtray" /></td>
		<td>Sim tray check</td>
		
		<td width="20px"><input type="checkbox" required="true" value="1" name="screw" /></td>
		<td>Screw check</td>
	</tr>
	
	<tr>
		<td width="20px"><input type="checkbox" required="true" value="1" name="battery" /></td>
		<td colspan="7">
			Battery test
			<div class="clearfix"></div>
			<br />
			<table class="table table-bordered" style="padding-top: 15px">
				<tr>
					<td width="50px">Battery Cycles</td>
					<td width="100px">
						<input type="text" class="form-control" name="battery_cycles" value="" />
					</td>
				</tr>
				<tr>
					<td>Battery DesignCapacity</td>
					<td>
						<input type="text" class="form-control" name="battery_design" value="" />
					</td>
				</tr>
				<tr>
					<td>Battery FullChargeCapacity</td>
					<td>
						<input type="text" class="form-control" name="battery_fullcharge" value="" />
					</td>
				</tr>
			</table>
			
		</td>
	</tr>
	
	<tr>
		<td width="20px"><input type="checkbox" required="true" name="ready_to_sell" /></td>
		<td colspan="6">Reset phone and ready to sell</td>
		
	</tr>
	
	<tr>
		<td width="" colspan="2">
			<input type="submit" class="btn btn-success btn-lg" name="ready" value="Tested and ready to sell" />
		</td>

	</tr>
	
</table>

<?=form_close();?>