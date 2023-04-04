
// ON FINISHED LOADING
$( document ).ready(function() {
    
    
    // When the "Complete Work Assignment" button is clicked
    $('input[name="complete_work_assignment"]').on("click", function(e) {

        // uncheck ourselves here, only check if the confirmation is successfull
        var ourCheckbox = $(this);
        ourCheckbox.prop( "checked", false );
        
        var content_start = '<div class="size100 marbot60">Checking this box will permanently remove this Work Assignment from your list. This is an optioanl feature to cut down on clutter and IS NOT needed to process a work assignment. Please confirm you would like to remove this work assignment from your list.</div>';
        var content_form = '' +
    	'<form action="" class="formName">' +
    	    '<div class="form-group">' +
    	    	'<div class="size100 marbot15">Type the word "REMOVE" (All capitalized) to permanently remove this work assignment from your list.</div>'+
    	    	'<div class="size100"><input type="text" name="del_string" id="del_string"/></div>' +
    	    '</div>' +
        '</form>';
        
        // Open a Confirmation popup
        $.confirm({
            title: 'Work Assignment - Remove Work Assignment from your list (OPTIONAL)',
            content: content_start + content_form,
            icon: 'fa fa-warning  fa-bounce',
            type: 'red',
            useBootstrap: false,
            draggable: false,
            theme: 'material',
            buttons: {
                confirm: {
                    text: 'REMOVE THIS WORK ASSIGNMENT FROM YOUR LIST',
                    btnClass: 'btn-red',
                    action: function(){
                        
                     var ourString = this.$content.find('#del_string').val();
                        if(ourString != "REMOVE") {
                            return false;
                        } else {
                        
                        // disable the other buttons
                        this.buttons.confirm.disable()
                        this.buttons.cancel.disable()
                        
                        // now we check ourselves
                        ourCheckbox.prop( "checked", true );
                        
                        }
                        
                    }
                },
                cancel: function () {
                    ourCheckbox.prop( "checked", false );
                },
            }
        });

    });
    
    $('input[name="reviewed_confirmed"]').on("click", function(e) {

        var ourCheckbox = $(this);
        ourCheckbox.prop( "checked", false );
        
        // Create our Confirmation alert box
        $.confirm({
            title: 'Review Submissions - Confirmation',
            content: 'By checking this box, you are confirming you have reviewed your listed transactions and they contain accurate information. Please confirm you have reviewed your transactions.',
            icon: 'fa fa-warning  fa-bounce',
            type: 'red',
            useBootstrap: false,
            draggable: false,
            theme: 'material',
            buttons: {
                confirm: {
                    text: 'CONFIRM REVIEW',
                    btnClass: 'btn-red',
                    action: function(){
                        
                        this.buttons.confirm.disable()
                        this.buttons.cancel.disable()
                        
                        ourCheckbox.prop( "checked", true );
                        $('#form_transaction_row_ids .widget-submit').fadeIn();
                        
                    }
                },
                cancel: function () {
                    ourCheckbox.prop( "checked", false );
                    $('#form_transaction_row_ids .widget-submit').fadeOut();
                },
            }
        });

    });
    
    
    // ADMIN REVIEW
    // if a price value is changed, flag this row as updated
    $( ".mod_admin_review .price" ).change(function() {
        $(this).closest('tr').find(".update").val("Process to Save");
        //$(this).closest('form').find(".btn").html("Process and Review Next Psychologist");
        
        var rowId = $(this).closest('tr').find(".row_id").val();
        
        if(!$(this).closest('form').find("#rows").val().includes(rowId)) {
            if($(this).closest('form').find("#rows").val() == '')
                $(this).closest('form').find("#rows").val(rowId);
            else {
                var currentVal = $(this).closest('form').find("#rows").val();
                $(this).closest('form').find("#rows").val(currentVal + "," + rowId);
            }
        }
        
    });
    
    // ADMIN REVIEW
    // if Service changed
    $( ".mod_admin_review #service_provided" ).change(function() {
        
        $(this).closest('tr').find(".update").val("Process to Save");
        //$(this).closest('form').find(".btn").html("Process and Review Next Psychologist");
        
        var rowId = $(this).closest('tr').find(".row_id").val();
        
        if(!$(this).closest('form').find("#rows").val().includes(rowId)) {
            if($(this).closest('form').find("#rows").val() == '')
                $(this).closest('form').find("#rows").val(rowId);
            else {
                var currentVal = $(this).closest('form').find("#rows").val();
                $(this).closest('form').find("#rows").val(currentVal + "," + rowId);
            }
        }
        
    });
    
    // ADMIN REVIEW
    // if Delete changed
    $( ".mod_admin_review .delete" ).change(function() {
        
        console.log("CHANGED");
        
        $(this).closest('tr').find(".update").val("Process to Save");
        //$(this).closest('form').find(".btn").html("Process and Review Next Psychologist");
        
        var rowId = $(this).closest('tr').find(".row_id").val();
        
        if(!$(this).closest('form').find("#rows").val().includes(rowId)) {
            if($(this).closest('form').find("#rows").val() == '')
                $(this).closest('form').find("#rows").val(rowId);
            else {
                var currentVal = $(this).closest('form').find("#rows").val();
                $(this).closest('form').find("#rows").val(currentVal + "," + rowId);
            }
        }
        
    });

    
});








