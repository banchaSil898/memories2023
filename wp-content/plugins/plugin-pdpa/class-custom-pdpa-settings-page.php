<?php
class Custom_PDPA_Settings_Page {

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'init_settings' ) );

	}

	public function add_admin_menu() {

		add_options_page(
			esc_html__( 'PDPA', 'matichon-pdpa' ),
			esc_html__( 'PDPA', 'matichon-pdpa' ),
			'manage_options',
			'pdpa-page',
			array( $this, 'page_layout' )
		);

	}

	public function init_settings() {

		register_setting(
			'custom_pdpa',
			'custom_pdpa'
		);

		add_settings_section(
			'custom_pdpa_section',
			'',
			false,
			'custom_pdpa'
		);

		add_settings_field(
			'matichon_pdpa_active',
			__( 'Active', 'matichon-pdpa' ),
			array( $this, 'render_matichon_pdpa_active_field' ),
			'custom_pdpa',
			'custom_pdpa_section'
		);
		add_settings_field(
			'matichon_pdpa_background_color',
			__( 'Background Color', 'matichon-pdpa' ),
			array( $this, 'render_matichon_pdpa_background_color_field' ),
			'custom_pdpa',
			'custom_pdpa_section'
		);
		add_settings_field(
			'matichon_pdpa_text',
			__( 'PDPA Text', 'matichon-pdpa' ),
			array( $this, 'render_matichon_pdpa_text_field' ),
			'custom_pdpa',
			'custom_pdpa_section'
		);

	}

	public function page_layout() {

		// Check required user capability.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'matichon-pdpa' ) );
		}

		// Admin Page Layout
		echo '<div class="wrap">' . "\n";
		echo '	<h1>' . esc_html( get_admin_page_title() ) . '</h1>' . "\n";
		echo '	<form action="options.php" method="post">' . "\n";

		settings_fields( 'custom_pdpa' );
		do_settings_sections( 'custom_pdpa' );
		submit_button();

		echo '	</form>' . "\n";
		echo '</div>' . "\n";

	}

	function render_matichon_pdpa_active_field() {

		// Retrieve data from the database.
		$options = get_option( 'custom_pdpa' );

		// Set default value.
		$value = isset( $options['matichon_pdpa_active'] ) ? $options['matichon_pdpa_active'] : '';

		// Field output.
		echo '<select name="custom_pdpa[matichon_pdpa_active]" class="matichon_pdpa_active_field">';
		echo '	<option value="0" ' . selected( $value, '0', false ) . '> ' . esc_html__( 'ไม่ใช้งาน PDPA', 'matichon-pdpa' ) . '</option>';
		echo '	<option value="1" ' . selected( $value, '1', false ) . '> ' . esc_html__( 'ใช้งาน PDPA', 'matichon-pdpa' ) . '</option>';
		echo '</select>';

	}

	function render_matichon_pdpa_background_color_field() {

		// Retrieve data from the database.
		$options = get_option( 'custom_pdpa' );

		// Set default value.
		$value = isset( $options['matichon_pdpa_background_color'] ) ? $options['matichon_pdpa_background_color'] : '#f4ede1';

		// Field output.
		echo '<input type="color" name="custom_pdpa[matichon_pdpa_background_color]" class="regular-text matichon_pdpa_background_color_field" placeholder="' . esc_attr__( '', 'matichon-pdpa' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . esc_html__( 'เลือกสีสำหรับพื้นหลังของ PDPA', 'matichon-pdpa' ) . '</p>';

	}

	function render_matichon_pdpa_text_field() {

		// Retrieve data from the database.
		$options = get_option( 'custom_pdpa' );

		// Set default value.
		$value = isset( $options['matichon_pdpa_text'] ) ? $options['matichon_pdpa_text'] : 'เว็บไซต์นี้ใช้คุ้กกี้เพื่อสร้างประสบการณ์ที่ดีมีประสิทธิภาพยิ่งขี้น <a href="https://www.matichon.co.th/privacy-policy" target="_blank">อ่านเพิ่มเติมคลิก (Privacy Policy) และ (Cookies Policy)</a>';

		// Field output.
		echo '<textarea name="custom_pdpa[matichon_pdpa_text]" class="regular-text matichon_pdpa_text_field" style="height: 200px;" placeholder="' . esc_attr__( '', 'matichon-pdpa' ) . '">' . $value . '</textarea>';
		echo '<p class="description">' . esc_html__( 'ข้อความแสดงผลสำหรับแจ้ง PDPA', 'matichon-pdpa' ) . '</p>';

	}

}

new Custom_PDPA_Settings_Page();
