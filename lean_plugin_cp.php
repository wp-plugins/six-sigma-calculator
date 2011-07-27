<?php
/*
Plugin Name: Six Sigma Calculator Widget
Plugin URI: http://www.leansixsigmatraining.org
Description: A Six Sigma Calculator Widget (Process capability calculator)
Version: 1.0   
Author: Junglee Labs
Author URI: http://www.jungleelabs.com
*/
?>
<?
add_action("widgets_init", array('SixSigma_Calculator_Widget', 'register'));
register_activation_hook( __FILE__, array('SixSigma_Calculator_Widget', 'activate'));
register_deactivation_hook( __FILE__, array('SixSigma_Calculator_Widget', 'deactivate'));


add_action('init', 'add_Cp_javascript');

function add_Cp_javascript()
{
   if ( is_admin() )
   {
      wp_enqueue_script ('calc-colorpicker', WP_PLUGIN_URL . '/Cp-calculator-widget/js/plugins/colorpicker/colorpicker.js', array('jquery'));
      wp_enqueue_script ('calcs', WP_PLUGIN_URL . '/Cp-calculator-widget/js/calcs.js', array('jquery'));
      wp_enqueue_style('colorpicker-styles', WP_PLUGIN_URL . '/Cp-calculator-widget/js/plugins/colorpicker/css/colorpicker.css');
      wp_enqueue_style('calc-styles', WP_PLUGIN_URL . '/Cp-calculator-widget/css/calcs.css');
   }
   else
   {
      wp_enqueue_script ('jquery');
   }
}

class SixSigma_Calculator_Widget {
   function activate()
   {
      $data = array( 'title' => 'Six Sigma Calculator' ,'standard' => 'standard', 'allowLink'=>'yes');
      if ( !get_option('SixSigma_Calculator_Widget')){
         add_option('SixSigma_Calculator_Widget' , $data);
      } else {
        update_option('SixSigma_Calculator_Widget' , $data);
      }
   }

   function deactivate(){
      delete_option('SixSigma_Calculator_Widget');
   }

