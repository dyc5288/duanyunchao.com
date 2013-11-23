<?php
/*
Plugin Name: WP-T-Wap
Plugin URI: http://www.tanggaowei.com/2008/01/04/7.html
Description: Browse and manage your WordPress's blog on a mobile phone with web explorer. New Features:append post;
Version: 1.13.2
Author: TangGaowei
Author URI: http://www.tanggaowei.com
*/
class T_Wap
{
    var $wap_folder;

    function T_Wap()
    {
        $this->wap_folder = 'wap';

        // 拷贝文件
        register_activation_hook(__FILE__, array(&$this, 'init'));

        // 添加管理界面
        add_action('admin_menu', array(&$this, 'add_options_page'));
    }

    // 插件启动时执行
    function init(){
        // 自动拷贝文件到站点根目录
        
        $this->copyfile('append-form-advanced.php');
        $this->copyfile('comment.php');
        $this->copyfile('comments.php');
        $this->copyfile('edit.php');
        $this->copyfile('edit-comments.php');
        $this->copyfile('edit-form-advanced.php');
        $this->copyfile('edit-post-rows.php');
        $this->copyfile('functions.php');
        $this->copyfile('index.php');
        $this->copyfile('index-wap.php');
        $this->copyfile('index-wap2.php');
        $this->copyfile('login.php');
        $this->copyfile('post.php');
        //$this->copyfile('wap-config.php');
        $this->copy_config_file();
        $this->copyfile('wap-settings.php');
        $this->copyfile('wap-zh_CN.mo');
        $this->copyfile('wap-comments-post.php');
        $this->copyfile('writer.php');
        $this->copyfile('wap.css');
        
        
        update_option('wap_show_detail', 'yes');
        update_option('wap_show_related_posts', 'yes');
        update_option('wap_show_hot_posts', 'yes');
        update_option('wap_show_last_comments', 'yes');
        update_option('wap_sitetitle', get_bloginfo('name'));
        
    }

    function get_home_path() {
        $home = get_option( 'home' );

        if ( $home != '' && $home != get_option( 'siteurl' ) ) {
            $home_path = parse_url( $home );
            $home_path = $home_path['path'];

            $file_self = dirname($_SERVER["REQUEST_URI"]);
            $file_self = str_replace('\\', '/', $file_self);
            $file_self = str_replace('//', '/', $file_self);

            $file_self_full = realpath(".");
            $file_self_full = str_replace('\\', '/', $file_self_full);
            $file_self_full = str_replace('//', '/', $file_self_full);

            $root = str_replace( $file_self, '', $file_self_full );

            $home_path = trailingslashit( $root . $home_path );
        } else {
            $home_path = ABSPATH;
        }

        $home_path = str_replace('\\', '/', $home_path);
        $home_path = str_replace('//', '/', $home_path);

        return $home_path;
    }

    function copyfile($filename){
        $home_path = $this->get_home_path() . $this->wap_folder .'/';

        // 如果文件夹不存在，则创建文件夹
        if(!file_exists($home_path)){
            mkdir($home_path);
        }

        $file = dirname(__FILE__) . '/wap/' . $filename;
        $file = str_replace ( '\\','/',$file );
        $newfile = $home_path . $filename;

        if (!copy($file, $newfile)) {
            echo "failed to copy $file...\n";
            return;
        } 

        /*
        if(($dataFile = fopen($newfile,'w')) === FALSE)
        {
            echo "failed to fopen $file...\n";
            return;
        }

        $buffer = '<?php require_once( "' . $file . '"); ?>';

        fwrite($dataFile,$buffer);
        fclose($dataFile);
        */
    }
    
    function copy_config_file()
    {
        $home_path = $this->get_home_path() . $this->wap_folder .'/';

        $wap_plugin_root = dirname ( __FILE__ ) .'/';
        $wap_plugin_root = str_replace("\\", "/", $wap_plugin_root);
        $wp_path = '..';

        //if ( defined('ABSPATH') )
        //{
        //    $wp_path = ABSPATH;
        //}
        //else
        //{
            $wp_path = str_replace("/wp-content/plugins/wp-t-wap", "", $wap_plugin_root);
        //}

        $wp_path = str_replace("\\", "/", $wp_path);

        $filename = $wap_plugin_root . "wap/wap-config.php";
        if ( file_exists ( $filename ) )
        {
            $dataFile = fopen( $filename, "r" );
            $buffer = '';
            if ( $dataFile )
            {                    
                $buffer = '';
                while (!feof($dataFile)) 
                {
                   $buffer = $buffer . fgets($dataFile, 4096);
                   
                }
                
                fclose($dataFile);
                
                $buffer = str_replace("../", $wp_path, $buffer);
                
                //echo $buffer;
                $newfile = $home_path . 'wap-config.php';               
                $dataFile = fopen( $newfile, "w" );
                echo $newfile;
                fwrite($dataFile,$buffer);
                fclose($dataFile);
            }
        }
    }
    
    
    /* 在插件管理页面中显示你的插件菜单 */
    function add_options_page(){
        if (function_exists('add_options_page')) {
            add_options_page('WP-T-WAP','WP-T-WAP',8,'WP-T-WAP',array(&$this, 'mainpage'));
        }
    }

