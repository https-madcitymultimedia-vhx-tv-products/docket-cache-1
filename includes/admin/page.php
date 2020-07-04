<?php
\defined('ABSPATH') || exit;

$status = $this->get_status();
$status_text = $this->status_code[$status];
$is_debug = (\defined('DOCKET_CACHE_DEBUG') && DOCKET_CACHE_DEBUG && \defined('DOCKET_CACHE_DEBUG_FILE'));

$do_preload = false;
if (1 === $status && isset($this->token)) {
    switch ($this->token) {
        case 'docket-cache-flushed':
            wp_cache_flush();
            $do_preload = true;
        break;
        case 'docket-cache-enabled':
            $do_preload = true;
        break;
    }
}

if (is_multisite() && is_network_admin()) {
    settings_errors('general');
}
?>
<div class="wrap" id="docket-cache">
    <h1><?php _e('Docket Object Cache', 'docket-cache'); ?></h1>
    <div class="section overview">
        <h2 class="title"><?php _e('Overview', 'docket-cache'); ?></h2>

        <table class="form-table">
            <tbody>
                <tr>
                    <th><?php _e('Status', 'docket-cache'); ?></th>
                    <td><code><?php echo $status_text; ?></code></td>
                </tr>

                <tr>
                    <th><?php _e('OPCache', 'docket-cache'); ?></th>
                    <td><code><?php echo $this->status_code[$this->get_opcache_status()]; ?></code></td>
                </tr>

                <tr>
                    <th><?php _e('Memory', 'docket-cache'); ?></th>
                    <td><code><?php echo $this->get_mem_size(); ?></code></td>
                </tr>

                <?php if (1 === $status): ?>
                <tr>
                    <th><?php _e('Cache Size', 'docket-cache'); ?></th>
                    <td><code><?php echo $this->get_dirsize(); ?></code></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <p class="submit">

            <?php if (!$this->has_dropin()) : ?>
                <a href="<?php echo $this->action_query('enable-cache'); ?>" class="button button-primary button-large"><?php _e('Enable Object Cache', 'docket-cache'); ?></a>
            <?php elseif ($this->validate_dropin()) : ?>
                <a href="<?php echo $this->action_query('flush-cache'); ?>" class="button button-primary button-large"><?php _e('Flush Cache', 'docket-cache'); ?></a>&nbsp;&nbsp;
                <a href="<?php echo $this->action_query('disable-cache'); ?>" class="button button-secondary button-large"><?php _e('Disable Object Cache', 'docket-cache'); ?></a>
           <?php endif; ?>

        </p>
    </div>

<?php if ($is_debug):?>
    <div class="section log">
        <h2 class="title"><?php _e('Debug Log', 'docket-cache'); ?></h2>
        <?php
            $output = $this->tail_log(100);
            if (empty($output)) {
                echo '<p><code>empty</code></p>';
            } else {
                echo '<textarea id="log" class="code" readonly="readonly" rows="10">'.implode("\n", array_reverse($output, true)).'</textarea>';
            }
        ?>
        <a href="#" onclick="window.location.assign(window.location.href); return false;" class="button button-primary button-large"><?php _e('Refresh', 'docket-cache'); ?></a>&nbsp;
        <?php if (!empty($output)): ?>
            <a href="<?php echo get_home_url(null, '/wp-content/object-cache.log').'?'.time(); ?>" target="_blank" rel="noopener" class="button button-primary button-large">Download</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
</div>

<?php if ($do_preload): ?>
<script>
jQuery(document).ready(function() {
    wp.ajax.post( "docket_preload", {} ).done(function(response) {
    console.log(response);
  });
});
</script>
<?php endif; ?>