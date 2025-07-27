$(function(){
	//alert('testtt');
	$(document).on('click','.cart-btn', function(){
		let product_id = $(this).data('id');
		 $.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });
		console.log('Product_id ', product_id);
		$.ajax({
			'type' : 'POST',
			url : base_url + '/add-to-cart',
			data:{product_id:product_id},
			beforeSend:function(){

			},
			dataType:'json',
			success:function(response){
				console.log('response ', response);
				$('#myToast').find('.toast-title').text('');
				if(response.status){
					$('#myToast').find('.toast-body').html(`<span class="success">${response.msg}</span>`)
					$('.total-cart-count').text(response.total_cart_count)
				}else{
					$('#myToast').find('.toast-body').html(`<span class="error">${response.msg}</span>`)
				}
				showToast();
			},
			complete:function(){

			}
		})
	});
});

 function showToast() {
    var toast = new bootstrap.Toast(document.getElementById('myToast'));
    toast.show();
    setTimeout(function(){
      toast.hide();
    }, 3000);
 }