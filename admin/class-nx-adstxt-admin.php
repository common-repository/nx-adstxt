<?php

class NX_Adstxt_Admin {

	private $nx_adstxt;
	private $nx_adstxt_var;
	private $nx_adstxt_path;
	private $settings;

	public function __construct($settings) {
		$this->nx_adstxt = NX_ADSTXT_DOMAIN;
		$this->nx_adstxt_var = NX_ADSTXT_VAR;
		$this->settings = $settings;
	}

	public function menu_pages() {
		add_submenu_page(
			'options-general.php' ,
			sprintf(__( 'Configuration | %s', 'nx-adstxt'), NX_ADSTXT_FULLTITLE), 
			NX_ADSTXT_TITLE,
			$this->user_can_config(), 
			$this->nx_adstxt,
			array($this, 'menu_pages_callback')
		);
	}

	/**
	 * The callback for creating a new submenu page under the "Tools" menu.
	 * @access public
	 */
	public function menu_pages_callback() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/nx-adstxt-admin-display.php';
	}

	private function user_can_config() {
		return current_user_can('manage_options') && current_user_can(NX_ADSTXT_CAP_CONFIG);
	}

	public function renderfield_adstxt($args) {
		?>
			<div class="editor">
				<textarea name="nx_adstxt[adstxt]" id="nxAdstxtStyle" cols="60"><?php echo esc_html($this->settings['adstxt']) ?></textarea>
			</div>
		<?php
	}

	public function plugin_add_settings_link($links) {
		if ($this->user_can_config()) {
			$settings_link = '<a href="options-general.php?page=nx-adstxt">' . __( 'Settings', 'nx-adstxt') . '</a>';
			array_push($links, $settings_link);
		}

		return $links;
	}

	/**
	 * Display the contents of /ads.txt when requested.
	 *
	 * @return void
	 */
	public function display_ads_txt() {
		$request = esc_url_raw($_SERVER['REQUEST_URI']);

		if ('/ads.txt' === $request) {
			$output = isset($this->settings['output']) ? $this->settings['output']  : '';
			header('Content-Type: text/plain');
			echo esc_html($output);
			die();
		}
	}

	public function get_external_content($url) {

		if(!ini_get('allow_url_fopen')) {
			return "";
		}

		$ctx = stream_context_create(array('http' =>
			array(
				'timeout' => 100
			)
		));

		$content = @file_get_contents($url, false, $ctx);
		return $content;
	}

	public function admin_notices() {

		/* Check transient, if available display notice */
		if( get_transient(NX_ADSTXT_ACTIVATED) ){
			?>
			<div class="updated notice is-dismissible">
				<p>
					<?php echo  sprintf(__('<strong>%s</strong> successfully activated. The Settings are available <a href="%s">on this page</a>.', 'nx-adstxt' ), NX_ADSTXT_FULLTITLE, 'options-general.php?page=' . NX_ADSTXT_DOMAIN); ?>
				</p>
			</div>
			<?php
			/* Delete transient, only display this notice once. */
			delete_transient(NX_ADSTXT_ACTIVATED);
		}

		/* Allow URL FOpen? */
		if ($this->has_urls()) { 
			if(!ini_get('allow_url_fopen')) {
				?>
					<div class="error notice">
						<p><?php echo  sprintf(__( '<strong>%s</strong><br/> You have configured external ads.txt files, but they can\'t be loaded. The setting <strong>allow_url_fopen</strong> is deactivated. Please activate this in your php.ini or remove all external urls to the ads.txt.', 'nx-adstxt'), NX_ADSTXT_FULLTITLE, 'options-general.php?page=' . NX_ADSTXT_DOMAIN); ?></p>
					</div>
				<?php
			}
		}

		/* Exists ads.txt */
	
		if(NX_Adstxt::exists_adstxt() === true) {
			?>
				<div class="error notice">
					<p><?php echo  sprintf(__( '<strong>%s</strong><br/>Attention: There is already an ads.txt file in the root directory, which overwrites the settings made <a href="%s">here</a>. Please remove the local ads.txt file.', 'nx-adstxt'), NX_ADSTXT_FULLTITLE, 'options-general.php?page=' . NX_ADSTXT_DOMAIN); ?></p>
				</div>
			<?php
		}
		
	}


	public function cron_job() {
		$this->generate_ads_txt($this->settings);
		update_option(NX_ADSTXT_VAR, $this->settings);
	}

	public function get_external_ads_txt(&$settings) {

		if (isset($settings['urls']) && is_array($settings['urls'])) {
			foreach($settings['urls'] as $name => $entry) {
				$content = $this->get_external_content($entry['url']);

				if ($content !== false) {
					$settings['urls'][$name]['content'] = $content;
				} else {
					// restore content!
					if (isset($this->settings['urls'][$name]) && 
						isset($this->settings['urls'][$name]['content'])) {
						$settings['urls'][$name]['content'] = $this->settings['urls'][$name]['content'];
					}
				}
			}
		}
	}

	public function generate_ads_txt(&$settings) {
		$rawData = [];
		$rawData[] = $settings['adstxt'];
		
		if (isset($settings['urls']) && is_array($settings['urls'])) {

			$this->get_external_ads_txt($settings);

			foreach($settings['urls'] as $name => $entry) {

				if (isset($entry['content'])) {
					$rawData[] = $entry['content'];
				}
			}
		}

		$output = isset($this->settings['output']) ? $this->settings['output']  : '';

		// if valid:
		$concat = implode("\n", $rawData);
		$data  = explode("\n", $concat);

		foreach ($data as $key => $line) {

	
			if(preg_match("/^[\s\t]*$/", $line)) {
			   $data[$key] = "#";
			} 
		}

		$output = implode("\n", $data);
		$settings['output'] = $output;
	}

	public function validate($data)
	{
		// clean adstxt code

		
		$data['adstxt'] = wp_strip_all_tags($data['adstxt']);
		$this->generate_ads_txt($data);
		return $data;
	}

	public function has_urls() 
	{
		return isset($this->settings['urls']) && is_array($this->settings['urls']) && count($this->settings['urls']) > 0;
	}

	public function has_adstxt() 
	{
	    $content = $this->settings['adstxt'];
		$content = preg_replace("/[\\n\\r\s\t]+/", "", $content);
		return strlen($content) > 0;
	}
	
	public function rendersection_general()
	{
		?>
			<p class="info"  <?php echo  $this->has_adstxt() ? 'data-toggle' : '' ?>>
				<?php _e('Please enter your own ads.txt data, such as Google AdSense, below. If you\'ve monetized your site through a marketer and provided you with a URL to the required ads.txt data, please submit it below to URLs.', 'nx-adstxt'); ?>
			</p>
		<?php
	}

	public function rendersection_urls()
	{
		?>
			<p class="info" <?php echo  $this->has_urls() ? 'data-toggle' : '' ?>>
				<?php _e('Subsequently, remote ads.txt sources can be included. For this purpose one or more URLs are deposited. The contents of these are appended to existing ads.txt data from the above input field.', 'nx-adstxt'); ?>
			</p>
		<?php
	}

	public function admin_init() {
		$helper = '<a href data-show-help ><span class="dashicons dashicons-editor-help"></span></a>';
		
		add_settings_section(
			$this->nx_adstxt . '-settings-general',
			sprintf(__('%s Ads.txt %s', 'nx-adstxt'), '<span class="dashicons dashicons-edit"></span>', $this->has_adstxt() ? $helper : ''),
			array($this, 'rendersection_general'),
			$this->nx_adstxt
		);

		add_settings_field( 
			$this->nx_adstxt_var . '-adstxt', 
			null,             
			array($this, 'renderfield_adstxt'),  
		 	$this->nx_adstxt,
			$this->nx_adstxt . '-settings-general'
		);

		add_settings_section(
			$this->nx_adstxt . '-settings-urls',
			sprintf(__('%s URLs %s', 'nx-adstxt'), '<span class="dashicons dashicons-admin-links"></span>', $this->has_urls() ? $helper : ''),
			array($this, 'rendersection_urls'),  
			$this->nx_adstxt. '-append'
		);


		register_setting($this->nx_adstxt, $this->nx_adstxt_var, array($this, 'validate'));
	}

	public function enqueue_scripts() {
		if (isset($_GET['page']) && ($_GET['page'] == 'nx-adstxt')) { 
			wp_enqueue_style ($this->nx_adstxt, plugin_dir_url( __FILE__ ) . 'css/nx-adstxt-admin.css', array(), NX_ADSTXT_VERSION, 'all');
			wp_enqueue_script($this->nx_adstxt, plugin_dir_url( __FILE__ ) . 'js/nx-adstxt-admin.js', array( 'jquery'), NX_ADSTXT_VERSION, false);
		
			// Enqueue code editor (codemirror - since version 4.9).
			if (function_exists('wp_enqueue_code_editor')) {
				wp_enqueue_code_editor(array());
			}
		}
	}
}