<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function siw_get_email_template( $template_args ) {
	$site_url = get_site_url();

	/* opties uit array halen */
	$remove_linebreaks = isset( $template_args['remove_linebreaks'] ) ? $template_args['remove_linebreaks'] : false;
	$subject = isset( $template_args['subject'] ) ? $template_args['subject'] : '';
	$message = isset( $template_args['message'] ) ? $template_args['message'] : '';
	$show_summary = isset( $template_args['show_summary'] ) ? $template_args['show_summary'] : false;
	$show_signature = isset( $template_args['show_signature'] ) ? $template_args['show_signature'] : false;
	$signature_name = isset( $template_args['signature_name'] ) ? $template_args['signature_name'] : '';
	$signature_title = isset( $template_args['signature_title'] ) ? $template_args['signature_title'] : '';

/* Start template */
ob_start();
?>
<body bgcolor="#ffffff">
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
<tr>
	<td align="center">&nbsp;</td>
</tr>
<tr>
	<td>
		<table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="border-radius:3px !important">
			<tr>
				<td align="center">&nbsp;</td>
			</tr>
			<tr>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="10%">&nbsp;</td>
							<td width="20%"><a href="<?php echo $site_url;?>" target="_blank"><img src="<?php echo SIW_ASSETS_URL;?>images/mail/logo.png" width="144" height="76" border="0" alt="logo" title="Bezoek onze website"/></a></td>
							<td width="60%" style="vertical-align:bottom;border-bottom: solid #ff9900;font-family:'Open Sans', Verdana, normal; color:#666666; font-size:1em; font-weight:bold;" align="center">
								<?php echo $subject;?>
							</td>
							<td width="10%">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center">&nbsp;</td>
			</tr>

			<tr>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="10%">&nbsp;</td>
							<td width="80%" align="left" valign="top">
								<div style="font-family:'Open Sans', Verdana, normal; color:#444; font-size:1em; ">
								<?php echo $message;?>
								<?php if ( $show_signature ) :?>
									<br/><br/>
									Met vriendelijke groet,<br /><br />
									<?php echo $signature_name;?><br/>
									<span style="color:#808080;">
										<?php echo $signature_title;?>
									</span>
									<?php endif; ?>
									<br/><br/>
								</div>
								<?php if ( $show_summary ) :?>
									<br/><br/>
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td colspan="3" height="20" style="font-family:'Open Sans', Verdana, normal; color:#666; font-size:0.75em; font-weight:bold; border-top:thin solid #ff9900" >
												Ingevulde gegevens
											</td>
										</tr>
										{summary}
									</table>
								<?php endif; ?>
							</td>
							<td width="10%">&nbsp;</td>
						</tr>

					</table>
				</td>
			</tr>
			<tr>
				<td align="center" height="20">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="10%">&nbsp;</td>
							<td height="20" style="margin-left:10%; m-right:10%;border-bottom:thin solid #ff9900"></td>
							<td width="10%">&nbsp;</td>
						</tr>
						<tr>
							<td width="10%">&nbsp;</td>
							<td width="auto" align="center" style="font-family:'Open Sans', Verdana, normal; color:#666; font-size:0.75em; font-weight:bold"><?php echo SIW_NAME; ?> | <?php echo SIW_PHONE; ?> | <a href= "mailto:<?php echo SIW_EMAIL;?>" style="color:#666; text-decoration:none"><?php echo SIW_EMAIL;?></a>
							</td>
							<td width="10%">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td height="30">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="40%">&nbsp;</td>
							<td width="auto" align="center"><a href="https://www.facebook.com/siwvolunteers" target="_blank"><img src="<?php echo SIW_ASSETS_URL;?>images/mail/facebook.png" alt="facebook" title="Volg ons op Facebook" width="20" height="20" border="0" /></a></td>
							<td width="auto" align="center"><a href="https://twitter.com/siwvolunteers" target="_blank"><img src="<?php echo SIW_ASSETS_URL;?>images/mail/twitter.png" alt="twitter" title="Volg ons op Twitter" width="20" height="20" border="0" /></a></td>
							<td width="auto" align="center"><a href="https://www.instagram.com/siwvolunteers/" target="_blank"><img src="<?php echo SIW_ASSETS_URL;?>images/mail/instagram.png" alt="instagram" title="Volg ons op Instagram" width="20" height="20" border="0" /></a></td>
							<td width="auto" align="center"><a href="https://www.youtube.com/user/SIWvolunteerprojects" target="_blank"><img src="<?php echo SIW_ASSETS_URL;?>images/mail/youtube.png" alt="youtube" title="Volg ons op YouTube" width="20" height="20" border="0" /></a></td>
							<td width="auto" align="center"><a href="http://www.linkedin.com/company/siw" target="_blank"><img src="<?php echo SIW_ASSETS_URL;?>images/mail/linkedin.png" alt="linkedin" title="Volg ons op LinkedIn" width="20" height="20" border="0" /></a></td>
							<td width="40%">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center">&nbsp;</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td align="center">&nbsp;</td>
</tr>
</table>
</body>
<?php
	$template = ob_get_clean();
	if ( $remove_linebreaks ) {
		$template = str_replace( array( "\n\r", "\r", "\n" ), '', $template );
	}
	return $template;
}
