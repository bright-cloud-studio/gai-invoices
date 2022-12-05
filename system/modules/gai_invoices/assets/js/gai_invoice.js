// when document has fully loaded
$( document ).ready(function() {
  
  
  
    // called when selecting a Work Assignment on the Create an Invoice page
    function selectWorkAssignment(id){

        alert("ID: " . id);
        
        /*
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
        */

    }
  
  
  
});
