
var api = null;
var verify_api = null;
var inst = null;

var $j = null; //jQuery.noConflict();

//require(['jquery', 'jCrop'], function($){
require(['jquery'], function() {
	$j = jQuery.noConflict();
	
	origImg = $j('#image-main').attr('src');
	origImgM = $j('.zoom-image-box-content img').attr('src');
	/* if product image thumbnails then load js */
	if ($j(".product-image-thumbs").length) {
		$j(".product-image-thumbs .thumb-link").click(function() {
			var imgSrc = $j(this).attr('data-image-src');
			var imgIndex = parseInt($j(this).attr('data-image-index'));
			if(!patternPage){
				destroyCrop();
			}
			if (imgIndex > 0) {
				$j('#image-main').attr('src', imgSrc);
				$j('.zoom-image-box-content img').attr('src', imgSrc);
				$j('#image-main').css({height:'auto',width:'auto'});
			} else {
				$j('#image-main').attr('src', origImg);
				$j('.zoom-image-box-content img').attr('src', origImgM);
			}
			
		});
	}
	
});

function customImageCrop() {
	if (productType == "patternprod") { return; }
	
	/* need to make sure that the main image is the original image and not one that was a thumb */
	//$('#' + mainImg).attr('src', origImg);
	//$('.zoom-image-box-content img').attr('src', origImgM);
	
	/* This is the custom function for when custom size is chosen */
	if (api === null) {
		var wd = $j('#' + mainImg).width();
		var ht = $j('#' + mainImg).height();
		
		$j('#' + mainImg).Jcrop({
				setSelect: [0, 0, imgWidth, imgHeight],
				allowSelect: false,
				allowMove: true,
				allowResize: true,
				onChange: showCoords,
				trueSize: [imgWidth, imgHeight],
				disabled: true,
                bgOpacity: 0.3,
			},
			function () {
				api = this;
				api.disable();
				api.release();
			}
		);
		
	}
} // end custom image crop
	
function showCoords(c) {
	if (productType == "patternprod") { return; }
	//update jcrop coordinates
	$j("#options_" + cropSizeId + "_text").val(c.x + ',' + c.y + ',' + c.w + ',' + c.h);
}
	
function destroyCrop() {
	if (api !== null) {
		api.destroy();
		api = null;
	}
	$j("#image-main").addClass("visible");
	// show add to cart button and hide verify
	if (currentSku == customUploadSku) {
		$j("#verify-crop-btn.analyze-btn").hide();
	} else {
		$j("#verify-crop-btn").hide();
	}
	$j("#product-addtocart-button").show();
	$j(".option-custom-sizes").hide();
	$j("#conv-calculator").show();
	$j(".custom-notification").hide();
}
	
/* metric calculations */

function roundit(which){
	return Math.round(which*100)/100
}

function cmconvert(){
	with (document.cminch){
		feet.value = roundit(cm.value/30.84);
		inch.value = roundit(cm.value/2.54);
	}
}

function inchconvert(){
	with (document.cminch){
		cm.value = roundit(inch.value*2.54);
		feet.value=roundit(inch.value/12);
	}
}

function feetconvert(){
	with (document.cminch){
		cm.value=roundit(feet.value*30.48);
		inch.value=roundit(feet.value*12);
	}
}

function calcReset() {
	$j("#calc-feet").val("");
	$j("#calc-inch").val("");
	$j("#calc-feet-w").val("");
	$j("#calc-inch-w").val("");
	$j("#calc-cm").val("");
	$j("#calc-cm-w").val("");
}

function calcCM() {
	if ( $j("#calc-feet").val() == "" ) {
		calcFtIn();
		return;
	}
	feet = roundit($j("#calc-feet").val()*30.48);
	inches = roundit($j("#calc-inch").val()*2.54);
	total = (feet+inches).toFixed(2);
	$j("#calc-cm").val(total);
	feetW = roundit($j("#calc-feet-w").val()*30.48);
	inchesW = roundit($j("#calc-inch-w").val()*2.54);
	totalW = (feetW+inchesW).toFixed(2);
	$j("#calc-cm-w").val(totalW);
}

function calcFtIn() {
	height = $j("#calc-cm").val()/2.54; // we have inches now
	width = $j("#calc-cm-w").val()/2.54; // we have inches now

	feet_height = Math.floor(height/12);
	inch_height = Math.floor(height %= 12);
	feet_width = Math.floor(width/12);
	inch_width = Math.floor(width %= 12);

	$j("#calc-feet").val(feet_height); // height feet
	$j("#calc-inch").val(inch_height); // height inch
	$j("#calc-feet-w").val(feet_width); // width feet
	$j("#calc-inch-w").val(inch_width); // width inch

	calculateCustomSqFt();
}

function setColor(color) {
	if (color == "original") {
		$j('#' + mainImg).css({filter:"none",WebkitFilter:"none"});
	} else if (color == "grayscale") {
		$j('#' + mainImg).css({filter:"grayscale(100%)",WebkitFilter:"grayscale(100%)"});
	} else if (color == "sepia") {
		$j('#' + mainImg).css({filter:"sepia(100%)",WebkitFilter:"sepia(100%)"});
	}
}

function setOrientation(orient) {
	if (orient == "mirror") {
		$j('#' + mainImg).css({transform:"none",WebkitTransform:"scaleX(-1)",top:"0",left:"0"});
	} else if (orient == "original") {
		$j('#' + mainImg).css({transform:"",WebkitTransform:"",top:"",left:""});
	}
}

//used to update price
function updatePrice() {
	$j("div.price-box .price-final_price").trigger("updatePrice");
}

/*$j(document).ready(function() {
});*/
	
	
//}); // end require statement