// WORK ASSIGNMENTS
// Displays form for a work assignment when the list's link is clicked
function selectWorkAssignment(id){

    // Lets untick all of our "Finalize Work Assignment" checkboxes
    $('input[name="complete_work_assignment"]').each(function( index ) {
        $(this).prop( "checked", false );
    });

    // First, hide all other work assignments
    $('.work_assignment_form').fadeOut();
    
    // Close all opened handoff forms
    $('#handoff_form').fadeOut();
    
    // Then, show this specific one
    $('.' + id).fadeIn();

}







// WORK ASSIGNMENTS
// The main function to send entered data to Sheets
function processWorkAssignment(id){

    var validateFailed = [];
    var validateMessage = '';
    
    
    var selectedService = $("#form_" + id + " .main_service #service_provided").find(":selected").text();
    
    // if finalize is checked and service is empty, skip validation
    if( $('input#complete_work_assignment').is(':checked') == true && selectedService == ''){
    } else {
        

        // Service Provided
        if(selectedService == '') {
            validateMessage += "Service Provided MUST NOT be empty<br>";
            validateFailed['selectedService'] = 1;
        } else {
            
            // Price
            var price = $("#form_" + id + " .main_service input#price").val();
            console.log("Price: " + price);
            if(price == '') {
                validateMessage += selectedService + ": Hourly Rate MUST NOT be empty<br>";
                validateFailed['price'] = 1;
            }
            
            // if service is a meeting type then require the data to be filled in
            if(selectedService == "Meeting" || selectedService == "Mtg Late Cancel" || selectedService == "Test Late Cancel" || selectedService == "Review District Report") {
                
                
                var meetingStart = $("#form_" + id + " .main_service input#meeting_start").val();
                if(meetingStart == '') {
                    validateMessage += selectedService + ": Start Time MUST NOT be empty<br>";
                    validateFailed['meeting_start'] = 1;
                }
                
                var meetingEnd = $("#form_" + id + " .main_service input#meeting_end").val();
                if(meetingEnd == '') {
                    validateMessage += selectedService + ": End Time MUST NOT be empty<br>";
                    validateFailed['meeting_end'] = 1;
                }
                
                var meetingDate = $("#form_" + id + " .main_service input#meeting_date").val();
                if(meetingDate == '') {
                    validateMessage += selectedService + ": Date MUST NOT be empty<br>";
                    validateFailed['meeting_date'] = 1;
                }

            }
        }
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    // VALIDATION - SUCCESS
    if($.isEmptyObject(validateFailed)) {
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
                
                console.log("RESULT: " + result);
                // redirect us to the success page
                if(result == "duplicate") {
                    
                    // re-enable our button so they can try submitting again
                    $("a#process_work_assignment").on('click');
                    $("a#process_work_assignment").removeClass("disabled");
                    
                    // Display our validation messages in a jQuery-confirm box
                    $.confirm({
                        title: 'Work Assignments - Duplicate Detected',
                        content: "An idential Transaction already exists. Please re-check your entered information.<br><br>View your current Transactions on the <strong>Review and Submit Current Invoice</strong> page.",
                        icon: 'fa fa-warning  fa-bounce',
                        type: 'red',
                        useBootstrap: false,
                        draggable: false,
                        theme: 'material',
                        buttons: {
                            confirm: {
                                text: 'OK',
                                btnClass: 'btn-red',
                                action: function(){
            
                                }
                            },
                        }
                    });
                    
                } else {
                    window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/work-assignments/submit-success.html");
                }
            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for processWorkAssignment");
            }
        });
    }
    // VALIDATION - FAILED
    else {
        // Display our validation messages in a jQuery-confirm box
        $.confirm({
            title: 'Work Assignments - Validation',
            content: validateMessage,
            icon: 'fa fa-warning  fa-bounce',
            type: 'red',
            useBootstrap: false,
            draggable: false,
            theme: 'material',
            buttons: {
                confirm: {
                    text: 'OK',
                    btnClass: 'btn-red',
                    action: function(){

                    }
                },
            }
        });
    }
    
}








