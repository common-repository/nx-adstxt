<tr <?php echo empty($name) ? 'data-init' : ''; ?>>

    <td>
        <label>
            <input 
                name="nx_adstxt[urls][<?php echo $name ?>][url]"
                type="url"
                data-url 
                data-field="nx_adstxt[urls][%fieldname%][url]"
                placeholder="<?php _e('URL to ads.txt', 'nx-adstxt') ?>"
                pattern="https?://.+"
                value="<?php echo $data ?>" 
                autocomplete="off"
                aria-required="true" 
                required 
            />
        </label>
    </td>

    <td>
        <span title="<?php _e('Remove entry', 'nx-adstxt') ?>" class="dashicons btn-remove dashicons-no-alt"></span>
    </td>
</tr>