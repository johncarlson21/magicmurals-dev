require(['require', 'jquery', 'Surething_Testimonials/js/masonry-gallery/_scripts/isotope.pkgd.min', 'Surething_Testimonials/js/masonry-gallery/_scripts/imagesloaded.pkgd', 'js/remodal/remodal.min'], function (require, $, Isotope, remodal) {

	
	$(window).on('load', function () {
		
		var page = 1;
		var lastPage = null;
		var filter = "";
		var oldFilter = "";
		var size = 1;
		var button = 1;
		var button_class = "gallery-header-center-right-links-current";
		var normal_size_class = "gallery-content-center-normal";
		var full_size_class = "gallery-content-center-full";
		// var $container = $('#gallery-content-center');

		// $container.isotope({itemSelector : '.gallery-item'});

		function check_button() {
			$('.gallery-header-center-right-links-current').removeClass(button_class);
			if (button == 1) {
				$("#filter-all").addClass(button_class);
				$("#business-dropdown").hide();
				$("#residential-dropdown").hide();
			}
			if (button == 2) {
				$("#filter-residential").addClass(button_class);
				$("#business-dropdown").hide();
				$("#residential-dropdown").show();
			}
			if (button == 3) {
				$("#filter-business").addClass(button_class);
				$("#residential-dropdown").hide();
				$("#business-dropdown").show();
			}
		}

		$("#filter-all").click(function () {
			oldFilter = filter;
			filter = "";
			loadItems();
			//$container.isotope({ filter: '.all' }); 
			button = 1;
			check_button();
		});

		$("#filter-residential").click(function () {
			$("#residential-dropdown").val($("#residential-dropdown option:first").val());
			oldFilter = filter;
			filter = "R";
			loadItems();
			//$container.isotope({ filter: '.R' }); 
			button = 2;
			check_button();
		});

		$("#filter-business").click(function () {
			$("#business-dropdown").val($("#business-dropdown option:first").val());
			oldFilter = filter;
			filter = "B";
			loadItems();
			//$container.isotope({ filter: '.B' }); 
			button = 3;
			check_button();
		});

		// setup filter actions for other dropdowns
		$("#residential-dropdown").change(function () {
			f = $(this).val();
			if (f == "") {
				f = "B";
			}
			oldFilter = filter;
			filter = f;
			loadItems();
			//if (f == "") { $container.isotope({ filter: '.R' }); }
			//$container.isotope({ filter: '.' + f });
		});
		$("#business-dropdown").change(function () {
			f = $(this).val();
			if (f == "") {
				f = "B";
			}
			oldFilter = filter;
			filter = f;
			loadItems();
			//if (f == "") { $container.isotope({ filter: '.B' }); }
			//$container.isotope({ filter: '.' + f });
		});

		$('#gallery-content').show();

		check_button();
		
		var $container = null;
		//var instModal = null;
		
		require( [ 'jquery-bridget/jquery-bridget' ],
			function( jQueryBridget ) {
			  // make Isotope a jQuery plugin
			  jQueryBridget( 'isotope', Isotope, $ );
			  // now you can use $().isotope()
			  $container = $('#gallery-content-center').isotope({
					itemSelector: '.gallery-item',
					percentPosition: true
				});
			  /*jQueryBridget( 'remodal', remodal, $ );
			  instModal = $('[data-remodal-id=gallery-modal]').remodal({
					hashTracking: false
				});*/
			}
		  );

		/*var $container = $('#gallery-content-center').isotope({
			itemSelector: '.gallery-item',
			percentPosition: true
		});*/

		var loading = false;

		function loadItems() {
			//console.log('loading: '+loading);
			//console.log('filter: '+filter);
			//console.log('oldfilter: '+oldFilter);
			if (loading == false) {
				//console.log('loading');
				loading = true;
				if (filter != oldFilter) {
					page = 1;
					lastPage = 100;
					//console.log('filters do not match');
				}
				//console.log('page: '+page);
				//console.log('lastpage: '+lastPage);
				if (page <= lastPage) {
					//console.log('calling ajax');
					$.ajax({
						url: "/testimonials/testimonials/index?page=" + page + "&filter=" + filter,
						cache: false,
						dataType: "json"
					}).done(function (data) {
						if (data) {
							lastPage = data['lastPage'];
							if (filter != oldFilter) {
								$(".gallery-item").each(function () {
									$container.isotope('remove', this).isotope('layout');
								});
							}
							addItems(data);
						}
					});
				} else {
					loading = false;
				}
			}
		}

		var instModal = $('[data-remodal-id=gallery-modal]').remodal({
			hashTracking: false
		});

		function loadGalleryItem() {
			instModal.open();
		}

		function setupPopup() {
			$('.gallery-item').click(function () {
				$('#gallery-modal .gallery-modal-box').html('');
				content = $('.gallery-item-info', this).html();
				img = $('.item-img', this).attr('src');
				img = img.replace("w=300", "w=800");
				$('#gallery-modal .gallery-modal-box').html(content);
				$('#gallery-modal .gallery-modal-box .gallery-item-img img').attr('src', img);
				loadGalleryItem();
			});
		}

		// initial load of images
		var hash = location.hash;
		if (hash != "") {
			$(hash).trigger("click");
		} else {
			$.ajax({
				url: "/testimonials/testimonials/index?page=" + page,
				cache: false,
				dataType: "json"
			}).done(function (data) {
				if (data) {
					lastPage = data['lastPage'];
					loading = true;
					addItems(data);
					loading = false;
				}
			});
		}

		setupPopup();

		function addItems(data) {
			var $content = "";
			var $items = "";
			if (data['data']) {
				// create main item
				for (var i = 0; i < data['data'].length; i++) {
					var obj = data['data'][i];
					$content += '<div class="gallery-item all ' + obj.type.replace(/,/g, " ") + '" style="opacity:0;">';
					$content += '<img class="item-img" src="' + obj.imageUrl + '" title="' + obj.headline + '" />';
					$content += '<div class="gallery-item-info">';
					$content += '<div class="grid12-7 gallery-item-img"><img src="" title="' + obj.headline + '" /></div>';
					$content += '<div class="grid12-5 gallery-item-desc">';
					$content += '<h3>' + obj.headline + '</h3>';
					$content += '<p>' + obj.description + '</p>';
					$content += '<p>&nbsp;</p><h6>' + obj.name + '<br /><small style="font-weight: 300;">';

					if (obj.company != "") {
						$content += obj.company;
					}

					if (obj.company != "" && obj.install != "") {
						$content += " - " + obj.install;
					} else if (obj.install != "") {
						$content += obj.install;
					}

					$content += '</small></h6><p><a href="' + obj.url + '">' + obj.mural + '</a></p>';
					$content += '</div>'; // end item desc
					$content += '</div>'; // end item info
					$content += '</div>'; // end main item
				}
				$items = $($content);
				$container.append($items);
				$container.imagesLoaded(function () {
					$container.isotope('appended', $items);
					//$container.isotope( 'insert', $items );
					$container.isotope('layout');
					//$container.isotope( 'reloadItems' );
				});


				$container.one('layoutComplete', function () {
					loading = false;
					page = page + 1;
				});

				setupPopup();
				oldFilter = filter;
				//loading=false;
			} else {
				loading = false;
			}
		}

		$(window).scroll(function () {
			if ($(this).scrollTop() + $(this).innerHeight() >= $(".footer-container").offset().top) {
				oldFilter = filter;
				loadItems();
			}
		});


	});

	$(window).ready(function () {
		var hash = location.hash;
		if (hash != "") {
			setTimeout(function () {
				$(hash).trigger("click");
			}, 300);
		}
	});

});
