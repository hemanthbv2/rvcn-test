<?php
/**
 * Plugin Name: RVCN Chatbot
 * Description: Premium conversational chatbot for RV College of Nursing, helping visitors inquire about admissions, HOD directories, hostel fees, and programs. Includes a configuration settings dashboard.
 * Version: 1.0.0
 * Author: RVCN Developer
 * License: GPL-2.0+
 * Text Domain: rvcn-chatbot
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Constants
define( 'RVCN_CHATBOT_VERSION', '1.0.0' );
define( 'RVCN_CHATBOT_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'RVCN_CHATBOT_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Register Settings Page in WordPress Admin Dashboard
 */
function rvcn_chatbot_add_admin_menu() {
	add_menu_page(
		__( 'RVCN Chatbot Settings', 'rvcn-chatbot' ),
		__( 'RVCN Chatbot', 'rvcn-chatbot' ),
		'manage_options',
		'rvcn-chatbot',
		'rvcn_chatbot_settings_page',
		'dashicons-format-chat',
		100
	);
}
add_action( 'admin_menu', 'rvcn_chatbot_add_admin_menu' );

/**
 * Register settings and sanitization
 */
function rvcn_chatbot_register_settings() {
	register_setting( 'rvcn_chatbot_options_group', 'rvcn_chatbot_enabled', 'sanitize_text_field' );
	register_setting( 'rvcn_chatbot_options_group', 'rvcn_chatbot_title', 'sanitize_text_field' );
	register_setting( 'rvcn_chatbot_options_group', 'rvcn_chatbot_status_text', 'sanitize_text_field' );
	register_setting( 'rvcn_chatbot_options_group', 'rvcn_chatbot_welcome_text', 'sanitize_textarea_field' );
	register_setting( 'rvcn_chatbot_options_group', 'rvcn_chatbot_sheets_url', 'esc_url_raw' );
	register_setting( 'rvcn_chatbot_options_group', 'rvcn_chatbot_logo_url', 'esc_url_raw' );
}
add_action( 'admin_init', 'rvcn_chatbot_register_settings' );

/**
 * Settings Page HTML Rendering
 */
