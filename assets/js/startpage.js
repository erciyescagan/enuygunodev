$( "#inputProject" ).change(function() {
    $( "#btnAssign" ).href = "/startpage/assigndevs/company/" + $( "#inputCompany" ).value + "/project/" + $( "#inputProject" ).value;
    $( "#btnReport" ).href = "/resultpage/index/project/" + $( "#inputProject" ).value;
    console.log($( "#inputProject" ).value);
});

$( "#inputCompany" ).change(function() {
    $( "#btnAssign" ).href = "/startpage/assigndevs/company/" + $( "#inputCompany" ).value + "/project/" + $( "#inputProject" ).value ;
    $( "#btnReport" ).href = "/resultpage/index/project/" + $( "#inputProject" ).value ;
    console.log($( "#inputCompany" ).value );
});



