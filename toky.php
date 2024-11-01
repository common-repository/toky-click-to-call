<?php

/*

Plugin Name: Toky Click to Call Widget
Plugin URL: https://toky.co/
Description: Add Toky Click to Call Widget so you can start receiving visitors calls with a single click
Version: 1.0
Author: Toky Inc.
Author URI: https://toky.co/

*/

define('PLUGIN_NAME', 'Toky Click To Call');

$toky_plugin  = esc_html__(PLUGIN_NAME, 'toky-click-to-call');
$toky_options = get_option('toky_options');
$toky_path    = plugin_basename(__FILE__);
$toky_homeurl = 'https://toky.co';


function toky_add_options_page() {
    global $toky_plugin;

    $icon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAACXBIWXMAAAsTAAALEwEAmpwYAAABWWlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS40LjAiPgogICA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogICAgICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgICAgICAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyI+CiAgICAgICAgIDx0aWZmOk9yaWVudGF0aW9uPjE8L3RpZmY6T3JpZW50YXRpb24+CiAgICAgIDwvcmRmOkRlc2NyaXB0aW9uPgogICA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgpMwidZAAACg0lEQVQ4EXVTPWgUQRR+b2Z37/JjspeYSgRLCUaxVtBUokFCErCVWKWzVbAQ7MQmpV0EqwPPiD9ImlNTithoISEIIrHxbjd/rrfZnef35i5GBR/Mzs68933vd5hU6hKZILnOROeIOMKNxRJiLKEq1l7v3xFLR4heu6K2SFc4Z3rYGjJ95hXH8WnKC+BB40qiCDxKk6a7VBkYIIODOFxAooAkTd+7zJ03tp/vcg3gzTSjbKekH9s5FXskW+k6tZKpcqY2yD+3Fz042+lQtluorWI81jaSBF5jEkFkCNYGhsqiVdriJE2PbXiP9da4De1HqDWlrjBCFUkNYt4HI3Zy1DeoZose/HSj31sP0lfwfyMbqo1SKBg7xyDwnlWh90x7HTKGVv3x3afc75dGt7BnxGrub/QDjEjgi+bJoBIxSsCOFdCVZ2kt6Lhx4EaRmsJ6zqCGvwPK/aYFEUkgw4q2E6eu2VzawrwK78PoTjf8LjW+AoJuSN0vowZRlVxBJ9QGdTpOw7H+5uSc9vDAey8ZA+QahRUoGPHBADOACk0pCroPhJHADFQojBBtzx1RoRhkvsZo41WK4yUMjLaoADrUYpUG3ocOrZsk/axkkALpH0OdMJUcUowsk9a8KWdrDyTdvOFbNDAUwjDXNGxZ3qNJLlwoZ9xs7SiiukVjAFX7YCMlMDfLucNL2k8Qs9Bye8I6voPQpv3Y6pxkOwtubuQ+SPFevh+xhi+QtZtl4N7S5ZEvHuuVTbSzJ0GjfdY20hX7vCN2RcQ+bl/c1/2110Vfyh9Sr1u8yt+XwZNk0jbab+zLUuxyMu8tX6xVqNkM6Dbm5b+iJErWE/somTGN9oI/arr/yC9hHhrIQNpRlAAAAABJRU5ErkJggg==';
    if ( floatval(get_bloginfo("version")) < 3.8 ) 
        $icon = plugins_url('/images/toky-icon.png', __FILE__);

    $page_hook_suffix = add_menu_page(PLUGIN_NAME, 'Toky', 'manage_options', 'toky-settings', toky_render_settings_page, $icon);
    
    toky_plugin_settings();
    toky_enqueue_styles();
}

function toky_render_widget() { 

    if ( trim(esc_attr( get_option('custom_code')) ) != '' ) {
        echo get_option('custom_code');
        return;
    }

    $show = esc_attr( get_option('visibility') ) == 'show';
    if ( !$show )
        return;

    $toky_username  = esc_attr( get_option('username') );
    if ( trim($toky_username) == "" )
        return;

    $toky_postion   = "";
    $toky_text      = esc_attr( get_option('text') );    

    if ( esc_attr( get_option('position') ) == 'L' )
        $toky_postion='"$position":"left",';
    else 
        $toky_postion='';

    if ( esc_attr( get_option('color') ) != 'green' )
        $toky_color=',"$color":"'.esc_attr( get_option('color') ).'",';
    else
        $toky_color='';
    
    echo '<script>(function(v,p){
            var s=document.createElement("script");
            s.src="https://app.toky.co/resources/widgets/toky-widget.js?v="+v;
            s.onload=function(){Toky.load(p);};
            document.head.appendChild(s); })("8dea735", {"$username":"'.$toky_username.'",'.$toky_sound.'"$bubble":false,'.$toky_postion.'"$text":"'.$toky_text.'"'.$toky_color.'})
        </script>';
}

function toky_plugin_settings() {
    register_setting( 'toky-settings-group', 'username' );
    register_setting( 'toky-settings-group', 'text' );
    register_setting( 'toky-settings-group', 'color' );
    register_setting( 'toky-settings-group', 'position' );
    register_setting( 'toky-settings-group', 'visibility' );
    register_setting( 'toky-settings-group', 'custom_code' );
}