function rvcn_chatbot_settings_page() {
	?>
	<div class="wrap">
		<h1><?php echo esc_html( __( 'RVCN Chatbot Settings Dashboard', 'rvcn-chatbot' ) ); ?></h1>
		<p><?php echo esc_html( __( 'Manage and customize your RV College of Nursing Chatbot frontend display, behaviors, and integrations.', 'rvcn-chatbot' ) ); ?></p>
		
		<hr />

		<form method="post" action="options.php" style="max-width: 800px; background: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-top: 20px;">
			<?php settings_fields( 'rvcn_chatbot_options_group' ); ?>
			<?php do_settings_sections( 'rvcn_chatbot_options_group' ); ?>

			<table class="form-table" style="width: 100%;">
				
				<!-- Enabled / Disabled Option -->
				<tr valign="top">
					<th scope="row" style="width: 200px; font-weight: bold;"><?php _e( 'Enable Chatbot', 'rvcn-chatbot' ); ?></th>
					<td>
						<label class="switch">
							<input type="checkbox" name="rvcn_chatbot_enabled" value="1" <?php checked( '1', get_option( 'rvcn_chatbot_enabled', '1' ) ); ?> />
							<span><?php _e( 'Display the chatbot on the website frontend.', 'rvcn-chatbot' ); ?></span>
						</label>
					</td>
				</tr>

				<!-- Chatbot Header Title -->
				<tr valign="top">
					<th scope="row" style="font-weight: bold;"><?php _e( 'Chatbot Header Title', 'rvcn-chatbot' ); ?></th>
					<td>
						<input type="text" name="rvcn_chatbot_title" value="<?php echo esc_attr( get_option( 'rvcn_chatbot_title', 'RV College of Nursing' ) ); ?>" class="regular-text" style="width: 100%; max-width: 450px;" required />
						<p class="description"><?php _e( 'Header title shown at the top of the chatbot container.', 'rvcn-chatbot' ); ?></p>
					</td>
				</tr>

				<!-- Chatbot Status Text -->
				<tr valign="top">
					<th scope="row" style="font-weight: bold;"><?php _e( 'Status Message', 'rvcn-chatbot' ); ?></th>
					<td>
						<input type="text" name="rvcn_chatbot_status_text" value="<?php echo esc_attr( get_option( 'rvcn_chatbot_status_text', 'Online — Ready to help' ) ); ?>" class="regular-text" style="width: 100%; max-width: 450px;" required />
						<p class="description"><?php _e( 'Sub-heading status text under the title (e.g., Online — Ready to help).', 'rvcn-chatbot' ); ?></p>
					</td>
				</tr>

				<!-- Welcome Tooltip Text -->
				<tr valign="top">
					<th scope="row" style="font-weight: bold;"><?php _e( 'Welcome Prompt Text', 'rvcn-chatbot' ); ?></th>
					<td>
						<textarea name="rvcn_chatbot_welcome_text" rows="3" class="large-text" style="width: 100%; max-width: 450px;" required><?php echo esc_textarea( get_option( 'rvcn_chatbot_welcome_text', 'Hi there! Need help with admissions at RVCN? Chat with us!' ) ); ?></textarea>
						<p class="description"><?php _e( 'The tooltip teaser message displayed above the toggle launcher button.', 'rvcn-chatbot' ); ?></p>
					</td>
				</tr>

				<!-- Google Sheets API URL -->
				<tr valign="top">
					<th scope="row" style="font-weight: bold;"><?php _e( 'Google Sheets Web App URL', 'rvcn-chatbot' ); ?></th>
					<td>
						<input type="url" name="rvcn_chatbot_sheets_url" value="<?php echo esc_url( get_option( 'rvcn_chatbot_sheets_url', 'https://script.google.com/macros/s/AKfycbweu2IVU6J23Dh9_nai5EmwFsjipR3HJmSMSh5ROOk-jcznWhha1Ng2c6WdizFWtYmA/exec' ) ); ?>" class="regular-text" style="width: 100%; max-width: 450px;" />
						<p class="description"><?php _e( 'Optional Google Apps Script endpoint URL where lead form submissions are forwarded.', 'rvcn-chatbot' ); ?></p>
					</td>
				</tr>

				<!-- Custom Logo URL -->
				<tr valign="top">
					<th scope="row" style="font-weight: bold;"><?php _e( 'Custom Logo Image URL', 'rvcn-chatbot' ); ?></th>
					<td>
						<input type="url" name="rvcn_chatbot_logo_url" id="rvcn_chatbot_logo_url" value="<?php echo esc_url( get_option( 'rvcn_chatbot_logo_url', RVCN_CHATBOT_DIR_URL . 'logo.png' ) ); ?>" class="regular-text" style="width: 100%; max-width: 450px;" />
						<p class="description"><?php _e( 'URL of the logo image shown inside the chatbot header and bot messages. Leave default to use RVEI Logo.', 'rvcn-chatbot' ); ?></p>
					</td>
				</tr>

			</table>

			<?php submit_button( __( 'Save Chatbot Settings', 'rvcn-chatbot' ), 'primary', 'submit', true, array( 'style' => 'margin-top: 20px;' ) ); ?>
		</form>
	</div>
	<?php
}

/**
 * Enqueue styles and scripts for the frontend site
 */
function rvcn_chatbot_enqueue_assets() {
	// Only load if the chatbot is enabled
	if ( get_option( 'rvcn_chatbot_enabled', '1' ) !== '1' ) {
		return;
	}

	// Enqueue main stylesheet
	wp_enqueue_style(
		'rvcn-chatbot-style',
		RVCN_CHATBOT_DIR_URL . 'style.css',
		array(),
		RVCN_CHATBOT_VERSION
	);

	// Enqueue Chatbot Data (KB definitions)
	wp_enqueue_script(
		'rvcn-chatbot-data',
		RVCN_CHATBOT_DIR_URL . 'chatbot-data.js',
		array(),
		RVCN_CHATBOT_VERSION,
		true
	);

	// Enqueue Chatbot Core Engine
	wp_enqueue_script(
		'rvcn-chatbot-script',
		RVCN_CHATBOT_DIR_URL . 'script.js',
		array( 'rvcn-chatbot-data' ),
		RVCN_CHATBOT_VERSION,
		true
	);

	// Localize script to pass WordPress admin values to frontend Javascript
	wp_localize_script(
		'rvcn-chatbot-script',
		'rvcnChatbotSettings',
		array(
			'logoUrl'         => esc_url( get_option( 'rvcn_chatbot_logo_url', RVCN_CHATBOT_DIR_URL . 'logo.png' ) ),
			'title'           => esc_html( get_option( 'rvcn_chatbot_title', 'RV College of Nursing' ) ),
			'statusText'      => esc_html( get_option( 'rvcn_chatbot_status_text', 'Online — Ready to help' ) ),
			'welcomeText'     => esc_html( get_option( 'rvcn_chatbot_welcome_text', 'Hi there! Need help with admissions at RVCN? Chat with us!' ) ),
			'googleSheetsUrl' => esc_url_raw( get_option( 'rvcn_chatbot_sheets_url', 'https://script.google.com/macros/s/AKfycbweu2IVU6J23Dh9_nai5EmwFsjipR3HJmSMSh5ROOk-jcznWhha1Ng2c6WdizFWtYmA/exec' ) )
		)
	);
}
add_action( 'wp_enqueue_scripts', 'rvcn_chatbot_enqueue_assets' );

