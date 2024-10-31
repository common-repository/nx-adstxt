<?php


class NX_Adstxt {

	protected $loader;
	protected $settings;
	public static $nx_adstxt_path;
	public static $nx_adstxt_backup_path;

	public function __construct() {
		$this->load_data();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	private function load_data() {
		$this->settings = get_option(NX_ADSTXT_VAR);
	}

	public static function read_adstxt_data () 
	{
		if (!file_exists(self::$nx_adstxt_path)) {
			return "";
		}
		
		return file_get_contents(self::$nx_adstxt_path);
	}

	public static function exists_adstxt () 
	{
		return file_exists(self::$nx_adstxt_path);
	}


	public static function backup_adstxt() 
	{
		if (file_exists(self::$nx_adstxt_path)) {
			rename (self::$nx_adstxt_path, self::$nx_adstxt_backup_path);
		}
	}

	public static function restore_adstxt() 
	{
		if (file_exists(self::$nx_adstxt_backup_path)) {
			rename (self::$nx_adstxt_backup_path, self::$nx_adstxt_path);
		}
	}

	private function load_dependencies() {
		require_once plugin_dir_path(dirname( __FILE__ )) . 'includes/class-nx-adstxt-loader.php';
		require_once plugin_dir_path(dirname( __FILE__ )) . 'includes/class-nx-adstxt-i18n.php';
		require_once plugin_dir_path(dirname( __FILE__ )) . 'admin/class-nx-adstxt-admin.php';
		$this->loader = new NX_Adstxt_Loader();
	}

	private function set_locale() {
		$plugin_i18n = new NX_Adstxt_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	private function define_admin_hooks() {
		$plugin_admin = new NX_Adstxt_Admin($this->settings);

		$this->loader->add_action('init', $plugin_admin, 'display_ads_txt');
		$this->loader->add_action('admin_init', $plugin_admin, 'admin_init');
		$this->loader->add_action('admin_menu', $plugin_admin, 'menu_pages');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('admin_notices', $plugin_admin, 'admin_notices');
		
		/* Cron Job */
		$this->loader->add_action(NX_ADSTXT_CRON, $plugin_admin, 'cron_job');
		

		/* Settings Link */
		$plugin_folder = basename(plugin_dir_path(dirname( __FILE__ )));
		$filter_name = $plugin_folder . '/nx-adstxt.php';
		$this->loader->add_filter("plugin_action_links_".$filter_name, $plugin_admin, 'plugin_add_settings_link');
	}

	public function run() {
		$this->loader->run();
	}
}

NX_Adstxt::$nx_adstxt_path = $_SERVER['DOCUMENT_ROOT']. "/ads.txt";
NX_Adstxt::$nx_adstxt_backup_path = $_SERVER['DOCUMENT_ROOT']. "/ads.txt.bak";