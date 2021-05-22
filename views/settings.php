<?php $settings = get_option('botman_settings'); ?>
<div class="wrap">
    <h2>ChatBot Settings</h2>
    <form method="post" action="options.php">
        <?php settings_fields('botman_settings'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Persistent Cache Engine</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <span>Persistent Cache Engine</span>
                        </legend>
                        <label>
                            <input type="radio" name="botman_settings[cache_engine]" value="file" <?php checked('file', $settings['cache_engine']); ?> /> <span class="cache-engine-text format-i18n">File</span>
                        </label><br>
                        <label>
                            <input type="radio" name="botman_settings[cache_engine]" value="redis" <?php checked('redis', $settings['cache_engine']); ?> /> <span class="cache-engine-text format-i18n">Redis</span>
                        </label>
                    </fieldset>
                </td>
            </tr>
        </table>
        <?php $fileCacheDirectory = get_option('file_cache_directory'); ?>
        <table class="form-table cache-engine-file">
            <tr valign="top">
                <th scope="row">File Cache Directory</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <span>File Cache Directory</span>
                        </legend>
                        <label>
                            <input type="text" name="botman_settings[cache_file][directory]" value="<?php echo $settings['cache_file']['directory']; ?>" class="regular-text ltr" />
                        </label><br>
                    </fieldset>
                </td>
            </tr>
        </table>
        <table class="form-table cache-engine-redis">
            <tr valign="top">
                <th scope="row">Redis Host</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <span>Redis Host</span>
                        </legend>
                        <label>
                            <input type="text" name="botman_settings[cache_redis][host]" value="<?php echo $settings['cache_redis']['host']; ?>" class="regular-text ltr" />
                        </label><br>
                        <legend class="screen-reader-text">
                            <span>Redis Port</span>
                        </legend>
                        <label>
                            <input type="text" name="botman_settings[cache_redis][port]" value="<?php echo $settings['cache_redis']['port']; ?>" class="regular-text ltr" />
                        </label><br>
                        <legend class="screen-reader-text">
                            <span>Redis Auth/Password</span>
                        </legend>
                        <label>
                            <input type="password" name="botman_settings[cache_redis][auth]" value="<?php echo $settings['cache_redis']['auth']; ?>" class="regular-text ltr" />
                        </label><br>
                    </fieldset>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
</div>