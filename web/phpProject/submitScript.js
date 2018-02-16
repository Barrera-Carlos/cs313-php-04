function submitItem() {
    var textInput = document.getElementsByClassName('container');
    if(textInput === ''){
        textInput.action = "/QuestionSelect.php"
    }
    else
        textInput.action = "/phpQuerying.php";

    document.getElementById("submit").click();
}