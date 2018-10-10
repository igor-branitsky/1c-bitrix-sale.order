document.addEventListener('DOMContentLoaded', function(){
	if(typeof $ === "function"){
		$('body').on('submit', '#form-auth-sale-order', function(){
			$('.error-auth-form').text('');
			$('.success-auth-form').text('');
			var data_form = $(this).serializeArray();
			$.ajax({
				url: window.location.href,
				method: 'POST',
				data:data_form,
				dataType : 'json',
				success:function (json) {
					if(json.AUTH === 'N'){
						$('.error-auth-form').text(json.AUTH_MESSAGE);
					} else {
						$('.success-auth-form').text(json.AUTH_MESSAGE);
						setTimeout("window.location.reload();", 1000);
					}
				}
			});
			return false;
		});

		$('body').on('change', '.js-changer-delivery', function(){
			var data_id = $(this).data('change-block');
			$('.block-delivery-change').hide();
			$('[data-delivery-block="'+data_id+'"]').show();
			return false;
		});

		$('body').on('click', '.js-change-address', function(){
			var data_id = $(this).data('address-id');
			$('.block-address').hide();
			$('[data-block-form-address="'+data_id+'"]').show();
			return false;
		});
	}
});
