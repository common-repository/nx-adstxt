<?php

class NX_Adstxt_Activator {

	public static function activate() {
		$init_data = array (
			'adstxt' => NX_Adstxt::read_adstxt_data(),
			'urls' => array(),
			'output' => ''
		);

		add_option('nx_adstxt', $init_data);
		set_transient(NX_ADSTXT_ACTIVATED, true, 5);
		self::add_cap();
		self::add_cron();

		NX_Adstxt::backup_adstxt();
	}

	public static function add_cap() {
		$roles = get_editable_roles();

        foreach ($GLOBALS['wp_roles']->role_objects as $key => $role) {
            if (isset($roles[$key]) && $role->has_cap('manage_options')) {
                $role->add_cap(NX_ADSTXT_CAP_CONFIG);
			}
        }
	}

	public static function add_cron() {
		if( !wp_next_scheduled(NX_ADSTXT_CRON) ) {  
			wp_schedule_event( time(), 'twicedaily', NX_ADSTXT_CRON);  
		}
	}
}
