jQuery(function($){

/*----------------------------------------------------------------
MAKE SELECTED IMAGES SORTABLE ON 'MYSLIDESHOW SETTINGS' PAGE 
------------------------------------------------------------------*/	

$( "#sortable" ).sortable();
$( "#sortable" ).disableSelection();

/*----------------------------------------------------------------
ADD NEW IMAGES TO 'MYSLIDESHOW SETTINGS' PAGE
------------------------------------------------------------------*/
$('body').on('click','.add_new_image',function(e){
		
		e.preventDefault();

		var img_uploader;

		if(img_uploader){
			img_uploader.open(); 
		}

		//define img_uploader as wp.media obj
		img_uploader = wp.media({
				
				title: 'Insert image',
				library : { type : 'image'},
				button: { text: 'Use this image' },
				multiple: true //true: for multiple selection 

 				});

		//select media 
 		img_uploader.on('select', function() 
 		{ 
				// multiple select : Get all selected image IDs
				var attachments = img_uploader.state().get('selection');

				attachment_ids = new Array(), i = 0;
				var slide_id=0;

				attachments.each(function(attachment) 
				{
 					attachment_ids[i] = attachment['id'];
 					slide_id++;					
					//console.log( attachment );

					var new_slide='<li class="slide-container">'+
									'<span class="remove-this-slide"><b>X</b></span>'+
									'<img src="' + attachment['attributes']['url'] + '"/>'+
									'<input type="hidden" name="slide_id[]" value="'+attachment['id']+'">'+
									'</li>';

					$('.addslides-container ul').append(new_slide);
					$('.no-images-yet').remove();
					i++;
				});//end - foreach attachement
				//console.log("number of images selected "+i);
			
		});

		img_uploader.open();

});//end - add new image

/*----------------------------------------------------------------
REMOVE IMAGES FROM 'MYSLIDESHOW SETTINGS' PAGE
------------------------------------------------------------------*/	
$('body').on('click','.remove-this-slide',function(e){
		
	    $(this).closest('.slide-container').remove();

});//end - remove this slide



});//end - jQuery function  