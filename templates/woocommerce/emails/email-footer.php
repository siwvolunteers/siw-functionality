<?php
/**
 * Email Footer
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.3.0
 */

use SIW\Data\Social_Network;
use SIW\Properties;
use SIW\Util\Links;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

										</td>
										<td width="10%">&nbsp;</td>
									</tr>
									<tr>
										<td width="10%">&nbsp;</td>
										<td width="80%" height="20" style="border-bottom:thin solid <?= Properties::PRIMARY_COLOR;?>"></td>
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
										<td width="auto" align="center" style="font-family:Verdana, normal; color:#666; font-size:0.7em; font-weight:bold"><?php echo Properties::NAME;?>
										</td>
										<td width="10%">&nbsp;</td>
									</tr>
									<tr>
										<td width="10%">&nbsp;</td>
										<td width="auto" align="center" style="font-family:Verdana, normal; color:#666; font-size:0.7em; font-weight:bold"><a href= "<?php echo SIW_SITE_URL;?>" target="_blank" style="color:#666; text-decoration:none" title="<?php esc_attr_e( 'Bezoek onze website', 'siw' );?>"><?php echo SIW_SITE_NAME;?></a> | <a href="tel:<?php echo Properties::PHONE_INTERNATIONAL;?>" style="color:#666; text-decoration:none"><?php echo Properties::PHONE; ?></a> | <a href="mailto:<?php echo Properties::EMAIL;?>" style="color:#666; text-decoration:none"><?php echo Properties::EMAIL;?></a>
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
											<?php foreach ( siw_get_social_networks( Social_Network::FOLLOW ) as $network ) :?>
											<td width="auto" align="center">
												<?php
													echo Links::generate_image_link(
														$network->get_follow_url(),
														[
															'src'    => SIW_ASSETS_URL . 'images/mail/' . $network->get_slug() . '.png',
															'alt'    => $network->get_slug(),
															'title'  =>  sprintf( __( 'Volg ons op %s', 'siw' ), $network->get_name() ),
															'width'  => 20,
															'height' => 20,
														],
														[
															'target' => '_blank'
														]
													);
												?>
											</td>
											<?php endforeach; ?>
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
