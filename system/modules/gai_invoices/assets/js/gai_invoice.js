
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
        cloned.find('input#price').val("");
        cloned.find('input#price').attr('name', 'price_' + transCount);
        cloned.find('input#price').attr('id', 'price_' + transCount);
        
        // Modify the cloned "Meeting Start" field to have a unique ID
        cloned.find("label[for='meeting_start']").attr('for', 'meeting_start_' + transCount);
        cloned.find('input#meeting_start').val("");
        cloned.find('input#meeting_start').attr('name', 'meeting_start_' + transCount);
        cloned.find('input#meeting_start').attr('id', 'meeting_start_' + transCount);
        
        // Modify the cloned "Meeting End" field to have a unique ID
        cloned.find("label[for='meeting_end']").attr('for', 'meeting_end_' + transCount);
        cloned.find('input#meeting_end').val("");
        cloned.find('input#meeting_end').attr('name', 'meeting_end_' + transCount);
        cloned.find('input#meeting_end').attr('id', 'meeting_end_' + transCount);
        
        // Modify the cloned "Meeting Date" field to have a unique ID
        cloned.find("label[for='meeting_date']").attr('for', 'meeting_date_' + transCount);
        cloned.find('input#meeting_date').val("");
        cloned.find('input#meeting_date').attr('name', 'meeting_date_' + transCount);
        cloned.find('input#meeting_date').attr('id', 'meeting_date_' + transCount);
        
        // Modify the cloned "Notes" field to have a unique ID
        cloned.find("label[for='notes']").attr('for', 'notes_' + transCount);
        cloned.find('textarea#notes').val("");
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

        $(".message").empty();
        
        var validateFailed = [];
        
        
        
        if( $('input#complete_work_assignment').is(':checked') == false){

            // Service Provided
            var selectedService = $("#form_" + id + " .main_service #service_provided").find(":selected").text();
            if(selectedService == '') {
                $(".message").append("Service Provided cannot be empty<br>");
                validateFailed['selectedService'] = 1;
            }
            
            // Price
            var price = $("#form_" + id + " .main_service #price").val();
            if(price == '') {
                $(".message").append("Price cannot be empty<br>");
                validateFailed['price'] = 1;
            }
            
            
            var transCount = $('.' + id + ' .transactions fieldset.transaction').length ;
            
            var selectedMeeting = [];
            var meetingPrice = [];
            var meetingTimeStart = [];
            var meetingTimeEnd = [];
            var meetingDate = [];
            
            // loop through each meeting and validate
            for(var i = 2; i <= transCount; i++)
            {
                
                // meeting provided
                selectedMeeting[i] = $("#form_" + id + " #service_provided_" + i).find(":selected").text();
                if(selectedMeeting[i] == '') {
                    $(".message").append("Meeting Provided cannot be empty<br>");
                    validateFailed['selected_meeting'] = 1;
                }
    
                // Price
                meetingPrice[i] = $("#form_" + id + " #price_" + i).val();
                if(meetingPrice[i] == '') {
                    $(".message").append("Meeting Price " + i + " cannot be empty<br>");
                    validateFailed['meeting_price'] = 1;
                }
                
                // Start Time
                meetingTimeStart[i] = $("#form_" + id + " #meeting_start_" + transCount).val();
                if(meetingTimeStart[i] == '') {
                    $(".message").append("Meeting Start Time " + i + " cannot be empty<br>");
                    validateFailed['meeting_start'] = 1;
                }
                
                // End Time
                meetingTimeEnd[i] = $("#form_" + id + " #meeting_end_" + transCount).val();
                if(meetingTimeEnd[i] == '') {
                    $(".message").append("Meeting End Time " + i + " cannot be empty<br>");
                    validateFailed['meeting_end'] = 1;
                }
                
                // Meeting Date
                meetingDate[i] = $("#form_" + id + " #meeting_date_" + transCount).val();
                if(meetingDate[i] == '') {
                    $(".message").append("Meeting Date " + i + " cannot be empty<br>");
                    validateFailed['meeting_date'] = 1;
                }
                
                
            }
        
        }

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
                    // redirect us to the success page
                    window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/work-assignments/submit-success.html");
                },
                error:function(result){
                    $(".message").html("There was an error using the AJAX call for processWorkAssignment");
                }
            });
        }
        
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
    
    // This generates Transactions manually for meetings without Work Assignments
    function addMeeting(){

        $(".message").empty();
        
        
        var validated = 0;
        
        var validateFailed = [];
        
        // District
        var district = $(".mod_add_meetings #district").val();
        if(district == '') {
            $(".message").append("District cannot be empty<br>");
            validateFailed['district'] = 1;
        }
        
        // School
        var school = $(".mod_add_meetings #school").val();
        if(school == '') {
            $(".message").append("School cannot be empty<br>");
            validateFailed['school'] = 1;
        }
        
        // Student Name
        var student_name = $(".mod_add_meetings #student_name").val();
        if(student_name == '') {
            $(".message").append("Student Name cannot be empty<br>");
            validateFailed['student_name'] = 1;
        }
        
        // Date of Birth
        /*
        var date_of_birth = $(".mod_add_meetings #date_of_birth").val();
        if(date_of_birth == '') {
            $(".message").append("Date of Birth cannot be empty<br>");
            validateFailed['date_of_birth'] = 1;
        }
        */
        
        // LASID SASID
        
        // Service Provided
        var selectedService = $(".mod_add_meetings #service_provided").find(":selected").text();
        if(selectedService == '') {
            $(".message").append("Meeting Provided cannot be empty<br>");
            validateFailed['selectedService'] = 1;
        }
        
        // Price
        var price = $(".mod_add_meetings #price").val();
        if(price == '') {
            $(".message").append("Price cannot be empty<br>");
            validateFailed['price'] = 1;
        }
        
        // Meeting Start
        var meeting_start = $(".mod_add_meetings #meeting_start").val();
        if(meeting_start == '') {
            $(".message").append("Meeting Start cannot be empty<br>");
            validateFailed['meeting_start'] = 1;
        }
        
        // Meeting End
        var meeting_end = $(".mod_add_meetings #meeting_end").val();
        if(meeting_end == '') {
            $(".message").append("Meeting End cannot be empty<br>");
            validateFailed['meeting_end'] = 1;
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
                    window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/add-meetings/success.html");
                },
                error:function(result){
                    $(".message").html("There was an error using the AJAX call for addMeeting");
                }
            });
        }

    }
    
    
     // This will update the user's transactions as "Reviewed"
    function deleteTransaction(id){
        
        // trigger this function when our form runs
        $.ajax({
            url: '/system/modules/gai_invoices/assets/php/action.delete.transaction.php',
            data:"row_id="+id,
            type: 'POST',
            success:function(result){
                // redirect us to the success page
                window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/review.html");
            },
            error:function(result){
                $(".message").html("There was an error using the AJAX call for deleteTransaction");
            }
        });

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
                    window.location.replace("https://www.globalassessmentsinc.com/payments/dashboard/misc-billing/success.html");
                },
                error:function(result){
                    $(".message").html("There was an error using the AJAX call for addMiscBilling()");
                }
            });
        }

    }
    
