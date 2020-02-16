function getDataForm(url, header='<h3>Add Staff</h3>'){
    $("#accreditation-modal").modal();
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

function saveDataForm(clickedButton){
    
    var url = $(clickedButton.form).attr('action');
    var data = new FormData(clickedButton.form)
    console.log(data)
    console.log(clickedButton.form.action)
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        contentType: false,       
        cache: false,             
        processData:false,
        success: function(result){
            //$('#modalHeader').html('<h3>Add Staff</h3>');
            $('div#modalContent').html(result);
            //$.pjax.reload({container: "#staff-data-list", async: false});
            //$( "#companystaff-dob" ).datepicker();
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