    /* 显示插件管理页面时即调用此函数 */
    function mainpage(){
        //require_once('wap-settings.php'); 

        /* 更新选项 */
        if($_POST['action']=="update"){
            if ( $_POST['wap_show_detail'] == "yes" )
            {
                update_option('wap_show_detail', "yes");
            }
            else
            {
                update_option('wap_show_detail', "no");
            }
            update_option('wap_show_related_posts',$_POST['wap_show_related_posts']);
            update_option('wap_show_hot_posts',$_POST['wap_show_hot_posts']);
            update_option('wap_show_last_comments',$_POST['wap_show_last_comments']);
			update_option('wap_wml_11',$_POST['wap_wml_11']);
            update_option('wap_sitetitle', $_POST['wap_sitetitle']);
            update_option('wap_copyright', $_POST['wap_copyright']);
        }

        ?>
        <!-- 添加wordpress默认的样式表 -->
        <div class="wrap">
        
        <!-- 标题 -->
        <h2><?php _e('WP-T-WAP Setup','wap') ?></h2>

        <!-- 表单 -->
        <form name="myform" method="post" action="options-general.php?page=WP-T-WAP">

        <!-- 记录表单是否提交 -->
        <input type="hidden" name="wap_stage" value="update" />

        <!-- 用于检验数据 -->

        <div id="msgall">
            <!--<fieldset class="options">-->
            <b><?php _e('Display','wap') ?></b>
                <div style="padding-left:40px; padding-top:10px;">
                    <?php if( get_option("wap_show_detail") == 'yes' ){ ?>
                        <input type=checkbox name="wap_show_detail" value="yes" checked>
                    <?php }else{ ?>
                        <input type=checkbox name="wap_show_detail" value="yes">
                    <?php } ?>
                    <?php _e('Display HTML','wap') ?>
                </div>
                <div style="padding-left:40px; padding-top:10px;">
                    <?php if( get_option("wap_show_related_posts") == 'yes' ){ ?>
                        <input type=checkbox name="wap_show_related_posts" value="yes" checked>
                    <?php }else{ ?>
                        <input type=checkbox name="wap_show_related_posts" value="yes">
                    <?php } ?>
                    <?php _e('Display related posts','wap') ?>
                </div>
                <div style="padding-left:40px; padding-top:10px;">
                    <?php if( get_option("wap_show_hot_posts") == 'yes' ){ ?>
                        <input type=checkbox name="wap_show_hot_posts" value="yes" checked>
                    <?php }else{ ?>
                        <input type=checkbox name="wap_show_hot_posts" value="yes">
                    <?php } ?>
                    <?php _e('Display hot posts','wap') ?>
                </div>
                <div style="padding-left:40px; padding-top:10px;">
                    <?php if( get_option("wap_show_last_comments") == 'yes' ){ ?>
                        <input type=checkbox name="wap_show_last_comments" value="yes" checked>
                    <?php }else{ ?>
                        <input type=checkbox name="wap_show_last_comments" value="yes">
                    <?php } ?>
                    <?php _e('Display last comments','wap') ?>
                </div>
				<div style="padding-left:40px; padding-top:10px;">
                    <?php if( get_option("wap_wml_11") == 'yes' ){ ?>
                        <input type=checkbox name="wap_wml_11" value="yes" checked>
                    <?php }else{ ?>
                        <input type=checkbox name="wap_wml_11" value="yes">
                    <?php } ?>
                    <?php _e('Use WML 1.1','wap') ?>
                </div>
            <!--</fieldset>-->

            <!--<fieldset class="options">-->
                <b><?php _e('Wap Site Title','wap') ?></b>
                <div style="padding-left:40px; padding-top:10px;">
                <input name="wap_sitetitle" value="<?php 
                                                        if ( get_option("wap_sitetitle") != '' ){
                                                            echo str_replace('\\','',get_option("wap_sitetitle"));
                                                        }
                                                        else{
                                                            echo str_replace('\\','',get_bloginfo('name'));
                                                        }
                                                    ?>" style="width:400px;">
                </div>                
            <!--</fieldset>-->

            <!--<fieldset class="options">-->
            <b><?php _e('Copyright Information','wap') ?></b>
                <div style="padding-left:40px; padding-top:10px;">
                <input name="wap_copyright" value="<?php 
                                                        if ( get_option("wap_copyright") != '' ){
                                                            echo get_option("wap_copyright");
                                                        }
                                                        else{
                                                            echo '&copy; 2007 tanggaowei.com';    
                                                        }
                                                    ?>" style="width:400px;">
                </div>
            <!--</fieldset>-->

            <!-- 设置哪些选项需要更新 -->
            <input type="hidden" name="page_options" value="wap_show_detail,wap_show_related_posts,wap_show_hot_posts,wap_show_last_comments,wap_sitetitle,wap_copyright,wap_wml_11" />
        </div>	

        <p class="submit">
            <!-- 必须，设置表单提交为“更新”操作 -->
            <input type="hidden" name="action" value="update" />
            
            <input type="submit" value="<?php _e('Update WAP Options  &raquo;') ?>" name="Update WAP Options"/>
        </p>
        </form>
        <?php
    }

}

// 实例化插件
$t_wap = new T_Wap;
//echo $t_wap->copy_config_file();
?>