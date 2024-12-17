$('#category').dropdown();
$('#tag').dropdown();
$('#categories_select').dropdown();
$('#tags_select').dropdown();

$(function(){
	$("#close_product").click(function(){
		$(".product_box").modal('hide');
        $('#okMessageProduct').removeClass('flexSPr');   
        $('#noChanges').removeClass('flexWP');  
        $('#required_featured').addClass('d-none');
        $('#required_gallery').addClass('d-none');
        $('#limit_gallery').addClass('d-none');
        $('#galleryPreviewContainer').empty();

	});
	$(".product_box").modal({
		closable: true
	});
});  

$(document).ready(function() {
    $(".modals").click(function(event) {
        if ($(event.target).is(".modals")) {
             $('#errMessage').removeClass('flexWP'); 
             $('#okMessage').removeClass('flexSP');    
             $('#okMessageProduct').removeClass('flexSPr');     
             $('#noChanges').removeClass('flexWP');  
             $('#required_featured').addClass('d-none');
             $('#required_gallery').addClass('d-none');
             $('#limit_gallery').addClass('d-none');
             $('#galleryPreviewContainer').empty();

        }
    });
});

$('#featured_image').on('change', function() {
    const fileInput = this;
    const file = fileInput.files[0];


    const maxFileSize = 3 * 1024 * 1024; 

    const acceptedFormats = [
        "image/png", 
        "image/jpg", 
        "image/jpeg", 
        "image/gif", 
        "image/webp", 
        "image/bmp", 
        "image/svg+xml", 
        "image/tiff", 
        "image/ico"
    ];

    if (!file) {
        return; 
    }

    const fileType = file.type;
    const fileSize = file.size;

    if (!acceptedFormats.includes(fileType)) {
        $('#required_featured').removeClass('d-none');
        fileInput.value = ""; 
        return;
    }

    if (fileSize > maxFileSize) {
        $('#required_featured').text('File size is too large! Maximum allowed size is 5 MB.').removeClass('d-none');
        fileInput.value = ""; 
        return;
    }

        $('#required_featured').addClass('d-none');

        const reader = new FileReader(); 
        reader.onload = function(e) {
            $('#uploadedImage').attr('src', e.target.result).show(); 
        };
        reader.readAsDataURL(file); 

        $('#fileName').val(file.name);
        $('.close_image').attr('style', 'display: flex !important');
        $('.ui.small.image.box_input.box_featured').attr('style', 'display: none !important');

   
});

$('.close_image').on('click', function() {
    $('#uploadedImage').attr('src', '').hide(); 
    $('#featured_image').val(''); 
    $('.close_image').hide();  
    $('.ui.small.image.box_input.box_featured').attr('style', 'display: block !important');

});



$('#gallery').on('change', function () {
    const fileInput = this;
    const files = fileInput.files;
    const maxFilesSize = 35 * 1024 * 1024; 


    if (!files.length) {
        $('#galleryPreviewContainer').empty();  
        return;
    }

    if (files.length > 15) {
        $('#limit_gallery').removeClass('d-none');
        fileInput.value = ""; 
        $('#galleryPreviewContainer').empty(); 
        return;
    }

    let totalSize = 0;
    for (let i = 0; i < files.length; i++) {
        totalSize += files[i].size;
    }

    if (totalSize > maxFilesSize) {
        $('#required_gallery').text('Files size is too large!').removeClass('d-none');
        fileInput.value = ""; 
        return;
    }

    

    const acceptedFormats = [
        "image/png",
        "image/jpg",
        "image/jpeg",
        "image/gif",
        "image/webp",
        "image/bmp",
        "image/svg+xml",
        "image/tiff",
        "image/ico",
    ];

    for (let i = 0; i < files.length; i++) {
        totalSize += files[i].size;

        if (!acceptedFormats.includes(files[i].type)) {
            $('#required_gallery').text('One or more files are not valid images!').removeClass('d-none');
            fileInput.value = ""; 
            $('#galleryPreviewContainer').empty(); 
            break;
        }
    }

    const galleryPreviewContainer = $('#galleryPreviewContainer');

    galleryPreviewContainer.empty(); 
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
       $('#galleryPreviewContainer').attr('style', 'display: block !important');
       $('#required_gallery').addClass('d-none');
       $('#limit_gallery').addClass('d-none');

            const reader = new FileReader();
            reader.onload = function (e) {
                const img = $('<img>', { src: e.target.result, alt: 'Gallery Image' }).css({
                    'width': '200px',  
                    'object-fit': 'contain',  
                    'height': '90px',       
                });

                $('.close_gallery').attr('style', 'display: flex !important');
                $('.ui.small.image.box_input.box_gallery').attr('style', 'display: none !important');
                galleryPreviewContainer.append(img);
            };
            reader.readAsDataURL(file); 
    }
});


$('.close_gallery').on('click', function() {
    $('#galleryPreviewContainer img').attr('src', '').hide(); 
    $('#gallery').val(''); 
    $('.close_gallery').hide();  
    $('.ui.small.image.box_input.box_gallery').attr('style', 'display: block !important');

});


$(document).ready(function() {    
    if ($('#productTableBody tr').children().length === 0) {
        $('.box_delete_buttons').hide();
    }
});


