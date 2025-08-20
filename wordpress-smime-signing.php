<?php
/**
 * WordPress S/MIME Signing Plugin
 * Plugin Name: WordPress S/MIME Signing Plugin
 * Plugin URI: https://github.com/liups233/wordpress-smime-signing
 * Description: A WordPress plugin for signing all outbound emails with S/MIME certificate. 
 * Version: 1.0
 * Requires PHP: 7.2
 * Author: Liups233
 * Author URI: https://www.liups.net
 * License: GPL-3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain: wordpress-smime-signing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Disallow direct HTTP access.
}

/**
 * Base class for WordPress to register and initialize this plugin.
 */
class WP_SMIME_SIGNING {
	/**
	 * Entry point for the WordPress framework into plugin code.
	 *
	 * This is the method called when WordPress loads the plugin file.
	 * It is responsible for "registering" the plugin's main functions
	 * with the WordPress Plugin API.
	 *
	 * @return void
	 */
	public static function register() {
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_page' ) );
		add_action( 'phpmailer_init', array( __CLASS__, 'phpmailer_init' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );
	}
	
	/**
	 * Register settings and fields
	 */
	public static function register_settings() {
		register_setting( 'wp_smime_settings_group', 'wp_smime_settings' );

		add_settings_section(
			'wp_smime_section',
			'S/MIME Configuration',
			null,
			'wp-smime-settings'
		);

		add_settings_field(
			'smime_public_key',
			'Public Key Path',
			array( __CLASS__, 'smime_public_key_field' ),
			'wp-smime-settings',
			'wp_smime_section'
		);

		add_settings_field(
			'smime_private_key',
			'Private Key Path',
			array( __CLASS__, 'smime_private_key_field' ),
			'wp-smime-settings',
			'wp_smime_section'
		);

		add_settings_field(
			'smime_password',
			'Private Key Password',
			array( __CLASS__, 'smime_password_field' ),
			'wp-smime-settings',
			'wp_smime_section'
		);

		add_settings_field(
			'smime_cert_chain',
			'Certificate Chain Path',
			array( __CLASS__, 'smime_cert_chain_field' ),
			'wp-smime-settings',
			'wp_smime_section'
		);
	}

	/**
	 * Add admin page
	 */
	public static function add_admin_page() {
		add_menu_page(
			'S/MIME Settings',
			'S/MIME Settings',
			'administrator',
			'wp-smime-settings',
			array( __CLASS__, 'settings_page' ),
			'dashicons-shield-alt',
			60
		);
	}

	/**
	 * Settings page HTML
	 */
	public static function settings_page() {
		?>
		<div class="wrap">
			<h1>S/MIME Settings</h1>
			<form method="post" action="options.php">
				<?php
					settings_fields( 'wp_smime_settings_group' );
					do_settings_sections( 'wp-smime-settings' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Public Key (Certificate) file path field
	 */
	public static function smime_public_key_field() {
		$options = get_option( 'wp_smime_settings' );
		?>
		<input type="text" name="wp_smime_settings[smime_public_key]" value="<?php echo esc_attr( $options['smime_public_key'] ); ?>" class="regular-text" />
		<p>Enter the full file path to the public key file. </p>
		<p>For example: <code>/etc/smime/smime.crt</code>. </p>
		<p>Public key file permission should be 644. </p>
		<?php
	}

	/**
	 * Private Key file path field
	 */
	public static function smime_private_key_field() {
		$options = get_option( 'wp_smime_settings' );
		?>
		<input type="text" name="wp_smime_settings[smime_private_key]" value="<?php echo esc_attr( $options['smime_private_key'] ); ?>" class="regular-text" />
		<p>Enter the full file path to the private key file. </p>
		<p>For example: <code>/etc/smime/smime.key</code>. </p>
		<p>Private key file permission should be 640. </p>
		<?php
	}

	/**
	 * Private Key Password field
	 */
	public static function smime_password_field() {
		$options = get_option( 'wp_smime_settings' );
		?>
		<input type="password" name="wp_smime_settings[smime_password]" value="<?php echo esc_attr( $options['smime_password'] ); ?>" class="regular-text" />
		<p>Enter the password for the private key, if applicable. </p>
		<p>Password will be stored in <b>plain text</b> in your database. </p>
		<?php
	}

	/**
	 * Certificate Chain file path field
	 */
	public static function smime_cert_chain_field() {
		$options = get_option( 'wp_smime_settings' );
		?>
		<input type="text" name="wp_smime_settings[smime_cert_chain]" value="<?php echo esc_attr( $options['smime_cert_chain'] ); ?>" class="regular-text" />
		<p>Enter the full file path to the certificate chain file. </p>
		<p>For example: <code>/etc/smime/certchain.pem</code>. </p>
		<p>Certificate Chain file permission should be 644. </p>
		<?php
	}

	/**
	 * PHPMailer init to sign outgoing emails
	 */
	public static function phpmailer_init( $phpmailer ) {
		$settings = get_option( 'wp_smime_settings' );

		// Do not sign if settings are empty
		if ( ! empty( $settings['smime_public_key'] ) && ! empty( $settings['smime_private_key'] ) ) {
			// Retrieve file paths
			$public_key_path = $settings['smime_public_key'];
			$private_key_path = $settings['smime_private_key'];
			$cert_chain_path = $settings['smime_cert_chain'];

			// Ensure files exist before signing
			if ( file_exists( $public_key_path ) && file_exists( $private_key_path ) && file_exists( $cert_chain_path ) ) {
				// Sign the email
				$phpmailer->sign(
					$public_key_path,
					$private_key_path,
					$settings['smime_password'],
					$cert_chain_path
				);
			}
		}
	}
	
	/**
	 * Delete all settings when uninstalling
	 */
	public static function uninstall() {
		delete_option( 'wp_smime_settings' );
	}
}

// Register the plugin
WP_SMIME_SIGNING::register();
