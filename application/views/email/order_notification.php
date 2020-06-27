<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Booking Email</title>
        <style>
            /* -------------------------------------
                INLINED WITH htmlemail.io/inline
            ------------------------------------- */
            /* -------------------------------------
                RESPONSIVE AND MOBILE FRIENDLY STYLES
            ------------------------------------- */
            @media only screen and (max-width: 620px) {
                table[class=body] h1 {
                    font-size: 28px !important;
                    margin-bottom: 10px !important;
                }
                table[class=body] p,
                table[class=body] ul,
                table[class=body] ol,
                table[class=body] td,
                table[class=body] span,
                table[class=body] a {
                    font-size: 16px !important;
                }
                table[class=body] .wrapper,
                table[class=body] .article {
                    padding: 10px !important;
                }
                table[class=body] .content {
                    padding: 0 !important;
                }
                table[class=body] .container {
                    padding: 0 !important;
                    width: 100% !important;
                }
                table[class=body] .main {
                    border-left-width: 0 !important;
                    border-radius: 0 !important;
                    border-right-width: 0 !important;
                }
                table[class=body] .btn table {
                    width: 100% !important;
                }
                table[class=body] .btn a {
                    width: 100% !important;
                }
                table[class=body] .img-responsive {
                    height: auto !important;
                    max-width: 100% !important;
                    width: auto !important;
                }
            }
            /* -------------------------------------
                PRESERVE THESE STYLES IN THE HEAD
            ------------------------------------- */
            @media all {
                .ExternalClass {
                    width: 100%;
                }
                .ExternalClass,
                .ExternalClass p,
                .ExternalClass span,
                .ExternalClass font,
                .ExternalClass td,
                .ExternalClass div {
                    line-height: 100%;
                }
                .apple-link a {
                    color: inherit !important;
                    font-family: inherit !important;
                    font-size: inherit !important;
                    font-weight: inherit !important;
                    line-height: inherit !important;
                    text-decoration: none !important;
                }
                .btn-primary table td:hover {
                    background-color: #34495e !important;
                }
                .btn-primary a:hover {
                    background-color: #34495e !important;
                    border-color: #34495e !important;
                }
            }
        </style>
    </head>
    <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
        <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
            <tr>
                <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
                <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
                    <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

                        <!-- START CENTERED WHITE CONTAINER -->

                        <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

                            <!-- START MAIN CONTENT AREA -->
                            <tr>
                                <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                                    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                        <tr>
                                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
											<?php

												echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
													echo '<tr><td align="center">';
													echo "<img src='".base_url('assets/images/logo-telerepair2.png')."' width='200' align='middle' style='text-align:center; margin: auto; margin-top:30px' /><br/><br />";
													echo '</td></tr></table>';

											?>




                                            </td>
                                        </tr>
                                    </table>
                                    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; margin-top: 40px;">
                                      <tr valign="top">
                                        <td width="50%" style="text-transform: uppercase">
                                          <strong>KUNDE:</strong><br />
                                          <?php echo $order['name']; ?><br />
                                          <?php echo $order['company']; ?><br />
                                          CVR: <?php echo $order['cvr']; ?><br />
                                          <?php echo $order['email']; ?>
                                        </td>
                                        <td align="right">

                                          <strong>Telerepair ApS</strong><br />
                                         <?php echo nl2br($address); ?><br />
                                         <?=nl2br($tlfcvrinfo);?><br /><br />
                                          <strong>DATO</strong> <?=date("d/m/Y",$order['created_timestamp']);?><br />
                                          <strong>FAKTURA</strong><br />
                                          <?=$initial;?><?=$order['id'];?><br />
                                        </td>
                                      </tr>
                                    </table>
                                    <div style="max-width:380px; margin-top: 30px;">
                                      <strong>FAKTURA</strong>
                                      <hr />
                                      <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                        <tbody>

                                        <?php
                                        if($order['type'] == 'sold'){
                                          $model = $this->db->select('name')->where('id',$order['product_id'])->get('products')->row()->name;
                                          $gb = $this->db->select('name')->where('id',$order['gb_id'])->get('gbs')->row()->name;
                                        ?>
                                        <tr>
                                          <td style="padding-bottom:10px;">
                                            <?php echo $model.' - '.$gb.'GB'.'<br />'; ?>
                                            IMEI: <?php echo $order['imei']; ?><br />
                                            <?php if($order['color']){ ?>
                                            Farve: <?php echo $order['color']; ?>
                                            <?php } ?>
                                          </td>
                                        </tr>
                                        <?php
                                        }else{
                                          foreach($items as $row){ ?>
                                            <tr>
                                              <td style="padding-bottom:10px; width: 260px"><?php echo $row['product'].' - '.$row['part']; ?></td>
                                              <td style="padding-bottom:10px; width:60px"><?php echo $row['qty']; ?></td>
                                              <td style="padding-bottom:10px; text-align:right"><?=number_format($row['price'],2,',','.');?></td>
                                            </tr>
                                          <?php }
                                          }
                                         ?>
                                        </tbody>

                                      </table>
                                      <hr />
                                      <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 200px; float: right">
                                        <?php if($order['discount']){ ?>
                                        <tr style="font-weight: bold; ">
                                          <td style="text-align:left; border-bottom: 1px solid #000">SUBTOTAL</td>
                                          <td style="text-align:right; border-bottom: 1px solid #000"><?=number_format($order['subtotal'],2,',','.');?> </td>
                                        </tr>
                                        <tr style="font-weight: bold; ">
                                          <td style="text-align:left; border-bottom: 1px solid #000">RABAT (<?php echo $order['discount']; ?>%)</td>
                                          <td style="text-align:right; border-bottom: 1px solid #000"><?=number_format(($order['subtotal']*$order['discount'])/100,2,',','.');?> </td>
                                        </tr>
                                      <?php } ?>
                                      <tr style="font-weight: bold; ">
                                        <td style="text-align:left; border-bottom: 2px solid #000">TOTAL DKK</td>
                                        <td style="text-align:right; border-bottom: 2px solid #000"><?=number_format($order['price'],2,',','.');?> </td>
                                      </tr>
                                    </table>

                                    <br /><br /><br /><br /><br />
                                    <strong>PRISERNE ER INKL. MOMS</strong>
                                    <br /><br /><br />
                                    MANGE TAK FOR DIT KØB <span style="font-size: 20px;">&#x263A;</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- END MAIN CONTENT AREA -->
                        </table>



                        <!-- END CENTERED WHITE CONTAINER -->
                    </div>
                </td>
                <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
            </tr>
        </table>
    </body>
</html>
