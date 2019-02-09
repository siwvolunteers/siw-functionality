<?php
/**
 * Email Footer
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

										</td>
										<td width="10%">&nbsp;</td>
									</tr>
									<tr>
										<td width="10%">&nbsp;</td>
										<td width="80%" height="20" style="border-bottom:thin solid #ff9900"></td>
										<td width="10%">&nbsp;</td>
									</tr>
								</table>
							<!-- End Body -->
							</td>
						</tr>
						<tr>
							<td align="center" height="20">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="10%">&nbsp;</td>
										<td width="auto" align="center" style="font-family:Verdana, normal; color:#666; font-size:0.7em; font-weight:bold"><?php echo SIW_Properties::NAME;?>
										</td>
										<td width="10%">&nbsp;</td>
									</tr>
									<tr>
										<td width="10%">&nbsp;</td>
										<td width="auto" align="center" style="font-family:Verdana, normal; color:#666; font-size:0.7em; font-weight:bold"><a href= "<?php echo SIW_SITE_URL;?>" target="_blank" style="color:#666; text-decoration:none" title="<?php esc_attr_e( 'Bezoek onze website', 'siw' );?>"><?php echo SIW_SITE_NAME;?></a> | <a href="tel:<?php echo SIW_Properties::PHONE_INTERNATIONAL;?>" style="color:#666; text-decoration:none"><?php echo SIW_Properties::PHONE; ?></a> | <a href="mailto:<?php echo SIW_Properties::EMAIL;?>" style="color:#666; text-decoration:none"><?php echo SIW_Properties::EMAIL;?></a>
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
										<td width="auto" align="center"><a href="<?php echo SIW_Properties::FACEBOOK_URL;?>" target="_blank"><img src="<?php echo SIW_ASSETS_URL; ?>images/mail/facebook.png" alt="facebook" title="<?php esc_attr_e( 'Volg ons op Facebook', 'siw' );?>" width="20" height="20" border="0" /></a></td>
										<td width="auto" align="center"><a href="<?php echo SIW_Properties::TWITTER_URL;?>" target="_blank"><img src="<?php echo SIW_ASSETS_URL; ?>images/mail/twitter.png" alt="twitter" title="<?php esc_attr_e( 'Volg ons op Twitter', 'siw' );?>" width="20" height="20" border="0" /></a></td>
										<td width="auto" align="center"><a href="<?php echo SIW_Properties::INSTAGRAM_URL;?>" target="_blank"><img src="<?php echo SIW_ASSETS_URL; ?>images/mail/instagram.png" alt="instagram" title="<?php esc_attr_e( 'Volg ons op Instagram', 'siw' );?>" width="20" height="20" border="0" /></a></td>
										<td width="auto" align="center"><a href="<?php echo SIW_Properties::YOUTUBE_URL;?>" target="_blank"><img src="<?php echo SIW_ASSETS_URL; ?>images/mail/youtube.png" alt="youtube" title="<?php esc_attr_e( 'Volg ons op YouTube', 'siw' );?>" width="20" height="20" border="0" /></a></td>
										<td width="auto" align="center"><a href="<?php echo SIW_Properties::LINKEDIN_URL;?>" target="_blank"><img src="<?php echo SIW_ASSETS_URL; ?>images/mail/linkedin.png" alt="linkedin" title="<?php esc_attr_e( 'Volg ons op LinkedIn', 'siw' );?>" width="20" height="20" border="0" /></a></td>
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
</html>
