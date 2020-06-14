<?php

namespace SIW\Email;

use SIW\Properties;
use SIW\Util\Links;

/**
 * E-mail template
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Template {

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Init
	 *
	 * @param array $args
	 */
	public function __construct( array $args ) {
		$defaults = array(
			'subject'           => '',
			'message'           => '',
			'show_summary'      => false,
			'show_signature'    => false,
			'signature_name'    => '',
			'signature_title'   => '',
			'remove_linebreaks' => false,
		);
		$this->args = wp_parse_args( $args, $defaults );	
	}

	/**
	 * Genereert e-mail template
	 * 
	 * @return string
	 */
	public function generate() {

		$output = $this->get_template();
		if ( $this->args['remove_linebreaks'] ) {
			$output = str_replace( array( "\n\r", "\r", "\n" ), '', $output );
		}
		return $output;
	}

	/**
	 * Haal template op
	 * 
	 * @return string
	 */
	protected function get_template() {
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
									<td width="60%" style="vertical-align:bottom;border-bottom: solid <?= Properties::PRIMARY_COLOR;?>;font-family:Verdana, normal; color:#666666; font-size:0.95m; font-weight:bold;" align="center">
										<?= esc_html( $this->args['subject'] );?>
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
										<div style="font-family:Verdana, normal; color:<?php echo Properties::FONT_COLOR;?>; font-size:0.9em; ">
											<p>
											<?= wp_kses_post( $this->args['message'] );?>
											<?php if ( $this->args['show_signature'] ) :?>
												<br/><br/>
												<?= esc_html__( 'Met vriendelijke groet,', 'siw' ); ?><br /><br />
												<?= esc_html( $this->args['signature_name'] );?><br/>
												<?php if ( ! empty( $this->args['signature_title'] ) ) :?>
													<span style="color:#808080;">
														<?= esc_html( $this->args['signature_title'] );?>
													</span>
												<?php endif; ?>
											<?php endif; ?>
											</p>
										</div>
										<?php if ( $this->args['show_summary']  ) :?>
											{summary}
										<?php endif; ?>
									</td>
									<td width="10%">&nbsp;</td>
								</tr>
								<tr>
									<td width="10%">&nbsp;</td>
									<td width="80%" height="20" style="border-bottom:thin solid <?= Properties::PRIMARY_COLOR;?>">&nbsp;</td>
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
									<td width="auto" align="center" style="font-family:Verdana, normal; color:#666; font-size:0.7em; font-weight:bold"><?= Properties::NAME;?>
									</td>
									<td width="10%">&nbsp;</td>
								</tr>
								<tr>
									<td width="10%">&nbsp;</td>
									<td width="auto" align="center" style="font-family:Verdana, normal; color:#666; font-size:0.7em; font-weight:bold"><a href="<?= SIW_SITE_URL;?>" target="_blank" style="color:#666; text-decoration:none" title="<?= esc_attr__( 'Bezoek onze website', 'siw' );?>"><?= SIW_SITE_NAME;?></a> | <a href="tel:<?= Properties::PHONE_INTERNATIONAL;?>" style="color:#666; text-decoration:none"><?= Properties::PHONE; ?></a> | <a href="mailto:<?= Properties::EMAIL;?>" style="color:#666; text-decoration:none"><?= Properties::EMAIL;?></a>
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
										<?php foreach ( siw_get_social_networks( 'follow') as $network ) :?>
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
		<?php
		$template = ob_get_clean();

		return $template;
	}
}