// WORK ASSIGNMENTS
// Display the 
function handoffSelected(id){

    // close all handoff forms
    $('#handoff_form').fadeOut();
    
    // Then, show this specific one
    $('#form_' + id + ' #handoff_form').fadeToggle();
    

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
    
    
    
    
    
    
    
    
// ADMIN REVIEW
// Change to a specific Psychologist
function jumpToPsy(id_next){
    
    // get our current form id
    var id_current = $("input#current_psy").val();
    var hasUpdates = false;
    
    if($("form#psy_" + id_current +" input#rows").val() != '') {
        hasUpdates = true;
    }
    
    if(hasUpdates) {
        
        // open our Confirm box to block out the page
        $.confirm({
            title: 'Admin Review',
            content: "Sending changes to Google Sheets...",
            icon: 'fa fa-warning  fa-bounce',
            type: 'red',
            useBootstrap: false,
            draggable: false,
            theme: 'material',
            buttons: {
                confirm: {
                    text: 'OK',
                    isHidden: true,
                    btnClass: 'btn-red',
                    action: function(){

                    }
                },
            }
        });
        
        
        var datastring = $("form#psy_" + id_current).serialize();
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.admin.review.update.php',
            type: 'POST',
            data: datastring,
            success:function(result){
                // hide current form, show next form
                $('div.psy_' + id_current).fadeOut();
                $('div.psy_' + id_next).fadeIn();
                // update the current id to our new one
                $("input#current_psy").val(id_next);
                
                // remove the "Process to Save" messages from this form
                $( "form#psy_" + id_current +" td .update" ).each(function( index ) {
                    $(this).val("");
                });
                // remove the "Update Row IDs" values from this form
                $("form#psy_" + id_current +" input#rows").val("");
                
                scrollToAnchor('anchor_nav');
                
                // Close our Confirm box
                jconfirm.instances[0].close();
                
            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for reviewNextPsy");
            }
        });
        
    } else {
        
        // hide current form, show next form
        $('div.psy_' + id_current).fadeOut();
        $('div.psy_' + id_next).fadeIn();
        // update the current id to our new one
        $("input#current_psy").val(id_next);
    }

}



