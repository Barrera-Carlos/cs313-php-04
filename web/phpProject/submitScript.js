function submitItem() {
    var input = document.getElementById('inputText').value;
    var textInput = document.getElementsByClassName('container');
    if(input === ''){
        textInput.action = "/QuestionSelect.php"
    }
    else
        textInput.action = "/phpQuerying.php";

    document.getElementById("submit").click();
}