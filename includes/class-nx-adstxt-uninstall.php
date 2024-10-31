<?php

class NX_Adstxt_Uninstaller {

    public static function uninstall_hook() {
    }
    
	public static function uninstall() {
        self::remove_cap();
	}

    private static function remove_cap() {
        $roles = get_editable_roles();

        foreach ($GLOBALS['wp_roles']->role_objects as $key => $role) {
            if (isset($roles[$key]) && $role->has_cap('manage_options')) {
                $role->remove_cap(NX_ADSTXT_CAP_CONFIG);
            }
        }
    }

}
