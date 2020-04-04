<?php

/*
Plugin Name: My Slide Show
Version : 1.0.0
Description: This plugin provides functionality to create and display slideshows through shortcodes 
Author: Mittala Nandle
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
 exit;
}

/*-------------------------------------------------------------------

INCLUDE SCRIPTS/STYLES FOR FRONTEND

---------------------------------------------------------------------*/

add_action( 'wp_enqueue_scripts', 'mss_front_scripts' );
function mss_front_scripts() {
 	//css, js for slideshow
 	wp_enqueue_style( 'mss-slideshow-style', plugins_url('lib/public/css/mss-public-styles.css', __FILE__) );
 	wp_enqueue_style( 'bootstrap-css', plugins_url('lib/public/css/bootstrap.min.css', __FILE__) );
 	wp_enqueue_script( 'bootstrap-js', plugins_url('lib/public/js/bootstrap.min.js', __FILE__ ), array('jquery'), null, false );
}

/*-------------------------------------------------------------------

INCLUDE SCRIPTS/STYLES FOR 'MYSLIDESHOW' SETTIGNS PAGE 

---------------------------------------------------------------------*/

function mss_include_scripts() {

	//return if not 'MSS setting page'

	if( empty( $_GET['page'] ) || "myslideshow-settings" !== $_GET['page'] ) { return; }

	if ( ! did_action( 'wp_enqueue_media' ) ) {

		wp_enqueue_media();
	}

 	//include upload media script

 	wp_enqueue_script( 'mss-media-upload', plugins_url('lib/admin/js/mss-media-upload.js', __FILE__ ), array('jquery'), null, false );

 	//include css for setting page

 	 wp_enqueue_style( 'mss-admin-style', plugins_url('lib/admin/css/mss-admin-styles.css', __FILE__) );
}

add_action( 'admin_enqueue_scripts', 'mss_include_scripts' );

/*-------------------------------------------------------------------

CREATE 'MYSLIDESHOW' SETTIGNS PAGE 

---------------------------------------------------------------------*/

function mss_menu_page(){

    add_menu_page('My Slide Show', 'My Slide Show', 'administrator', 'myslideshow-settings', 'mss_menu_output' );

    add_action( 'admin_init', 'mss_save_settings' );

}

add_action('admin_menu', 'mss_menu_page');

/*-------------------------------------------------------------------

'MYSLIDESHOW' SAVE SETTINGS

---------------------------------------------------------------------*/

function mss_save_settings(){

	$settings_options=[		

		'slide_id'=>array('default'=>''),

	];

	foreach ($settings_options as $opt=>$val)
    	{
        	register_setting( 'myslideshow-settings-group', $opt,$val);
    	}
} 

/*-------------------------------------------------------------------

'MYSLIDESHOW MENU' PAGE CONTENT

---------------------------------------------------------------------*/

function mss_menu_output(){  ?>

	<form method="post" action="options.php"> 

	 <?php settings_fields( 'myslideshow-settings-group' ); ?>

    <?php do_settings_sections( 'myslideshow-settings-group' ); ?>


    <h2>My Slideshow</h2>

    <input type="button" class="add_new_image" name="add_new_image" value="Add Images" >

    	<div class="addslides-container">
   		
    		<ul id="sortable">
    	    <?php      		
       		//get ids of saved images 

            	$slide_ids = get_option( 'slide_id' );                
              
             	if(!empty($slide_ids)){

             		//display saved images
             		foreach($slide_ids as $key=>$value){

             			//todo: condition if source is unknown
             			if(wp_get_attachment_url($value))
             			{
             				echo "<li class='slide-container '>"

             			 	."<span class='remove-this-slide'><b>X</b></span>"

             				."<img src='".wp_get_attachment_url($value)."' >"

             				."<input type='hidden' name='slide_id[]' value='".$value."' >"

             				."</li>";
             			}
             		}
             	}
             	else{
             		echo "<span class='no-images-yet'>Images are not added yet!</span>";
             	}

             	?>

             </ul>
    	</div>
    
 	<?php  submit_button(); ?>
 	</form>
<?php

}
/*-------------------------------------------------------------------

CREATE SHORTCODE TO DISPLAY SLIDESHOW

---------------------------------------------------------------------*/

function myslideshow_output() {
  
   	$slide_ids = get_option( 'slide_id' );
   	$return='';

   //check if images are selected for slideshow

  	if(!empty($slide_ids))
  	{
  		$return="<div id='myslideshow' class='carousel slide' data-ride='carousel'>";

  		//ADD INDICATORS

  		$return=$return." <ol class='carousel-indicators'>";

  		$i=0;

  		foreach($slide_ids as $key=>$value)
		{   			
   			$return=$return."<li data-target='#myslideshow' data-slide-to='".$i."'";

   			if($i==0)
   			{
   				$return=$return."class='active'";
   			}

   			$return=$return."></li>";
     			$i++;
  		}

  		$return=$return."</ol>";

  		//ADD SLIDER WRAPPERS	

  		$j=0;

  		$return=$return."<div class='carousel-inner'>";

   		foreach($slide_ids as $key=>$value)
		{
   			$return=$return."<div class='carousel-item";

   			if($j==0)
   			{
   				$return=$return." active";
   			}
   			$j++;
   			$return=$return."'><img class='mss-slide' src='".wp_get_attachment_url($value)."' > 
   			</div>";
  		}

  		$return=$return."</div>";//end - inner-carousel-items

  		//ADD LEFT-RIGHT CONTROLERS

  		$return=$return."<a class='carousel-control-prev' href='#myslideshow' data-slide='prev'>
				  			<span class='carousel-control-prev-icon'></span>				  		
				  		</a>
				  		<a class='carousel-control-next' href='#myslideshow' data-slide='next'>
				  			<span class='carousel-control-next-icon'></span>
				  		</a>";

  		$return=$return."</div>";//end - carousel-container
    
	}//end - check if images are selected for slideshow

   return $return;
}
add_shortcode( 'myslideshow', 'myslideshow_output' );

?>
