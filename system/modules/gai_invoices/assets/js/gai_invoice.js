
    // Changes which Work Assignment Form is visible when clicking a Work Asssignment List item
    function selectWorkAssignment(id){

        // First, hide all other work assignments
        $('.work_assignment_form').fadeOut();
        
        // Close all opened handoff forms
        $('#handoff_form').fadeOut();
        
        // Then, show this specific one
        $('.' + id).fadeIn();

    }
    
    
    // Adds another Transaction fieldset to the specific form
    function addAnotherTransaction(id){
        
        // get total of Transactions already listed in the form
        var transCount = $('.' + id + ' .transactions fieldset.transaction').length + 1;
        
        // set our hidden transactions total value
        $('.' + id + ' .trans_total').val(transCount);
        
        // Clone the first Transaction as a base
        var cloned =  $('.' + id + ' .templates fieldset.transaction:first').clone();
        
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
        
        // Modify the cloned "Meeting Start" field to have a unique ID
        cloned.find("label[for='meeting_start']").attr('for', 'meeting_start_' + transCount);
        cloned.find('input#meeting_start').val(" ");
        cloned.find('input#meeting_start').attr('name', 'meeting_start_' + transCount);
        cloned.find('input#meeting_start').attr('id', 'meeting_start_' + transCount);
        
        // Modify the cloned "Meeting End" field to have a unique ID
        cloned.find("label[for='meeting_end']").attr('for', 'meeting_end_' + transCount);
        cloned.find('input#meeting_end').val(" ");
        cloned.find('input#meeting_end').attr('name', 'meeting_end_' + transCount);
        cloned.find('input#meeting_end').attr('id', 'meeting_end_' + transCount);
        
        // Modify the cloned "Meeting Date" field to have a unique ID
        cloned.find("label[for='meeting_date']").attr('for', 'meeting_date_' + transCount);
        cloned.find('input#meeting_date').val(" ");
        cloned.find('input#meeting_date').attr('name', 'meeting_date_' + transCount);
        cloned.find('input#meeting_date').attr('id', 'meeting_date_' + transCount);
        
        // Modify the cloned "Notes" field to have a unique ID
        cloned.find("label[for='notes']").attr('for', 'notes_' + transCount);
        cloned.find('textarea#notes').val(" ");
        cloned.find('textarea#notes').attr('name', 'notes_' + transCount);
        cloned.find('textarea#notes').attr('id', 'notes_' + transCount);
        
        // Append the cloned and updated Transaction to the list of other Transactions
        cloned.appendTo('.' + id + ' .transactions');
        
        $('#meeting_start_' + transCount).datetimepicker({
            datepicker:false,
            format: 'H:i',
            formatTime: 'g:i A',
            step: 15
        });
        
        $('#meeting_end_' + transCount).datetimepicker({
            datepicker:false,
            format: 'H:i',
            formatTime: 'g:i A',
            step: 15
        });
    
        $('#meeting_date_' + transCount).datetimepicker({
            timepicker:false,
            format:'m-d-Y'
        });
        
        
        
        
        

    }
    
    
    // this is the big bad booty daddy that will generate our Transactions on Google Sheets and mark our Work Assignment as Processed
    function processWorkAssignment(id){

        // remove our onclick and add disabled class
        $("a#process_work_assignment").off('click');
        $("a#process_work_assignment").addClass("disabled");

        // get every form field and add them to the ajax data line
        var datastring = $("#form_" + id).serialize();
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.process.work.assignment.php',
            type: 'POST',
            data: datastring,
            success:function(result){
                // redirect us to the success page
                window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/work-assignments/submit-success.html");
            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for processWorkAssignment");
            }
        });
        
    }
    
    
    
    
    
    
    
     // Changes which Work Assignment Form is visible when clicking a Work Asssignment List item
    function handoffSelected(id){

        // close all handoff forms
        $('#handoff_form').fadeOut();
        
        // Then, show this specific one
        $('#form_' + id + ' #handoff_form').fadeIn();

    }
    
    
    
    // this is the big bad booty daddy that will generate our Transactions on Google Sheets and mark our Work Assignment as Processed
    function handoffWorkAssignment(id){

        // get every form field and add them to the ajax data line
        var datastring = $("#form_" + id).serialize();
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.handoff.work.assignment.php',
            type: 'POST',
            data: datastring,
            success:function(result){
                // redirect us to the success page
                window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/work-assignments/hand-off-success.html");
            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for handoffWorkAssignment");
            }
        });
        
    }
    
    
    
    
    // This will update the user's transactions as "Reviewed"
    function reviewTransactions(id){

        // get every form field and add them to the ajax data line
        var datastring = $("#" + id).serialize();
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.review.transactions.php',
            type: 'POST',
            data: datastring,
            success:function(result){
                // redirect us to the success page
                window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/review/review-success.html");
            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for reviewTransactions");
            }
        });

        
    }
    
    
