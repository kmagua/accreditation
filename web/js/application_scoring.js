function calculateCategoty(score){
    if(score > 84 ){
        $('#applicationscore-classification').val("ICTA 1");
        $('#applicationscore-status').val(1);
    }else if(score > 74 ){
        $('#applicationscore-classification').val("ICTA 2");
        $('#applicationscore-status').val(1);
    }else if(score > 64 ){
        $('#applicationscore-classification').val("ICTA 3");
        $('#applicationscore-status').val(1);
    }else if(score > 54 ){
        $('#applicationscore-classification').val("ICTA 4");
        $('#applicationscore-status').val(1);
    }else if(score > 44 ){
        $('#applicationscore-classification').val("ICTA 5");
        $('#applicationscore-status').val(1);
    }else if(score > 34 ){
        $('#applicationscore-classification').val("ICTA 6");
        $('#applicationscore-status').val(1);
    }else if(score > 24 ){
        $('#applicationscore-classification').val("ICTA 7");
        $('#applicationscore-status').val(1);
    }else if(score > 9 ){
        $('#applicationscore-classification').val("ICTA 8");
        $('#applicationscore-status').val(1);
    }else if(score < 10 ){
        $('#applicationscore-classification').val("reapply");
        $('#applicationscore-status').val(0);
    }
}

function clearInSimilarClass(cur_element){
    var element_class = $("#" + cur_element.id).attr('class');
    
    if(element_class != undefined){
        $("input:checkbox." + element_class).each(function(){
            if(this.checked == true && (this.id != cur_element.id)){                
                this.click();
            }
        });
    }
}

function validateForm(){
    if(Number($('#applicationscore-status').val()) == 0 && $('#applicationscore-rejection_comment').val() == ''){
        $("#applicationscore-rejection_comment").css("border", "2px solid red");
        alert("You must provide a comment for rejected applications.")
        return false;
    }
    return true;
}