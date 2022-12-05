
    // Changes which Work Assignment Form is visible when clicking a Work Asssignment List item
    function selectWorkAssignment(id){

        // First, hide all other work assignments
        $('.work_assignment_form').fadeOut();
        
        // Then, show this specific one
        $('.' + id).fadeIn();

    }
    
    
    // Adds another Transaction fieldset to the specific form
    function addAnotherTransaction(id){

        var transCount = $('.' + id + ' fieldset.transaction').length + 1;
        
        // first, lets clone the first transaction
        var cloned =  $('.' + id + ' fieldset.transaction:first').clone();
        
        // unique ID for Services Provided
        cloned.find("label[for='service_provided']").attr('for', 'service_provided_' + transCount);
        cloned.find('select#service_provided').val(' ');
        cloned.find('select#service_provided').attr('name', 'service_provided_' + transCount);
        cloned.find('select#service_provided').attr('id', 'service_provided_' + transCount);
        
        // unqiue ID for Price
        cloned.find("label[for='price']").attr('for', 'price_' + transCount);
        cloned.find('input#price').val(" ");
        cloned.find('input#price').attr('name', 'price_' + transCount);
        cloned.find('input#price').attr('id', 'price_' + transCount);
        
        // unqiue ID for Notes
        cloned.find("label[for='notes']").attr('for', 'notes_' + transCount);
        cloned.find('textarea#notes').val(" ");
        cloned.find('textarea#notes').attr('name', 'notes_' + transCount);
        cloned.find('textarea#notes').attr('id', 'notes_' + transCount);
        
        // lets append the cloned element to the transactions list
        cloned.appendTo('.' + id + ' .transactions');

    }
    
    
    // this is the big bad booty daddy that will generate our Transactions on Google Sheets and mark our Work Assignment as Processed
    function processWorkAssignment(id){
        alert("PROCESS: " + id);
    }
    
    
    
