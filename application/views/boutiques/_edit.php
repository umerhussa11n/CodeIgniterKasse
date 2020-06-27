<?=form_open(current_url());?>

<label>Navn</label>
<input type="text" class="form-control" value="<?=$boutique->name;?>" name="name" required style="margin-bottom: 10px" />

<label>Initial</label>
<input type="text" class="form-control" value="<?=$boutique->initial;?>" name="initial" required style="margin-bottom: 10px" />

<label>Adresse</label>
<textarea name="address" class="form-control" name="address" style="margin-bottom: 10px"><?=$boutique->address;?></textarea>

<label>Tlf, CVR og email</label>
<textarea name="tlcvremail" class="form-control"><?=$boutique->tlcvremail;?></textarea>
<div class="row" style="margin-top:10px;">
  <div class="col-md-6">
  <label>SMTP Username</label>
  <input type="text" class="form-control" value="<?=$boutique->smtp_username;?>" name="smtp_username" style="margin-bottom: 10px" />
  </div>

  <div class="col-md-6">
  <label>SMTP Password</label>
  <input type="password" class="form-control" value="<?=$boutique->smtp_password;?>" name="smtp_password" style="margin-bottom: 10px" />
  </div>

  <div class="col-md-6">
  <label>SMTP Host</label>
  <input type="text" class="form-control" value="<?=$boutique->smtp_host;?>" name="smtp_host" style="margin-bottom: 10px" />
  </div>

  <div class="col-md-6">
  <label>SMTP Port</label>
  <input type="text" class="form-control" value="<?=$boutique->smtp_port;?>" name="smtp_port" style="margin-bottom: 10px" />
  </div>
</div>
<input type="hidden" name="id" value="<?=$boutique->id;?>" />
<input type="submit" class="btn btn-success" name="edit_boutique" value="Opret" style="margin-top: 20px" />

<?=form_close();?>
