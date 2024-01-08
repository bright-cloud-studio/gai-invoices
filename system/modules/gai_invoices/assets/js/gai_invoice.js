
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
                            
                        
                        // display our loading symbol
                        
                            
                        
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
    // if a price value is changed
    $( ".mod_admin_review .price" ).change(function() {
        
        // find theh closest parent tr, find the nearest update field, add our copy
        $(this).closest('tr').find(".update").val("Process to Save");
        
        // get the hidden row id field value
        var rowId = $(this).closest('tr').find(".row_id").val();
        
        // get the hidden psychologist name field value
        var psyName = $(this).closest("tr").find(".psy_name").val();
        // set our hidden update psy name field with the value
        $(this).closest('form').find("#update_psy_name").val(psyName);
        
        // change the button text to show changes will be processed
        $(this).closest('form').find(".process_review").text('Process Changes');
        
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
        
        // get the hidden psychologist name field value
        var psyName = $(this).closest("tr").find(".psy_name").val();
        // set our hidden update psy name field with the value
        $(this).closest('form').find("#update_psy_name").val(psyName);
        
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
        
        // get the hidden psychologist name field value
        var psyName = $(this).closest("tr").find(".psy_name").val();
        // set our hidden update psy name field with the value
        $(this).closest('form').find("#update_psy_name").val(psyName);
        
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

    if($("#form_" + id + " a#process_work_assignment").hasClass("disabled"))
        return;

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
        
        // add disabled class
        $("#form_" + id + " a#process_work_assignment").addClass("disabled");

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
                    $("#form_" + id + " a#process_work_assignment").removeClass("disabled");
                    
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
function adminReviewChangeSelectedPsychologist(id_next){
    
    // ID of currently selected psychologist
    var id_current = $("input#current_psy").val();
        
    // hide current form, show next form
    $('div.psy_' + id_current).fadeOut();
    $('div.psy_' + id_next).fadeIn();
    
    // update the current id to our new one
    $("input#current_psy").val(id_next);
    
    // scroll the page back to the top
    scrollToAnchor('anchor_nav');

}






// Send edit requests to Sheets and change to another psychologist if requested
function processReviewChanges(id_next){
    
    // ID of currently selected psychologist
    var id_current = $("input#current_psy").val();
    // Keeps track of if updates have been made
    var hasUpdates = false;
    
    // If the current psychologist form has "Updated Row IDs" listed, meaning there are edits being requested
    if($("form#psy_" + id_current +" input#rows").val() != '') {
        hasUpdates = true;
    }
    
    // If updates are present
    if(hasUpdates) {
        
        // Show our confirm box to let Ed know updates are being processed
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
        
        // Serialize our entire form so we can send it to our php script
        var datastring = $("form#psy_" + id_current).serialize();
        
        // Send serialized form data to our php script
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.admin.review.update.php',
            type: 'POST',
            data: datastring,
            success:function(result){
                
                
                // hide current form and show the next
                $('div.psy_' + id_current).fadeOut();
                $('div.psy_' + id_next).fadeIn();
                
                
                // update the current id to our new one
                $("input#current_psy").val(id_next);
                
                // remove the "Process to Save" messages from this form
                $( "form#psy_" + id_current +" td .update" ).each(function( index ) {
                    $(this).val("");
                });
                // remove the "Update Row IDs" values from this form
                $("form#psy_" + id_current + " input#rows").val("");
                
                
                
                
                
                
                // Get the name of the current psychologist, used to build id/class
                var psyName = $("form#psy_" + id_current + " input#update_psy_name").val();
                // update that psy on the Navigation menu to mark changes have been made
                $(".mod_admin_review_nav #" + psyName).addClass("updated");
                // remove the "Psychologist Name" value
                $("form#psy_" + id_current + " input#update_psy_name").val("");
                
                // Change button text back to the default
                $("form#psy_" + id_current + " a.process_review").text("Mark As Reviewed");
                
                // Close our Confirm box
                jconfirm.instances[0].close();
                
            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for reviewNextPsy");
            }
        });
        
    } else {
        
        // Get the psy name from the nav
        var psyName = $('.mod_admin_review_nav div.psy_id_' + id_current).attr('id');
        $(".mod_admin_review_nav #" + psyName).addClass("updated");
        
        
        // hide current form, show next form
        $('div.psy_' + id_current).fadeOut();
        $('div.psy_' + id_next).fadeIn();
        
        // update the current id to our new one
        $("input#current_psy").val(id_next);
    }
    
    // Scroll to top of page
    scrollToAnchor('anchor_nav');

}