// SEND INVOICE EMAILS
// The main function to send out emails
function sendInvoiceEmails(){
    
    var datastring = $("form#send_invoice_emails").serialize();
        
    // trigger this function when our form runs
    $.ajax({
        url: '/system/modules/gai_invoices/assets/php/action.send.invoice.emails.php',
        type: 'POST',
        data: datastring,
        success:function(result){
            window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/send-invoice-emails/send-invoice-emails-success.html");
        },
        error:function(result){
            $(".message").html("There was an error using the AJAX call for sendInvoiceEmails");
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








 // This will update the user's transactions as "Reviewed"
function deleteTransaction(transaction_id){
    
    var content_start = '<div class="size100 marbot60">Please confirm you would like to REMOVE this Transaction from your Invoice</div>';
    var content_form = '' +
	'<form action="" class="formName">' +
	    '<div class="form-group">' +
	    	'<div class="size100 marbot15">Type the word "REMOVE" (All capitalized) to remove this transaction permanently from your Invoice</div>'+
	    	'<div class="size100"><input type="text" name="del_string" id="del_string"/></div>' +
	    '</div>' +
    '</form>';
    
    // Create our Confirmation alert box
    $.confirm({
        title: 'Transaction - Delete',
        content: content_start + content_form,
        icon: 'fa fa-warning  fa-bounce',
        type: 'red',
        useBootstrap: false,
        draggable: false,
        theme: 'material',
        buttons: {
            confirm: {
                text: 'DELETE',
                btnClass: 'btn-red',
                action: function(){
                    
                    
                    var ourString = this.$content.find('#del_string').val();
                    if(ourString != "REMOVE") {
                        return false;
                    } else {

                        
                        this.buttons.confirm.disable()
                        this.buttons.cancel.disable()
                        
                        var datastring = $("#form_" + transaction_id).serialize();
                        
                        // AJAX call to our php script to flag this Transaction as deleted
                        $.ajax({
                            url: '/system/modules/gai_invoices/assets/php/action.delete.transaction.php',
                            type: 'POST',
                            data: datastring,
                            success:function(result){
                                // redirect us to the success page
                                window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/review.html");
                            },
                            error:function(result){
                                $(".message").html("There was an error using the AJAX call for deleteTransaction");
                            }
                        });
                        
                        return false;
                    
                    }
                    
                    
                }
            },
            cancel: function () {
                
            },
        }
    });
}








// This generates Transactions manually for meetings without Work Assignments
function addMeeting(){

    var validated = 0;
    var validateFailed = [];
    var validateMessage = '';
    
    // District
    var district = $('select[name="district"]').children("option:selected").val();
    if(district == '' || district == 'NONE') {
        validateMessage += "District MUST NOT be empty<br>";
        validateFailed['district'] = 1;
    }
    
    // School
    var school = $('select[name="school"]').children("option:selected").val();
    if(school == '' || school == 'NONE') {
        validateMessage += "School MUST NOT be empty<br>";
        validateFailed['school'] = 1;
    }
    
    // Student Name
    var student_name = $(".mod_add_meetings #student_name").val();
    if(student_name == '') {
        validateMessage += "Student Name MUST NOT be empty<br>";
        validateFailed['student_name'] = 1;
    }
    
    // Both LASID and SASID being empty
    var las = $(".mod_add_meetings #sasid").val();
    var sas = $(".mod_add_meetings #lasid").val();
    if(las == '' && sas == '') {
        validateMessage += "Both LASID and SASID MUST NOT be empty<br>";
        validateFailed['las_sas'] = 1;
    }
    
    // Service Provided
    var selectedService = $(".mod_add_meetings #service_provided").find(":selected").text();
    if(selectedService == '') {
        validateMessage += "Meeting Provided MUST NOT be empty<br>";
        validateFailed['selectedService'] = 1;
    }
    
    // Price
    var price = $(".mod_add_meetings #price").val();
    if(price == '') {
        validateMessage += "Hourly Rate MUST NOT be empty<br>";
        validateFailed['price'] = 1;
    }
    
    // Meeting Start
    var meeting_start = $(".mod_add_meetings #meeting_start").val();
    if(meeting_start == '') {
        validateMessage += "Meeting Start MUST NOT be empty<br>";
        validateFailed['meeting_start'] = 1;
    }
    
    // Meeting End
    var meeting_end = $(".mod_add_meetings #meeting_end").val();
    if(meeting_end == '') {
        validateMessage += "Meeting End MUST NOT be empty<br>";
        validateFailed['meeting_end'] = 1;
    }
    
    // Meeting Date
    var meeting_date = $(".mod_add_meetings input#meeting_date").val();
    if(meeting_date == '') {
        validateMessage += "Meeting Date MUST NOT be empty<br>";
        validateFailed['meeting_date'] = 1;
    }
    
    

    if($.isEmptyObject(validateFailed)) {
        // get every form field and add them to the ajax data line
        var datastring = $("#form_add_meeting").serialize();
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.add.meeting.php',
            type: 'POST',
            data: datastring,
            success:function(result){
                
                // redirect us to the success page
                if(result == "duplicate") {
                    
                    // re-enable our button so they can try submitting again
                    $("a#process_work_assignment").on('click');
                    $("a#process_work_assignment").removeClass("disabled");
                    
                    // Display our validation messages in a jQuery-confirm box
                    $.confirm({
                        title: 'Add Meeting - Duplicate Detected',
                        content: "An idential Transaction already exists. Please re-check your entered information.<br><br>View your current Transactions on the <strong>Review and Submit Current Invoice</strong> page.",
                        icon: 'fa fa-warning  fa-bounce',
                        type: 'red',
                        useBootstrap: false,
                        draggable: false,
                        theme: 'material',
                        buttons: {
                            confirm: {
                                text: 'OK',
                                btnClass: 'btn-red',
                                action: function(){
            
                                }
                            },
                        }
                    });
                    
                } else {
                    // redirect us to the success page
                    window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/add-meetings/success.html");
                }
            
            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for addMeeting");
            }
        });
    } else {
        // Create our Confirmation alert box
        $.confirm({
            title: 'Add Meeting - Validation',
            content: validateMessage,
            icon: 'fa fa-warning  fa-bounce',
            type: 'red',
            useBootstrap: false,
            draggable: false,
            theme: 'material',
            buttons: {
                confirm: {
                    text: 'OK',
                    btnClass: 'btn-red',
                    action: function(){

                    }
                },
            }
        });
    }

}








    // This is for misc. billing
function addMiscBilling(){

    $(".message").empty();
    
    
    var validated = 0;
    
    var validateFailed = [];
    
    // Label
    var label = $(".mod_misc_billing #label").val();
    if(label == '') {
        $(".message").append("Label cannot be empty<br>");
        validateFailed['label'] = 1;
    }

    // Price
    var price = $(".mod_misc_billing #price").val();
    if(price == '') {
        $(".message").append("Price cannot be empty<br>");
        validateFailed['price'] = 1;
    }
    
    // Notes
    var price = $(".mod_misc_billing #notes").val();
    if(price == '') {
        $(".message").append("Price cannot be empty<br>");
        validateFailed['price'] = 1;
    }

    
    
    if($.isEmptyObject(validateFailed)) {
        // get every form field and add them to the ajax data line
        var datastring = $("#form_misc_billing").serialize();
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.misc.billing.php',
            type: 'POST',
            data: datastring,
            success:function(result){
                
                // redirect us to the success page
                if(result == "duplicate") {
                    
                    // re-enable our button so they can try submitting again
                    $("a#process_work_assignment").on('click');
                    $("a#process_work_assignment").removeClass("disabled");
                    
                    // Display our validation messages in a jQuery-confirm box
                    $.confirm({
                        title: 'Misc. Billing - Duplicate Detected',
                        content: "An idential Transaction already exists. Please re-check your entered information.<br><br>View your current Transactions on the <strong>Review and Submit Current Invoice</strong> page.",
                        icon: 'fa fa-warning  fa-bounce',
                        type: 'red',
                        useBootstrap: false,
                        draggable: false,
                        theme: 'material',
                        buttons: {
                            confirm: {
                                text: 'OK',
                                btnClass: 'btn-red',
                                action: function(){
            
                                }
                            },
                        }
                    });
                    
                } else {
                    // redirect us to the success page
                    window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/misc-billing/success.html");
                }
                
                
                
            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for addMiscBilling()");
            }
        });
    }

}








function scrollToAnchor(aid){
    var aTag = $("a[name='"+ aid +"']");
    $('html,body').animate({scrollTop: aTag.offset().top-200},'slow');
}
