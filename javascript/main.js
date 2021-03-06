var canUploadFile = false;
var hasUploadPresentationName = false;
var hasUploadPresentationFile = false;

function getPresentationsRequest() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/presentations-list.php");
    xhr.onload = function () {
        var data = JSON.parse(this.response);
        console.log(data);

        document.getElementById("presentations-list").innerHTML = "";

        var list = document.createElement("div");
        for (let presentationData of data) {
            var presentationElement = document.createElement("div");
            presentationElement.id = "presentation";
            
            var innerLink = document.createElement("a");
            innerLink.setAttribute("href", "editor.html?presentation=" + presentationData);
            innerLink.text = presentationData;

            presentationElement.append(innerLink);
            
            list.append(presentationElement);
        }
        document.getElementById("presentations-list").append(list);
    }
    xhr.send();
}

function uploadPresentationRequest() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/upload-presentation.php");

    let formData = new FormData();
    formData.append("file", document.getElementById("presentationFile").files[0]);
    formData.append("presentationName", document.getElementById("presentationName").value);

    document.getElementById("presentationName").value = "";
    document.getElementById("presentationFile").value = "";
    canUploadFile = false;
    hasUploadPresentationName = false;
    hasUploadPresentationFile = false;
    refreshButtons();

    xhr.onload = function () {
        getPresentationsRequest();
    }
    xhr.send(formData);
}

function refreshButtons() {
    if (!canUploadFile) {
        document.getElementById("upload-presentation").setAttribute("disabled", "true");
    } else {
        document.getElementById("upload-presentation").removeAttribute("disabled");
    }
}

function checkIfCanUploadFiles() {
    hasUploadPresentationFile = document.getElementById("presentationFile").value.length; 
    hasUploadPresentationName = document.getElementById("presentationName").value.length; 
    canUploadFile = hasUploadPresentationName && hasUploadPresentationFile;
    refreshButtons();
}

function init() {
    document.getElementById("upload-presentation").onclick = uploadPresentationRequest;
    document.getElementById("presentationFile").oninput = checkIfCanUploadFiles;
    document.getElementById("presentationName").oninput = checkIfCanUploadFiles;
    
    getPresentationsRequest();
    refreshButtons();

    uploadPresentationButton = document.getElementById('presentationFile');
    fileChosen = document.getElementById('file-chosen');
    uploadPresentationButton.addEventListener('change', function(){
        fileChosen.textContent = this.files[0].name
    })

}
window.onload = init;
