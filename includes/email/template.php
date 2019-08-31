<?php
/**
 * Template voor e-mail
 * 
 * @package SIW\Email
 * @author Maarten Bruna
 * @copy
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Geeft e-mailtemplate terug op basis van parameters
 *
 * @param  array
 * @return string e-mailtemplate
 */
function siw_get_email_template( $args ) {

	$defaults = array(
		'subject'			=> '',
		'message'			=> '',
		'show_summary'		=> false,
		'show_signature'	=> false,
		'signature_name'	=> '',
		'signature_title'	=> '',
		'remove_linebreaks' => false,
	);
	$args = wp_parse_args( $args, $defaults );

/* Start template */
ob_start();
?>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="#ffffff">
<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#eeeeee">
<tr>
	<td align="center">&nbsp;</td>
</tr>
<tr>
	<td align="center" valign="top">
		<table border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#ffffff" style="border-radius:3px !important;">
			<tr>
				<td align="center">&nbsp;</td>
			</tr>
			<tr>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="10%">&nbsp;</td>
							<td width="20%">
								<img src="<?= SIW_ASSETS_URL;?>images/mail/logo.png" style="display: block; border: 0px; outline: none; width: 100%; height: auto; max-width: 144px;" width="144" border="0" alt="logo" />
							</td>
							<td width="60%" style="vertical-align:bottom;border-bottom: solid <?= SIW_Properties::PRIMARY_COLOR;?>;font-family:Verdana, normal; color:#666666; font-size:0.95m; font-weight:bold;" align="center">
								<?= esc_html( $args['subject'] );?>
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
							<td width="80%">
								<div style="font-family:Verdana, normal; color:<?= SIW_Properties::FONT_COLOR;?>; font-size:0.9em; ">
									<p>
									<?= wp_kses_post( $args['message'] );?>
									<?php if ( $args['show_signature'] ) :?>
										<br/><br/>
										<?= esc_html__( 'Met vriendelijke groet,', 'siw' ); ?><br /><br />
										<?= esc_html( $args['signature_name'] );?><br/>
										<?php if ( ! empty( $args['signature_title'] ) ) :?>
											<span style="color:#808080;">
												<?= esc_html( $args['signature_title'] );?>
											</span>
										<?php endif; ?>
									<?php endif; ?>
									</p>
								</div>
								<?php if ( $args['show_summary']  ) :?>
									{summary}
								<?php endif; ?>
							</td>
							<td width="10%">&nbsp;</td>
						</tr>
						<tr>
							<td width="10%">&nbsp;</td>
							<td width="80%" height="20" style="border-bottom:thin solid <?= SIW_Properties::PRIMARY_COLOR;?>">&nbsp;</td>
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
							<td width="auto" align="center" style="font-family:Verdana, normal; color:#666; font-size:0.7em; font-weight:bold"><?= SIW_Properties::NAME;?>
							</td>
							<td width="10%">&nbsp;</td>
						</tr>
						<tr>
							<td width="10%">&nbsp;</td>
							<td width="auto" align="center" style="font-family:Verdana, normal; color:#666; font-size:0.7em; font-weight:bold"><a href="<?= SIW_SITE_URL;?>" target="_blank" style="color:#666; text-decoration:none" title="<?= esc_attr__( 'Bezoek onze website', 'siw' );?>"><?= SIW_SITE_NAME;?></a> | <a href="tel:<?= SIW_Properties::PHONE_INTERNATIONAL;?>" style="color:#666; text-decoration:none"><?= SIW_Properties::PHONE; ?></a> | <a href="mailto:<?= SIW_Properties::EMAIL;?>" style="color:#666; text-decoration:none"><?= SIW_Properties::EMAIL;?></a>
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
							<td width="auto" align="center"><a href="<?= SIW_Properties::FACEBOOK_URL;?>" target="_blank"><img src="<?= SIW_ASSETS_URL;?>images/mail/facebook.png" alt="facebook" title="<?= esc_attr__( 'Volg ons op Facebook', 'siw' );?>" width="20" height="20" border="0" /></a></td>
							<td width="auto" align="center"><a href="<?= SIW_Properties::TWITTER_URL;?>" target="_blank"><img src="<?= SIW_ASSETS_URL;?>images/mail/twitter.png" alt="twitter" title="<?= esc_attr__( 'Volg ons op Twitter', 'siw' );?>" width="20" height="20" border="0" /></a></td>
							<td width="auto" align="center"><a href="<?= SIW_Properties::INSTAGRAM_URL;?>" target="_blank"><img src="<?= SIW_ASSETS_URL;?>images/mail/instagram.png" alt="instagram" title="<?= esc_attr__( 'Volg ons op Instagram', 'siw' );?>" width="20" height="20" border="0" /></a></td>
							<td width="auto" align="center"><a href="<?= SIW_Properties::YOUTUBE_URL;?>" target="_blank"><img src="<?= SIW_ASSETS_URL;?>images/mail/youtube.png" alt="youtube" title="<?= esc_attr__( 'Volg ons op YouTube', 'siw' );?>" width="20" height="20" border="0" /></a></td>
							<td width="auto" align="center"><a href="<?= SIW_Properties::LINKEDIN_URL;?>" target="_blank"><img src="<?= SIW_ASSETS_URL;?>images/mail/linkedin.png" alt="linkedin" title="<?= esc_attr__( 'Volg ons op LinkedIn', 'siw' );?>" width="20" height="20" border="0" /></a></td>
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
	if (  $args['remove_linebreaks'] ) {
		$template = str_replace( array( "\n\r", "\r", "\n" ), '', $template );
	}
	return $template;
}
