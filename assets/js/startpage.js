$( "#inputProject" ).change(function() {
    $( "#btnAssign" ).href = "/startpage/assigndevs/company/" + $( "#inputCompany" ).val() + "/project/" + $(this).value;
    $( "#btnReport" ).href = "/resultpage/index/project/" + $(this).value;
});

$( "#inputCompany" ).change(function() {
    $( "#btnAssign" ).href = "/startpage/assigndevs/company/" + $(this).val()+ "/project/" + $( "#inputProject" ).value ;
    $( "#btnReport" ).href = "/resultpage/index/project/" + $( "#inputProject" ).value ;
});