/**
 * Inject Chatbot Markup into the Page Footer
 */
function rvcn_chatbot_render_footer_html() {
	// Only render if enabled
	if ( get_option( 'rvcn_chatbot_enabled', '1' ) !== '1' ) {
		return;
	}

	$logo_url    = esc_url( get_option( 'rvcn_chatbot_logo_url', RVCN_CHATBOT_DIR_URL . 'logo.png' ) );
	$title       = esc_html( get_option( 'rvcn_chatbot_title', 'RV College of Nursing' ) );
	$status_text = esc_html( get_option( 'rvcn_chatbot_status_text', 'Online — Ready to help' ) );
	$welcome     = esc_html( get_option( 'rvcn_chatbot_welcome_text', 'Hi there! Need help with admissions at RVCN? Chat with us!' ) );
	?>
	<!-- Welcome Prompt Tooltip -->
	<div class="welcome-prompt hidden" id="welcomePrompt" role="alert">
		<div class="welcome-prompt-avatar" aria-hidden="true">👋</div>
		<div class="welcome-prompt-text">
			<strong><?php _e( 'Hi there!', 'rvcn-chatbot' ); ?></strong> <?php echo $welcome; ?>
		</div>
		<button class="welcome-prompt-close" id="welcomePromptClose" aria-label="<?php esc_attr_e( 'Dismiss prompt', 'rvcn-chatbot' ); ?>" type="button">✕</button>
	</div>

	<!-- Chat Toggle Button -->
	<button class="chat-toggle" id="chatToggle" aria-label="<?php esc_attr_e( 'Open chat', 'rvcn-chatbot' ); ?>" type="button">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
		</svg>
		<span class="badge" aria-hidden="true">1</span>
	</button>

	<!-- Chat Window -->
	<div class="chat-container" id="chatContainer" role="dialog" aria-label="<?php esc_attr_e( 'RVCN Chatbot', 'rvcn-chatbot' ); ?>">

		<!-- Header -->
		<header class="chat-header">
			<div class="chat-logo" aria-hidden="true">
				<img src="<?php echo $logo_url; ?>" alt="<?php echo esc_attr( $title ); ?> Logo" style="width: 100%; height: 100%; object-fit: contain;">
			</div>
			<div class="chat-header-info">
				<div class="chat-header-title"><?php echo $title; ?></div>
				<div class="chat-header-status">
					<span class="status-dot" aria-hidden="true"></span>
					<?php echo $status_text; ?>
				</div>
			</div>
			<button class="chat-clear-btn" id="chatClearBtn" aria-label="<?php esc_attr_e( 'Clear chat', 'rvcn-chatbot' ); ?>" type="button" title="<?php esc_attr_e( 'Clear chat', 'rvcn-chatbot' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
			</button>
			<button class="chat-close-btn" id="chatCloseBtn" aria-label="<?php esc_attr_e( 'Close chat', 'rvcn-chatbot' ); ?>" type="button">✕</button>
		</header>

		<!-- Messages -->
		<div class="chat-messages" id="chatMessages" role="log" aria-live="polite" aria-label="<?php esc_attr_e( 'Chat messages', 'rvcn-chatbot' ); ?>">
			<!-- Messages will be dynamically inserted here -->
		</div>

		<!-- Input Area -->
		<div class="chat-input-area">
			<input
				type="text"
				class="chat-input"
				id="chatInput"
				placeholder="<?php esc_attr_e( 'Type a message or click a button...', 'rvcn-chatbot' ); ?>"
				autocomplete="off"
				aria-label="<?php esc_attr_e( 'Type your message', 'rvcn-chatbot' ); ?>"
			>
			<button class="chat-send-btn" id="chatSendBtn" aria-label="<?php esc_attr_e( 'Send message', 'rvcn-chatbot' ); ?>" type="button">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="22" y1="2" x2="11" y2="13"></line>
					<polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
				</svg>
			</button>
		</div>

	</div>
	<?php
}
add_action( 'wp_footer', 'rvcn_chatbot_render_footer_html' );
