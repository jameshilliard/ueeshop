<?php
include('../../../inc/site_config.php');
include('../../../inc/set/ext_var.php');
include('../../../inc/fun/mysql.php');
include('../../../inc/function.php');
include('../../../inc/manage/config.php');
include('../../../inc/manage/do_check.php');

check_permit('order_price_trend');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<settings>
   <data_type>csv</data_type>
   <legend>
      <enabled>false</enabled>
   </legend>        
    
   <plugins>
    <!-- value indicator plugin is suitable for line chart, column chart & xy chart -->
    <plugin file="plugins/value_indicator.swf" position="above">      <!-- file should be located in "path" folder. position can be "behind" or "above". "behind" means that the plugin will be loaded behind graphs -->
      <chart_type>line</chart_type>                                             <!-- [line] (line / column / xy) this plugin can be used with line or with column chart -->
      <axis>left</axis>                                                         <!-- [left] (left / right / x / y) if used with line chat use left or right, if used with xy chart, use x or y -->
      <line_color>#BBBB00</line_color>                                          <!-- [#BBBB00] (hex color code) -->
      <line_alpha></line_alpha>                                                 <!-- [100] (0 - 100) -->
      <text_color>#000000</text_color>                                          <!-- [settings.text_color] -->
      <text_size>12</text_size>                                                 <!-- [settings.tex_size] -->
      <precision>2</precision>                                                  <!-- [0] (Number) how many numbers after comma should be shown -->
    </plugin>
  </plugins>
  
  <labels>                                                    <!-- LABELS -->
    <label  lid="0">
      <x>0</x>                                               <!-- [0] (Number / Number% / !Number) -->
      <y>10</y>                                               <!-- [0] (Number / Number% / !Number) -->
      <rotate></rotate>                                       <!-- [false] (true / false) -->
      <width></width>                                         <!-- [] (Number / Number%) if empty, will stretch from left to right untill label fits -->
      <align>center</align>                                         <!-- [left] (left / center / right) -->  
      <text_color></text_color>                               <!-- [text_color] (hex color code) button text color -->
      <text_size>12</text_size>                               <!-- [text_size](Number) button text size -->
      <text>                                                  <!-- [] (text) html tags may be used (supports <b>, <i>, <u>, <font>, <a href="">, <br/>. Enter text between []: <![CDATA[your <b>bold</b> and <i>italic</i> text]]>-->
        <![CDATA[<b><?=get_lang('count.order_price_trend');?></b>]]>
      </text>        
    </label>
    
    <label lid="1">
      <x>250</x>                                               
      <y>380</y>                                              
      <rotate></rotate>                                       
      <width></width>                                         
      <align>left</align>                                       
      <text_color></text_color>                               
      <text_size></text_size>                                 
      <text>                                                  
        <![CDATA[]]>
      </text>        
    </label>  
        
  </labels>

  <export_as_image>                                           <!-- export_as_image feature works only on a web server -->
    <file></file>                                             <!-- [] (filename) if you set filename here, context menu (then user right clicks on flash movie) "Export as image" will appear. This will allow user to export chart as an image. Collected image data will be posted to this file name (use ampie/export.php or ampie/export.aspx) -->
    <target>iframe_data_xxzs</target>                                         <!-- [] (_blank, _top ...) target of a window in which export file must be called -->
    <x></x>                                                   <!-- [0] (Number / Number% / !Number) x position of "Collecting data" text -->
    <y></y>                                                   <!-- [] (Number / Number% / !Number) y position of "Collecting data" text. If not set, will be aligned to the bottom of flash movie -->
    <color></color>                                           <!-- [#BBBB00] (hex color code) background color of "Collecting data" text -->
    <alpha></alpha>                                           <!-- [0] (0 - 100) background alpha -->
    <text_color></text_color>                                 <!-- [text_color] (hex color code) -->
    <text_size></text_size>                                   <!-- [text_size] (Number) -->
  </export_as_image>
</settings>