
// when the page loads, check if there is anything in the cart. If there is update the cart number
// A $( document ).ready() block.
$( document ).ready(function() {
  
  
  
  // this is the call to save to cart
function remove_from_cart(id){

    // trigger this function when our form runs
    $.ajax({
        url:'/system/modules/panel_pricing_calculator/assets/php/action.remove.from.cart.endpoint.php',
        type:'POST',
        data:"entry_id="+id+"",
        success:function(result){
        	console.log("REMOVED SUCCESS");
        	
        	var ourItem = '#cart_item_' + id;
        	console.log("SELECTOR: " + ourItem);
        	$(ourItem).remove();
        	
        	if ($('#cart_contents').is(':empty')){
        		$("#calc_cart_container").slideUp();
			}
        	
        	$("#cart_total").html(result);

        },
        error:function(result){
			$("#dev_message").html("REMOVE FROM CART FAIL");
        }
    });
	
}
  
  
  
}