// This will update the user's transactions as "Reviewed"
function reviewTransactions(id){
    
    // Show our confirm box to let Ed know updates are being processed
    $.confirm({
        title: 'Review Monthly Submission',
        content: "Processing your review...",
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
            
            // If there was an error we wont be redirected so close the alert box
            jconfirm.instances[0].close();
            
        }
    });

}








 // This will update the user's transactions as "Reviewed"
function deleteTransaction(transaction_id){
    
    var content_start = '<div id="del_text" class="size100 marbot60">Please confirm you would like to REMOVE this Transaction from your Invoice</div>';
    content_start = content_start + '<div id="del_loading" class="size100 marbot60" style="display: none; text-align: center;">Loading...<br><br><i class="fa-duotone fa-spinner fa-spin-pulse"></i></div>';
    var content_form = '' +
	'<form action="" id="del_form" class="formName">' +
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
                        
                        
                        // hide our normal content
                        $('#del_text').hide();
                        $('#del_form').hide();
                        
                        $('#del_loading').show();
                        // show our loading content
                        
                        
                        
                        var datastring = $("#form_" + transaction_id).serialize();
                        
                         console.log("delete: starting ajax");
                        
                        // AJAX call to our php script to flag this Transaction as deleted
                        $.ajax({
                            url: '/system/modules/gai_invoices/assets/php/action.delete.transaction.php',
                            type: 'POST',
                            data: datastring,
                            success:function(result){
                                console.log("delete: success");
                                // redirect us to the success page
                                window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/review.html");
                            },
                            error:function(result){
                                 console.log("delete: error");
                                 console.log(result);
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
    
    if($("a#process_work_assignment").hasClass("disabled"))
        return;

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
    
    
    var service_id = $(".mod_add_meetings #service_provided").find(":selected").val();
    if(service_id == '1' || service_id == '12' || service_id == '13' || service_id == '15') {
        // Meeting Start
        var meeting_start = $(".mod_add_meetings input#meeting_start").val();
        if(meeting_start == '') {
            validateMessage += "Meeting Start MUST NOT be empty<br>";
            validateFailed['meeting_start'] = 1;
        }
        
        // Meeting End
        var meeting_end = $(".mod_add_meetings input#meeting_end").val();
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
    }
    
    

    if($.isEmptyObject(validateFailed)) {
        
        // add disabled class
        $("a#process_work_assignment").addClass("disabled");
        
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
    
    // If Validation failed
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




// This is for misc. billing
function addMiscTravelExpenses(){

    $(".message").empty();
    
    var validated = 0;
    var validateFailed = [];
    
    // Label
    var label = $(".mod_misc_travel_expenses #label").val();
    if(label == '') {
        $(".message").append("Label cannot be empty<br>");
        validateFailed['label'] = 1;
    }

    // Price
    var price = $(".mod_misc_travel_expenses #price").val();
    if(price == '') {
        $(".message").append("Price cannot be empty<br>");
        validateFailed['price'] = 1;
    }
    
    // If Validation failed
    if($.isEmptyObject(validateFailed)) {
        // get every form field and add them to the ajax data line
        var datastring = $("#form_misc_travel_expenses").serialize();
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.misc.travel.expenses.php',
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
                        title: 'Misc. Travel Expenses - Duplicate Detected',
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
                    window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/misc-travel-expenses/success.html");
                }

            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for addMiscTravelExpenses()");
            }
        });
    }

}






// This is for misc. billing
function addParking(){

    $(".message").empty();
    
    var validated = 0;
    var validateFailed = [];
    
    // Label
    var label = $(".mod_parking #label").val();
    if(label == '') {
        $(".message").append("Label cannot be empty<br>");
        validateFailed['label'] = 1;
    }

    // Price
    var price = $(".mod_parking #price").val();
    if(price == '') {
        $(".message").append("Price cannot be empty<br>");
        validateFailed['price'] = 1;
    }
    
    // If Validation failed
    if($.isEmptyObject(validateFailed)) {
        // get every form field and add them to the ajax data line
        var datastring = $("#form_parking").serialize();
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.parking.php',
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
                        title: 'Parking - Duplicate Detected',
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
                    window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/parking/success.html");
                }

            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for addParking()");
            }
        });
    }

}









