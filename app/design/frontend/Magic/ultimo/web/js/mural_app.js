require(['jquery', 'remodal'], function($j){
	
	function submitForm() {
		if ($j('#couponForm #couponemail').val() != '') {
			if (!validateEmail($j('#couponForm #couponemail').val())) {
				$j('#couponForm #couponemail').css('border-color', 'red');
				return false;
			}
			$j.ajax({
				url: "/coupon/coupon/index/",
				type: "POST",
				data: "email="+$j('#couponForm #couponemail').val()+"&subscribe="+$j('#couponForm #subscribe').val(),
				cache: false
			})
			.done(function( html ) {
				if (html == 'success') {
					$j("#newsletter-modal-box .newsletter-box-content").html('<p>&nbsp;</p><p>&nbsp;</p>');
					$j("#newsletter-modal-box .verify-crop-title").html('<span class="larger">THANK YOU</span> for signing up for our Newsletter!');
					$j("#newsletter-modal-box .verify-crop-title").after('<h4>Don&rsquo;t forget to check your email for your 10% OFF coupon code which can be used on your first purchase. Happy shopping!</h4>');
				} else if (html == 'error') {
					$j("#newsletter-modal-box .newsletter-box-content").html('<p>There was an error! Please try again later.</p>');
				} else if (html == 'subscribed') {
					$j("#newsletter-modal-box .newsletter-box-content").html('<h3 style="background-color: #b41717; padding: 5px; margin: 10px;">This email is already subscribed.</h3>');
				}
			});
		} else {
			$j("#couponemail").css("background:red");
			$j("#error").show();
		}
	}
	
	function footerSubmitForm() {
        if ($j('#subscribe-form #newsletter').val() != '') {
            if (!validateEmail($j('#subscribe-form #newsletter').val())) {
                $j('#subscribe-form #newsletter').css('border-color', 'red');
                return false;
            }
            
            $j.ajax({
                url: "/coupon/coupon/index/",
                type: "POST",
                data: "email="+$j('#subscribe-form #newsletter').val()+"&subscribe="+$j('#subscribe-form #subscribe').val(),
                cache: false
            })
            .done(function( html ) {
				var inst = $j('[data-remodal-id=newsletter-modal2]').remodal();
                if (html == 'success') {
                    inst.open();
                    $j('#subscribe-form #newsletter').val('');
                    $j("#newsletter-modal-box2 .newsletter-box-content").html('<p>&nbsp;</p><p>&nbsp;</p>');
                    $j("#newsletter-modal-box2 .verify-crop-title").html('<span class="larger">THANK YOU</span> for signing up for our Newsletter!');
					$j("#newsletter-modal-box2 .verify-crop-title").after('<h4>Don&rsquo;t forget to check your email for your 10% OFF coupon code which can be used on your first purchase. Happy shopping!</h4>');
                } else if (html == 'error') {
					inst.open();
                    $j("#newsletter-modal-box2 .newsletter-box-content").html('<p>There was an error! Please try again later.</p>');
                } else if (html == 'subscribed') {
					inst.open();
					$j("#newsletter-modal-box2 .newsletter-box-content").html('<h3 style="background-color: #b41717; padding: 5px; margin: 10px;">This email is already subscribed.</h3>');
				}
            });
        }
        return false;
    }
	
	window.footerSubmitForm = footerSubmitForm;
	
	/* -------- VARIOUS OTHER FUNCTIONS ------- */
	// Header Newsletter popup
	/*$j(document).on('opening', '#newsletter-modal-box.remodal', function () {
		$j.ajax({
			url: "/coupon.php",
			cache: false
		})
		.done(function( html ) {
			$j(".newsletter-box-content").html( html );
		});
	});*/
	
	function validateEmail($email) {
	  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	  return emailReg.test( $email );
	}
	
	// Open newsletter if url hashtag is #signup
	$j(document).ready(function() {
		if (location.hash === "#signup") {
			var inst = $j('[data-remodal-id=newsletter-modal]').remodal();
			inst.open();
		}
	});

	/* close response popup after submit */
	var isHidden = false;
	function closeResponsePopup() {
		 slideIconWidth = $j("#slideLeft").outerWidth();
		 popupWidth = $j(".mbdialog").outerWidth()-slideIconWidth;
		 if(isHidden){
			 isHidden = false;
			 $j(".mbdialog").animate({"left":"-10px"}, "slow");
		 }else{
			 isHidden = true;
			 $j(".mbdialog").animate({"left":"-"+popupWidth+"px"}, "slow");
		 }
	}
	
	window.closeResponsePopup = closeResponsePopup;

	/* functions for new product thumb images */
	var origImg = null;
	var origImgM = null;
	$j(document).ready(function() {
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
					//$j('.product-image').css({border:'none'});
					$j('#image-main').css({height:'auto',width:'auto'});
				} else {
					$j('#image-main').attr('src', origImg);
					$j('.zoom-image-box-content img').attr('src', origImgM);
					//$j('.product-image').css({border:'1px solid #999999'});
				}

			});
		}
	});

	/* function to close dialog box */
	var slideLeftClicked = false;
	var isHidden3 = false;
	function closeDialogAfter(id) {
		if (slideLeftClicked) { return false; }
		slideLeftClicked = true;
		slideIconWidth = $j("#slideLeft").outerWidth();
		popupWidth = $j(id).outerWidth()-slideIconWidth;
		//$j(id).animate({"left":"-"+popupWidth+"px"}, "slow");

		// resetup the click on the slideleft id
		$j('#slideLeft').click(function(){
		 if(isHidden3){
			 isHidden3 = false;
			 $j(id).animate({"left":"-10px"}, "slow");
		 }else{
			 isHidden3 = true;
			 $j(id).animate({"left":"-"+popupWidth+"px"}, "slow");
		 }
		});
	}
	
	window.closeDialogAfter = closeDialogAfter;

	/* tooltip load */
	/*
	$j(document).ready(function () {
		$j(".tooltip").each(function () {
			$j(this).qtip({
				content: {
					text: $j(this).next().next('.tooltiptext'),
					title: $j(this).next('.tooltiptitle'),
					button: true
				},
				hide: {
					fixed: true,
					delay: 300
				},
				position: {
					my: "left center",
					at: "top right",
					viewport: $j(window),
					adjust: {
						method: "shift"
					}
				},
				style: 'qtip-bootstrap'
			});
		});
	});
	*/
	

}); // end require