function toky_enqueue_styles() {
    wp_register_style( 'toky-styles',  plugin_dir_url( __FILE__ ) . 'css/toky.css' );
    wp_enqueue_style( 'toky-styles' );
}

if ( is_admin() ) {
    add_action('admin_menu', 'toky_add_options_page');
    add_action('admin_init', 'toky_plugin_settings');
}

add_action('wp_head', 'toky_render_widget');

function toky_render_settings_page() {
    $set_disabled = trim(esc_attr( get_option('custom_code') )) != '' ? 'disabled' : "";
    $set_readonly = trim(esc_attr( get_option('custom_code') )) != '' ? 'readonly="readonly"' : "";

    ?>

<?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ) { ?>
    <div class="notice notice-success is-dismissible">
        <p> Changed saved successfully. </p>
    </div>
<?php } ?>

<style>
#wpwrap {
    background:#353943 ;
}
ul#adminmenu a.wp-has-current-submenu:after, ul#adminmenu>li.current>a.current:after {
    border-right-color: #353943 ;
}
</style>

<div class="toky-wp"> 

    <form method="post" action="options.php" id="toky-settings">

        <?php settings_fields( 'toky-settings-group' ); ?>
        <?php do_settings_sections( 'toky-settings-group' ); ?>

        <input type="hidden" name="page_saved" value="yes">

        <div class="toky-wp-col-left">
            <div class="toky-wp-logo">
                <a href="https://toky.co/en/features/live-calls" target="_blank"><img src="<?php echo plugins_url('/images/toky-logo.png', __FILE__);?>" width="380" height="124" alt="Toky Logo"></a>
            </div>
            
            <div class="toky-wp-desc-signup">
                <p>New to Toky? </p>
                <p><a href="https://toky.co/register" class="toky-wp-btn-blue" target="_blank">CREATE AN ACCOUNT</a></p>
                <p> Or </p>
                <p><a href="http://help.toky.co/how-tos/how-to-use-the-toky-wordpress-plugin" class="toky-wp-btn-blue" target="_blank">LEARN HOW TO USE TOKY</a></p>
            </div>
            
        </div>
        <div class="toky-wp-col-right">
            <div class="toky-wp-container">
                
                <div class="toky-wp-title">
                    <h1>Toky Click To Call</h1>
                </div>

                <div class="toky-wp-form">
                    <div class="toky-wp-row">
                    <div class="toky-wp-title-spam">
                        <h2>ACCOUNT INFORMATION</h2>
                    </div>
                    
                    <div class="toky-wp-form-control">
                        <label>Toky Username</label>
                        <input <?php echo $set_readonly; ?> type="text" name="username" value="<?php echo esc_attr( get_option('username') ); ?>" />
                    </div>

                    <div class="toky-wp-title-spam">
                        <h2>WIDGET CONFIGURATION</h2>
                    </div>
                    
                    <div class="toky-wp-form-control">
                        <label>Button's Text</label>
                        <input <?php echo $set_readonly; ?> type="text" name="text" value="<?php echo esc_attr( get_option('text') ); ?>" />
                    </div>

                    
                    <div class="toky-wp-form-control">
                        
                        <div class="toky-wp-col">
                            <label>Position</label>
                            <select <?php echo $set_disabled; ?> name="position">
                                <option value="L" <?php selected(get_option('position'), "L"); ?>>Left</option>
                                <option value="R" <?php selected(get_option('position'), "R"); ?>>Right</option>
                            </select>
                        </div>

                        
                        <div class="toky-wp-col">
                            <label>Color</label>

                            <select <?php echo $set_disabled; ?> name="color" class="toky-wp-select">
                                <option value="green" <?php selected(get_option('color'), "green"); ?>>Green</option>
                                <option value="blue" <?php selected(get_option('color'), "blue"); ?>>Blue</option>
                                <option value="white" <?php selected(get_option('color'), "white"); ?>>White</option>
                            </select>

                        </div>

                        <div class="toky-wp-col">
                            <label>Visibility</label>
                            <select <?php echo $set_disabled; ?> name="visibility" class="toky-wp-select">
                                <option value="show" <?php selected(get_option('visibility'), "show"); ?>>Show</option>
                                <option value="hide" <?php selected(get_option('visibility'), "hide"); ?>>Hide</option>
                            </select>

                        </div>
                    </div>
                    <div class="toky-wp-clear"></div>
                    </div>
                    
                    <div class="toky-wp-toky-wp-row">
                    <div class="toky-wp-title-spam">
                        <h2>CUSTOM CODE</h2>
                    </div>
                    
                    <div class="toky-wp-form-control">
                        <textarea id="custom_code" class="toky-wp-textarea" name="custom_code" form="toky-settings"><?php echo esc_attr( get_option('custom_code') ); ?></textarea>
                    </div>
                        
                    <!-- <div class="toky-wp-clear"></div>
                    </div> -->
                    
                    <div class="toky-wp-form-control">
                        <?php submit_button(); ?>
                    </div>
                    
                </div>
            </div>
        </div>
    </form>
</div>

<?php }?>