// This is for misc. billing
function addManager(){

    $(".message").empty();
    
    var validated = 0;
    var validateFailed = [];
    
    // Label
    var label = $(".mod_manager #label").val();
    if(label == '') {
        $(".message").append("Label cannot be empty<br>");
        validateFailed['label'] = 1;
    }

    // Price
    var price = $(".mod_manager #price").val();
    if(price == '') {
        $(".message").append("Price cannot be empty<br>");
        validateFailed['price'] = 1;
    }
    
    // If Validation failed
    if($.isEmptyObject(validateFailed)) {
        // get every form field and add them to the ajax data line
        var datastring = $("#form_manager").serialize();
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.manager.php',
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
                        title: 'Manager - Duplicate Detected',
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
                    window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/manager/success.html");
                }

            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for addManager()");
            }
        });
    }

}









// This is for misc. billing
function addEditingServices(){

    $(".message").empty();
    
    var validated = 0;
    var validateFailed = [];
    
    // Total Minutes
    var total_minutes = $(".mod_editing_services #total_minutes").val();
    if(total_minutes == '') {
        $(".message").append("Total Minutes cannot be empty<br>");
        validateFailed['total_minutes'] = 1;
    }

    // Price
    var price = $(".mod_editing_services #price").val();
    if(price == '') {
        $(".message").append("Price cannot be empty<br>");
        validateFailed['price'] = 1;
    }
    
    
    // If Validation failed
    if($.isEmptyObject(validateFailed)) {
        // get every form field and add them to the ajax data line
        var datastring = $("#form_editing_services").serialize();
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.editing.services.php',
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
                        title: 'Editing Services - Duplicate Detected',
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
                    window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/editing-services/success.html");
                }

            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for addEditingServices()");
            }
        });
    }

}






// This is for Test Late Cancel First
function addTestLateCancelFirst(){

    $(".message").empty();
    
    var validated = 0;
    var validateFailed = [];
    
    // Label
    var label = $(".mod_test_late_cancel_first #label").val();
    if(label == '') {
        $(".message").append("Label cannot be empty<br>");
        validateFailed['label'] = 1;
    }

    // Price
    var price = $(".mod_test_late_cancel_first #price").val();
    if(price == '') {
        $(".message").append("Price cannot be empty<br>");
        validateFailed['price'] = 1;
    }
    
    // If Validation failed
    if($.isEmptyObject(validateFailed)) {
        // get every form field and add them to the ajax data line
        var datastring = $("#form_test_late_cancel_first").serialize();
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.test.late.cancel.first.php',
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
                        title: 'First Test Late Cancel - Duplicate Detected',
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
                    window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/first-test-late-cancel/success.html");
                }

            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for addTestLateCancelFirst()");
            }
        });
    }

}





// This is for Test Late Cancel Additional
function addTestLateCancelAdditional(){

    $(".message").empty();
    
    var validated = 0;
    var validateFailed = [];
    
    // Label
    var label = $(".mod_test_late_cancel_additional #label").val();
    if(label == '') {
        $(".message").append("Label cannot be empty<br>");
        validateFailed['label'] = 1;
    }

    // Price
    var price = $(".mod_test_late_cancel_additional #price").val();
    if(price == '') {
        $(".message").append("Price cannot be empty<br>");
        validateFailed['price'] = 1;
    }
    
    // If Validation failed
    if($.isEmptyObject(validateFailed)) {
        // get every form field and add them to the ajax data line
        var datastring = $("#form_test_late_cancel_additional").serialize();
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.test.late.cancel.additional.php',
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
                        title: 'Additional Test Late Cancel - Duplicate Detected',
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
                    window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/additional-test-late-cancel/success.html");
                }

            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for addTestLateCancelAdditional()");
            }
        });
    }

}






function scrollToAnchor(aid){
    var aTag = $("a[name='"+ aid +"']");
    $('html,body').animate({scrollTop: aTag.offset().top-200},'slow');
}
