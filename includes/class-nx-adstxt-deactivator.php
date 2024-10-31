<?php

class NX_Adstxt_Deactivator {

	public static function deactivate() {
		self::unschedule_cron();
		NX_Adstxt::restore_adstxt();
	}

	private static function unschedule_cron() {
        $timestamp = wp_next_scheduled (NX_ADSTXT_CRON);
        wp_unschedule_event ($timestamp, NX_ADSTXT_CRON);
    }
}
