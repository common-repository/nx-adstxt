<div class="wrap" id="plugin-<?php echo $this->nx_adstxt ?>">
    <form method="post" action="options.php">
        <a href="https://www.mairdumont-netletix.com/" target="_blank" id="logo"></a>
        <h1><?php echo sprintf(__( '%s configuration', 'nx-adstxt'), NX_ADSTXT_FULLTITLE) ?></h1>

        <p>
            <?php _e('Via the plugin both own ads.txt data and ads.txt data from different sources can be deposited via URL link.', 'nx-adstxt'); ?>
        </p>
        

        <div id="nxAdstxtEditor">
            <?php settings_fields( $this->nx_adstxt ); ?>
            <?php do_settings_sections( $this->nx_adstxt); ?>
        </div>
        <div id="nxAdstxtUrls">
            
            <script class="template" type="text/template">
                <?php
                    $name = '';
                    $data = '';
                    include('row-url.php'); 
                ?>
            </script>

            <?php do_settings_sections($this->nx_adstxt. '-append'); ?>

            <table class="widefat striped fixed posts">
                <colgroup>
                    <col width="95%">
                    <col width="50px">
                </colgroup>
                <thead>
                    <tr>
                        <th><?php _e('URL', 'nx-adstxt') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if (isset($this->settings['urls'])) :
                            foreach($this->settings['urls'] as $name => $entry):
                                $data = $entry['url']; 
                                include('row-url.php');
                            endforeach;
                        endif;
                    ?>
                </tbody>
            </table>
            <p>
                <button name="add-url" data-add-url class="button">
                    <span class="dashicons dashicons-plus"></span> <?php _e('Add new URL', 'nx-adstxt') ?>
                </button>
            </p>
        </div>
        <p>
            <?php submit_button(); ?>
        </p>
    </form>
</div>
<script id="nxAdstxtData" type="application/json">
{
    "remove_url" : "<?php _e('Should the URL %name% be removed?', 'nx-adstxt') ?>",
    "entry_invalid": "<?php _e('This entry is invalid', 'nx-adstxt') ?>",
    "type_invalid": "<?php _e('The Type must be either DIRECT or RESELLER', 'nx-adstxt') ?>",
    "domain_invalid": "<?php _e('No valid domain', 'nx-adstxt') ?>"

    
}
</script>