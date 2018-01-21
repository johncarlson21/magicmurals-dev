$j(window).load(function () {

var size = 1;
var button = 1;
var button_class = "gallery-header-center-right-links-current";
var normal_size_class = "gallery-content-center-normal";
var full_size_class = "gallery-content-center-full";
// var $container = $j('#gallery-content-center');

// $container.isotope({itemSelector : '.gallery-item'});

function check_button(){
	$j('.gallery-header-center-right-links-current').removeClass(button_class);
	if(button==1){
		$j("#filter-all").addClass(button_class);
		$j("#business-dropdown").hide();
		$j("#residential-dropdown").hide();
		}
	if(button==2){
		$j("#filter-residential").addClass(button_class);
		$j("#business-dropdown").hide();
		$j("#residential-dropdown").show();
		}
	if(button==3){
		$j("#filter-business").addClass(button_class);
		$j("#residential-dropdown").hide();
		$j("#business-dropdown").show();
		}	
}

/* // No longer used
function check_size(){
	$j("#gallery-content-center").removeClass(normal_size_class).removeClass(full_size_class);
	if(size==0){
		$j("#gallery-content-center").addClass(normal_size_class); 
		$j("#gallery-header-center-left-icon").html('<span class="iconb" data-icon="&#xe23a;"></span>');
		}
	if(size==1){
		$j("#gallery-content-center").addClass(full_size_class); 
		$j("#gallery-header-center-left-icon").html('<span class="iconb" data-icon="&#xe23b;"></span>');
		}
	$container.isotope({itemSelector : '.gallery-item', percentPosition: true});
}
*/


	
$j("#filter-all").click(function() { $container.isotope({ filter: '.all' }); button = 1; check_button(); });
$j("#filter-residential").click(function() {  $container.isotope({ filter: '.R' }); button = 2; check_button(); });
$j("#filter-business").click(function() {  $container.isotope({ filter: '.B' }); button = 3; check_button(); });
	
// setup filter actions for other dropdowns
	$j("#residential-dropdown").change(function() {
		f = $j(this).val();
		if (f == "") { $container.isotope({ filter: '.R' }); }
		$container.isotope({ filter: '.' + f });
	});
	$j("#business-dropdown").change(function() {
		f = $j(this).val();
		if (f == "") { $container.isotope({ filter: '.B' }); }
		$container.isotope({ filter: '.' + f });
	});
	
// not used
// $j("#gallery-header-center-left-icon").click(function() { if(size==0){size=1;}else if(size==1){size=0;} check_size(); });

$j('#gallery-content').show();

check_button();
//check_size();
var $container = $j('#gallery-content-center').isotope({itemSelector : '.gallery-item', percentPosition: true});

	$j('.gall-load').on('click', function() {
		if ( c > gItemsCount ) { return; }
                newItems = '';
                for (i = c; i < (c + 40); i++) {
                        newItems += gItems[i];
                }
		newItems = $j(newItems);
                $container.append(newItems);
		$container.imagesLoaded( function() {
			$container.isotope('appended', newItems);
			$container.isotope('layout');
		});
		c = c + 40;
		setupPopup();
        });

	var instModal = $j('[data-remodal-id=gallery-modal]').remodal({hashTracking: false});

	function loadGalleryItem() {
		instModal.open();
	}

	function setupPopup() {
	    $j('.gallery-item').click(function() {
		$j('#gallery-modal .gallery-modal-box').html('');
		content = $j('.gallery-item-info', this).html();
		img = $j('.item-img', this).attr('src');
		img = img.replace("w=300","w=800");
		$j('#gallery-modal .gallery-modal-box').html(content);
		$j('#gallery-modal .gallery-modal-box .gallery-item-img img').attr('src', img);
		loadGalleryItem();
	    });
	}
	setupPopup();
});

