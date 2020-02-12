function getStaffForm(url){
    $("#accreditation-modal").modal();
    $.ajax({
        type: "GET",
        url: url,            
        success: function(result){
            $('#modalHeader').text('Chair Approval');
            $('div#modalContentInner').html(result);
            $( "#chair_approval_date" ).datepicker();
        },        
    });        
}