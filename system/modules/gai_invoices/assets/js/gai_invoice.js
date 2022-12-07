
    // Changes which Work Assignment Form is visible when clicking a Work Asssignment List item
    function selectWorkAssignment(id){

        // First, hide all other work assignments
        $('.work_assignment_form').fadeOut();
        
        // Then, show this specific one
        $('.' + id).fadeIn();

    }
    
    
    // Adds another Transaction fieldset to the specific form
    function addAnotherTransaction(id){
        
        // get total of Transactions already listed in the form
        var transCount = $('.' + id + ' fieldset.transaction').length + 1;
        
        // set our hidden transactions total value
        $('.' + id + ' .trans_total').val(transCount);
        
        // Clone the first Transaction as a base
        var cloned =  $('.' + id + ' fieldset.transaction:first').clone();
        
        // Modify the cloned "Services Provided" field to have a unique ID
        cloned.find("label[for='service_provided']").attr('for', 'service_provided_' + transCount);
        cloned.find('select#service_provided').val(' ');
        cloned.find('select#service_provided').attr('name', 'service_provided_' + transCount);
        cloned.find('select#service_provided').attr('id', 'service_provided_' + transCount);
        
        // Modify the cloned "Price" field to have a unique ID
        cloned.find("label[for='price']").attr('for', 'price_' + transCount);
        cloned.find('input#price').val(" ");
        cloned.find('input#price').attr('name', 'price_' + transCount);
        cloned.find('input#price').attr('id', 'price_' + transCount);
        
        // Modify the cloned "Notes" field to have a unique ID
        cloned.find("label[for='notes']").attr('for', 'notes_' + transCount);
        cloned.find('textarea#notes').val(" ");
        cloned.find('textarea#notes').attr('name', 'notes_' + transCount);
        cloned.find('textarea#notes').attr('id', 'notes_' + transCount);
        
        // Append the cloned and updated Transaction to the list of other Transactions
        cloned.appendTo('.' + id + ' .transactions');

    }
    
    
    // this is the big bad booty daddy that will generate our Transactions on Google Sheets and mark our Work Assignment as Processed
    function processWorkAssignment(id){

        // get every form field and add them to the ajax data line
        var datastring = $("#form_" + id).serialize();
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.process.work.assignment.php',
            type: 'POST',
            data: datastring,
            success:function(result){
                // redirect us to the success page
                window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/process-work-assignments/process-work-assignments-success.html");
            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for processWorkAssignment");
            }
        });
        
    }
    
    
    
