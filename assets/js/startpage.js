$("#inputProject").change(function () {
    $("#btnAssign").attr('href',"/startpage/assigndevs/company/" + $("#inputCompany").val() + "/project/" + $("#inputProject").val());
    $("#btnReport").attr('href',"/resultpage/index/project/" + $("#inputProject").val());

});
$("#inputCompany").change(function () {
    $("#btnAssign").attr('href',"/startpage/assigndevs/company/" + $("#inputCompany").val() + "/project/" + $("#inputProject").val());
    $("#btnReport").attr('href',"/resultpage/index/project/" + $("#inputProject").val());

});


