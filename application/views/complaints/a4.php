<meta charset="UTF-8">
<div style="width: 700px; height: 1000px;">
	
	<div style="float: right; text-align: right; padding: 20px 40px 0px 40px; font-size: 13px"><?=date("d/m-Y, H:i",$complain[0]->created_timestamp);?><br /><?=$address;?></div>

	<div style="clear: both"></div>
	
	<h1 style="text-align: center; magin-top: 40px; margin-bottom: 40px">Reklamationsformular #<?=$complain[0]->id;?></h1>
	
	<div style="padding: 0px 40px;">
	<table width="100%">
		<tr>
			<td>Navn:</td>
			<td><?=$complain[0]->name;?></td>
		</tr>
		<tr>
			<td>Tlf. nr.:</td>
			<td><?=$complain[0]->number;?></td>
		</tr>
		<tr>
			<td>E-mail:</td>
			<td><?=$complain[0]->email;?></td>
		</tr>
		<tr>
			<td>Ordre nr.:</td>
			<td><?=$complain[0]->order_id;?></td>
		</tr>
		<tr>
			<td>IMEI nr.:</td>
			<td><?=$complain[0]->imei;?></td>
		</tr>
		<tr>
			<td>Model:</td>
			<td><?=$complain[0]->model;?>, <?=$complain[0]->color;?></td>
		</tr>
		<tr>
			<td>Adresse:</td>
			<td><?=$complain[0]->address;?>, <?=$complain[0]->zipcode;?> <?=$complain[0]->city;?></td>
		</tr>
		
		<tr>
			<td></td>
			<td></td>
		</tr>
		
		<tr>
			<td>Grundig fejlbeskrivelse:</td>
		</tr>
		<tr>
			<td colspan="2" style="border: 1px solid #2e2e2e; width: 100%; height: 130px; padding: 5px; font-size: 12px" valign="top"><?=$complain[0]->description;?></td>
		</tr>
		
	</table>
	
	
	<div style="margin-top: 70px; font-size: 14px">
	
		Det med småt:<br />
		Kære kunde, vi har nu taget din enhed ind til reklamation.<br />
		Vi behandler din sag på følgende måde 
		<ul>
			<li>1.	Enheden bliver sendt til vores hovedkontor, Holbergsgade 26 – 1067 København K </li>
			<li>2.	RMA afdelingen gennemgår enheden hvorefter de vurderer om reklamationen er berettiget. Såfremt reklamationen ikke er berettiget, bliver du kontakt med tilbud for evt. udbedring af skader*</li>
			<li>3.	Når skaderne er udbedret, bliver enheden testet på ny, hvorefter den bliver sendt retur til dig pr. Post.
		Det er ikke muligt at afhentes i butik</li>
			<li>4.	Anslået behandlingstid 7-10 hverdage. </li>
		</ul>		
		<br /><br /><br /><br />
		Jeg har bekræfter at have læst betingelserne for reklamationen.
		
		<br /><br />
		Sted og dato<br /><br />
		
		<div style="border-bottom: 1px solid #2e2e2e; width: 80%; margin-top: 55px"></div>
	
	
		<div class="footer" style="font-size: 10px; color: #bc9c80; margin-top: 70px">* Hvis enheden har lidt fysisk overlast eller fået væske betegnes førnævnte s om en selvforskyldt skade. 
Dette dækkes ikke af din reklamationsret -  hvorfor der opkræves et gebyr på 199kr for håndtering af denne.
		</div>
	</div>
	
	</div>
	
</div>