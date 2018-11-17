<?php
   /*
       * Plugin Name:       Image Overlay
       * Description:       Custom plugin to generate images overlayed with text
       * Version:           1.2.1
       * Author:            Chamaabe
       * Author URI:        https://www.fiverr.com/chamaabe
       * Text Domain:       imageoverlay
       * License:           GPL-2.0+
       * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
       * GitHub Plugin URI: https://www.fiverr.com/chamaabe
   */
   
   
   add_action('admin_menu', 'image_overlay_menu');
   
   function image_overlay_menu(){
     add_menu_page('Image Overlay Generator', 'Generate Overlay', 'manage_options', 'generate-image-overlay', 'image_overlay_admin_page', '
     dashicons-camera');
   
   }
   
   function image_overlay_admin_page() {
   
     if (!current_user_can('manage_options'))  {
       wp_die( __('You do not have sufficient pilchards to access this page.')    );
     }
   
     // Start building the page
   
     echo '<div class="wrap">'; ?>
<div class="form-group inside">
<h3>
   Image Generator Settings
</h3>
<p>
   Make sure, you have included an image in plugin directory.
	You can <a href="https://www.fiverr.com/chamaabe" target="_blank">contact me</a> for support
   <br>
   <br>
   Insert the Person's Name below
</p>
<?php echo '<form action="admin.php?page=generate-image-overlay" method="post">'; ?>
<table class="form-table">
   <tbody>
      <tr>
         <td scope="row">
            <label>Persons Name</label>
         </td>
         <td>
            <input name="person_name"
               id="person_name"
               class="regular-text"
               type="text"
               value=""/>
         </td>
      </tr>
   </tbody>
</table>
<?php
   // Check whether the button has been pressed AND also check the nonce
   if (isset($_POST['overlay_button']) && check_admin_referer('overlay_button_clicked')) {
     // the button has been pressed AND we've passed the security check
     image_overlay_action();
   }
   
   wp_nonce_field('overlay_button_clicked');
   echo '<input type="hidden" value="true" name="overlay_button" />';
   submit_button('Generate Image');
   echo '</form>';
   
   echo '</div>';
   
   }
   
   function image_overlay_action()
   {
     //header('Content-type: image/jpeg');
   $path = WP_PLUGIN_DIR;
   $temp_path3 = plugin_dir_url( __FILE__ );
   echo '<script>console.log("'.$temp_path3.'");</script>';
   $temp_path0 = $path.'/plug/image.jpg';
   echo '<script>console.log("'.$temp_path0.'");</script>';
     $jpg_image = imagecreatefromjpeg($temp_path0);
   
     // Allocate A Color For The Text
     $white = imagecolorallocate($jpg_image, 0, 0, 0);
   
     // Set Path to Font File
     
   $temp_path1 = $path.'/plug/Roboto-Black.ttf';
     $font_path = $temp_path1;
   echo '<script>console.log("'.$font_path.'");</script>';
   
   
     // Set Text to Be Printed On Image
     $text = $_POST['person_name'];
   
     // Print Text On Image
     if (function_exists('imagettftext')) {
      imagettftext($jpg_image, 25, 0, 75, 300, $white, $font_path, $text);
     // Send Image to Browser
     
     Imagejpeg($jpg_image, $path.'/plug/imagename.jpg', 100);
     ob_start (); 
   
     imagejpeg ($jpg_image);
     $image_data = ob_get_contents (); 
   
     ob_end_clean (); 
   
     $image_data_base64 = base64_encode ($image_data);
     $image_generated = "<img src='data:image/jpeg;base64," . $image_data_base64 . "'>";
   
     // Clear Memory
     imagedestroy($jpg_image);
   
   
     $my_post = array(
         'post_title' => $_POST['person_name'],
         //'post_date' => $_SESSION['cal_startdate'],
         'post_content' => $image_generated,
         'post_status' => 'publish',
         'post_type' => 'post',
     );
   
     $the_post_id = wp_insert_post( $my_post );
     $post_url = get_permalink($the_post_id);
   
     echo '<div id="message" class="updated fade"><p>'
     .'New Post Created <a href="'.$post_url.'"> Open </a></p></div>';
   } else {
      echo '<script>console.log("fudge!")</script>';
   echo '<div id="message" class="updated fade"><p>'
     .'Failed to make post</p></div>';
   }
     
   }
   
   ?>