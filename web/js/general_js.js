
function getDataForm(url, header='<h3>Add Staff</h3>'){
    $("#accreditation-modal").modal();
    //basePathWeb is defined in main.php layout file
    $('div#modalContent').html('<img src="' + basePathWeb + '/images/ajax-loader-blue.gif"/><p>Please wait ...</p>');
    //setTimeout(() => {  console.log("World!"); }, 5000);
    $.ajax({
        type: "GET",
        url: url,            
        success: function(result){
            $('#modalHeader').html(header);
            $('div#modalContent').html(result);
            //$( "#companystaff-dob" ).datepicker();
        },
        error:function(jqXHR){
            $('#modalHeader').html("Error Occured!");
            $('div#modalContent').html("<p style='color:red'>" + jqXHR.responseText + '</p>');
        }
    });
}

function saveDataForm(clickedButton, contentDivID='', tabToOpen=''){
    
    var url = $(clickedButton.form).attr('action');
    var data = new FormData(clickedButton.form);
    //basePathWeb is defined in main.php layout file
    $('div#modalContent').html('<img src="' + basePathWeb + '/images/ajax-loader-blue.gif"/> <p>Please wait ...</p>');
    //console.log(data)
    //console.log(clickedButton.form.action)
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        contentType: false,       
        cache: false,             
        processData:false,
        success: function(result){
            if(contentDivID){
                $('div#' + contentDivID).html(result);
            }else{
                $('div#modalContent').html(result);
            }
        },
        error:function(jqXHR){
            $('#modalHeader').html("Error Occured!");
            $('div#modalContent').html("<p style='color:red'>" + jqXHR.responseText + '</p>');
        }
    });
}

function ajaxDeleteRecord(url, returnUrl,header){
    $.ajax({
        type: "POST",
        url: url,
        success: function(){
            $.ajax({
                type: "GET",
                url: returnUrl,
                success: function(result){
                    $('#modalHeader').html(header);
                    $('div#modalContent').html(result);
                },        
            });
        },        
    });
}