var globalParams = new URLSearchParams(window.location.search);
var slideId = 1;
var presentationSlideCount = -1;
var isSlimCodeEdited = false;

function getSlideSlimCode() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/slide.php");

    const params = new URLSearchParams();
    params.set("slide-id", slideId);
    params.set("presentation", globalParams.get("presentation"));

    xhr.onload = function () {
        var data = JSON.parse(this.response);
        var slimCode = document.createElement("textarea");
        slimCode.id = "textarea-code";
        slimCode.setAttribute("rows", "50");
        slimCode.setAttribute("cols", "90");
        slimCode.oninput = onCodeEdit;
        slimCode.value = data.slimCode;
        document.getElementById("slide-slim-code").innerHTML = "";
        document.getElementById("slide-slim-code").append(slimCode);
        getSlidePreview();
    }
    xhr.send(params);
}

function getSlidePreview() {
    // xhr.open("POST", "php/slide-preview.php");

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost:1337");
    const params = new URLSearchParams();
    params.set("slide-id", slideId);
    params.set("presentation", globalParams.get("presentation"));
    params.set("code", document.getElementById("textarea-code").value);

    xhr.onload = function () {
        var previewElement = document.createElement("iframe");
        previewElement.setAttribute("width", "756");
        previewElement.setAttribute("height", "756");
        // var data = JSON.parse(this.response);
        // previewElement.setAttribute("srcdoc", data.preview);
        // previewElement.setAttribute("srcdoc", this.repsonse);
        // previewElement.setAttribute("src", "http://localhost:1337");
        document.getElementById("slide-preview").innerHTML = "";
        document.getElementById("slide-preview").append(previewElement);

        previewElement.contentWindow.document.open();
        previewElement.contentWindow.document.write(this.response);
        previewElement.contentWindow.document.close();
    }
    xhr.send(params);
}

function saveSlimCode() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/save-slide.php");

    const params = new URLSearchParams();
    params.set("slide-id", slideId);
    params.set("presentation", globalParams.get("presentation"));
    params.set("code", document.getElementById("textarea-code").value);
    
    xhr.onload = function () {
        var data = JSON.parse(this.response); 
        getSlidePreview();
        isSlimCodeEdited = false;
        refreshButtons();
    }
    xhr.send(params);
}

function updateSlideCount() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/slide-count.php");

    const params = new URLSearchParams();
    params.set("presentation", globalParams.get("presentation"));
    
    xhr.onload = function () {
        var data = JSON.parse(this.response);   
        presentationSlideCount = data.count;
        refreshButtons();
    }
    xhr.send(params);
}

function createNewSlideRequest() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/new-slide.php");

    const params = new URLSearchParams();
    params.set("presentation", globalParams.get("presentation"));
    params.set("after-slide", slideId);
    
    xhr.onload = function () {
        slideId ++;
        var data = JSON.parse(this.response);  
        getSlideSlimCode(); 
        updateSlideCount();
        refreshButtons();
    }
    xhr.send(params);
}
function getFullPresentationRequest() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/full-presentation.php");

    const params = new URLSearchParams();
    params.set("presentation", globalParams.get("presentation"));
    
    xhr.onload = function () {
        var data = JSON.parse(this.response);
        function download(filename, text) {
            var element = document.createElement('a');
            element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
            element.setAttribute('download', filename);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();

            document.body.removeChild(element);
        }

        download(globalParams.get("presentation"), data.presentation);
    }
    xhr.send(params);
}

function downloadPresentation() {
    getFullPresentationRequest();
}

function onSlideChange() {
    getSlideSlimCode();
    isSlimCodeEdited = false;
    refreshButtons();
}
function nextSlide() {
    slideId ++;
    onSlideChange();
}
function previousSlide() {
    slideId --;
    onSlideChange();
}

function preview() {
    getSlidePreview();
}

function createNewSlide() {
    createNewSlideRequest();
    onSlideChange();
}

function refreshButtons() {
    if (slideId == 1) {
        document.getElementById("previous-slide").setAttribute("disabled", "true");
    } else {
        document.getElementById("previous-slide").removeAttribute("disabled");
    }
    if (slideId >= presentationSlideCount) {
        document.getElementById("next-slide").setAttribute("disabled", "true");
    } else {
        document.getElementById("next-slide").removeAttribute("disabled");
    }
    if (!isSlimCodeEdited) {
        document.getElementById("save").setAttribute("disabled", "true");
    } else {
        document.getElementById("save").removeAttribute("disabled");
    }
}

function init() {
    document.getElementById("next-slide").onclick = nextSlide;
    document.getElementById("previous-slide").onclick = previousSlide;
    document.getElementById("preview").onclick = preview;
    document.getElementById("save").onclick = saveSlimCode;
    document.getElementById("new-slide").onclick = createNewSlide;
    document.getElementById("download-presentation").onclick = downloadPresentation;
    updateSlideCount();
    onSlideChange();
}

function onCodeEdit() {
    isSlimCodeEdited = true;
    refreshButtons();
}

window.onload = init;