   function control(){
      $data = get_option('SixSigma_Calculator_Widget');
   ?>
     <p><label>Title<input name="title" type="text" value="<?php echo $data['title']; ?>" /></label></p>
     
     
          
     </label></p>
     <div class="colorHolder"><div class="colorSelector" id="bgColorSelector"><div id="widgetBackground" style="background-color: <?php echo $data['bgcolor']; ?>"></div></div> <span>Widget Start Background Color</span> </div>
     <div class="colorHolder"><div class="colorSelector" id="bgEndColorSelector"><div id="widgetEndBackground" style="background-color: <?php echo $data['bgendcolor']; ?>"></div></div> <span>Widget End Background Color</span> </div>
     <div class="colorHolder"><div class="colorSelector" id="textColorSelector"><div id="widgetText" style="background-color: <?php echo $data['textcolor']; ?>"></div></div> <span>Widget Text Color</span></div>
     <input name="bgcolor" type="hidden" value="<?php echo $data['bgcolor']; ?>" /></label>
     <input name="bgendcolor" type="hidden" value="<?php echo $data['bgendcolor']; ?>" />
     <input name="textcolor" type="hidden" value="<?php echo $data['textcolor']; ?>" />
     <div id="CpCalcDemo" style="color: <?php echo $data['textcolor']; ?>; border: 1px solid rgba(21, 11, 11, 0.199219); padding: 5px; width: 200px; -moz-border-radius: 12px; -webkit-border-radius: 12px; border-radius: 12px; -moz-box-shadow: 0px 0px 4px #ffffff; -webkit-box-shadow: 0px 0px 4px #ffffff; box-shadow: 0px 0px 4px #ffffff; background-color: <?php echo $data['bgcolor']; ?>; background-image: -moz-linear-gradient(top, <?php echo $data['bgcolor']; ?>, <?php echo $data['bgendcolor']; ?>); background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0, <?php echo $data['bgcolor']; ?>),color-stop(1, <?php echo $data['bgendcolor']; ?>)); filter:  progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo $data['bgcolor']; ?>', EndColorStr='<?php echo $data['bgendcolor']; ?>'); -ms-filter: \"progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo $data['bgcolor']; ?>', EndColorStr='<?php echo $data['bgendcolor']; ?>')\"; text-shadow: 1px 1px 3px #888;">
        Widget Color Preview
     </div>
     <p><label>Please give us credit by allowing link back to leansixsigmatraining.org<input name="allowLink" type="checkbox" value="yes" <?= $data['allowLink'] == 'yes' ? "checked" : "" ?>/></label></p>
     
     <?php
      if (isset($_POST['title'])){
       $data['title'] = attribute_escape($_POST['title']);
       $data['standard'] = attribute_escape($_POST['standard']);
       $data['textcolor'] = attribute_escape($_POST['textcolor']);
       $data['bgcolor'] = attribute_escape($_POST['bgcolor']);
       $data['bgendcolor'] = attribute_escape($_POST['bgendcolor']);
       $data['allowLink'] = attribute_escape($_POST['allowLink']);
       update_option('SixSigma_Calculator_Widget', $data);
     }
  }
  function widget($args){
           extract( $args );
           $options = get_option('SixSigma_Calculator_Widget', $data);
           extract($options);
           ?>
                 <?php echo $before_widget; ?>
      <script>
         jQuery(document).ready(function($) {
            $("#Calculate").click(function()
             {
               var Cp = 0;
               var LSL = 0;
               var USL = 0;
               var SD = 0;
               var Pp = 0;
               var SL = 0;
               
              LSL = $("[name='LSL']").val();
              USL = $("[name='USL']").val();
              SD = $("[name='SD']").val();

            
	       Cp = (USL-LSL)/(6*SD);
	       Cp = Math.round(Cp * 10) / 10;
	       Pp = (USL-LSL)/(3*SD);
	       Pp = Math.round(Pp * 10) / 10;
	       SL = 3*Cp;
	       SL = Math.round(SL * 10) / 10;
	       
	     
	     


              
               if(isNaN(Cp))
                  $('#Cpresults').hide().text('Please enter a valid LSL and USL.').fadeIn();
               else
                  $('#Cpresults').hide().text('Your Cp is ' + Cp + ". Pp is " + Pp + ". Your process sigma level is " + SL ).fadeIn();
                  

             });
          });
      </script>
      <table style="color: <?= $textcolor ? $textcolor : "#ffffff" ?>; padding: 5px; margin: 0; width: 200px; font-size: 9pt; -moz-border-radius: 12px; -webkit-border-radius: 12px; border-radius: 12px; -moz-box-shadow: 0px 0px 4px #ffffff; -webkit-box-shadow: 0px 0px 4px #ffffff; box-shadow: 0px 0px 4px #ffffff; background-color: <?php echo $bgcolor ? $bgcolor : '#3399CC' ?>; background-image: -moz-linear-gradient(top, <?php echo $bgcolor ? $bgcolor : '#3399CC' ?>, <?php echo $bgendcolor ? $bgendcolor : '#1C5992' ?>); background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0, <?php echo $bgcolor ? $bgcolor : '#3399CC' ?>),color-stop(1, <?php echo $bgendcolor ? $bgendcolor : '#1C5992' ?>)); filter:  progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo $bgcolor ? $bgcolor : '#3399CC' ?>', EndColorStr='<?php echo $bgendcolor ? $bgendcolor : '#1C5992' ?>'); -ms-filter: \"progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo $bgcolor ? $bgcolor : '#3399CC' ?>', EndColorStr='<?php echo $bgendcolor ? $bgendcolor : '#1C5992' ?>')\"; text-shadow: 1px 1px 3px #888;" id="CpTable">
         <tbody>
            <tr><td colspan="2" align="center"><h4 style="color: <?= $textcolor ? $textcolor : "#ffffff" ?>; margin: 0; padding: 3px;"><?= $title ?></h4></td></tr>
         
            <tr><td style="padding: 3 px;"> Cp and Pp: </td></tr>
            <tr><td style="padding: 3px;">LSL: </td><td><input name="LSL" style="width: 77px;"> </td></tr>
            <tr><td style="padding: 3px;">USL: </td><td><input name="USL" style="width: 77px;"> </td></tr>
            <tr><td style="padding: 3px;">SD: </td><td><input name="SD" style="width: 77px;"> </td></tr>
        
 
       
         <tr><td colspan="2" style="padding: 3px;" align="center"><input id="Calculate" type="button" value="Get Cp!" style="cursor: pointer; border: 0; padding: 5px; color:<?= $bgcolor ? $bgcolor : "#1C5992" ?>; background-color: <?= $textcolor ? $textcolor  : "#ffffff" ?>; font-USL: bold;"></td></tr>
         <tr><td colspan="2" style="padding: 3px;"><div id="Cpresults" style="font-USL: bold;"></div></td></tr>
         <? if($allowLink == 'yes') { ?>
            <tr><td colspan="2" align="center"><a href="http://www.leansixsigmatraining.org/" style="text-decoration: underline; color: <?= $textcolor ? $textcolor : "#ffffff" ?>;">Six Sigma Calculator</a></td></tr>
        <? } ?>
      </tbody></table>
                 <?php echo $after_widget; ?>
           <?php
	}

  function register(){
    register_sidebar_widget('Six Sigma Calculator', array('SixSigma_Calculator_Widget', 'widget'));
    register_widget_control('Six Sigma Calculator', array('SixSigma_Calculator_Widget', 'control'));
  }
}
?>