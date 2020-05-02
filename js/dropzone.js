var gloArray = [];
var nUCheck = false;
var address = "";

function scanFiles(item, path){
    path = path || "";
    if (item.isDirectory) {
        var dirReader = item.createReader();
        dirReader.readEntries(function(entries) {
            entries.forEach(function(entry) {
                scanFiles(entry, path + item.name + "/");
            });
        });
    }
    else if (item.isFile){
        item.file(function(file) {
            var obj = {piece: file, location: path};
            gloArray.push(obj);
        });
    }
}

function newUpload(){
    nUCheck = true;
    const file = gloArray[0].piece;
    const locale = gloArray[0].location;
    const formData = new FormData();
    formData.append('submit[]', file);
    const xhr = new XMLHttpRequest();

    link = (locale === "")? address: address+locale;
    var tmp2 = (file.name).split('/').reverse()[0];

    var upldtitle = (tmp2.length > 31)? tmp2.substring(0, 28) + "...": tmp2;
    var progress = document.getElementById('uploadFiller');

    document.getElementById("uploadSub").innerHTML = upldtitle;
    document.getElementById('uploadProgressContainer').style.bottom = minimise? "-5%": "0%";

    xhr.upload.addEventListener("progress", function(evt){
        const percent = evt.lengthComputable ? (evt.loaded/evt.total)*100: 0;
        progress.style.width = percent + "%";
    });

    xhr.open('POST', 'upload.php?link='+link);
    xhr.send(formData);

    xhr.onload = function(){
        progress.style.width = "0%";

        for(let i = gloArray.length-1; i >= 0; i--){
            if (gloArray[i].piece.name === file.name) gloArray.splice(i, 1);
        }

        if (gloArray.length >= 1){ newUpload(); }

        else {
            document.getElementById('uploadProgressContainer').style.bottom = "-50%";
            var obj = {type:"prompt", message:"Upload Successful"};
            reportInfo(obj);
            dom.click();
            nUCheck =  false;
        }
    }

}

function dataCapture(items){
    for (var i=0; i<items.length; i++) {
        var item = items[i].webkitGetAsEntry();
        if (item) {
            scanFiles(item);
            setTimeout(function(){
                if (!nUCheck) {
                    decrypt();
                } else {
                    var obj = {
                        type: "alert",
                        message: (gloArray.length - 1  === 1)? gloArray.length - 1+" file queued for upload": gloArray.length - 1+" files queued for upload"
                    };
                    reportInfo(obj);
                }
            },200);
        }
    }
}

function dataCapturedo(items, int){
    for (const file of items)
    {
        const path = (int === 1)? "": dir_name(file.webkitRelativePath)[0];
        var obj = {piece: file, location: path};
        gloArray.push(obj);
    }
    if (!nUCheck) {
        decrypt();
    } else {
        var obj = {
            type: "alert",
            message: (gloArray.length - 1  === 1)? gloArray.length - 1+" file queued for upload": gloArray.length - 1+" files queued for upload"
        };
        reportInfo(obj);
    }
}

// The functions and triggers

dropzone.addEventListener("dragover", function(e){
    e.preventDefault();
    dropzone.style.opacity = "0.1";
    dndModal.style.display = "block";
});

dropzone.addEventListener("dragleave", function(e){
    e.preventDefault();
    dropzone.style.opacity = "1";
    dndModal.style.display = "none";
});

dropzone.addEventListener("drop", function(e){
    e.preventDefault();
    dataCapture(e.dataTransfer.items);
    dropzone.style.opacity = "1";
    dndModal.style.display = "none";
});

//The upload button


document.getElementById('uplbtn').addEventListener('click', function(e){
    const inpfile = document.getElementById('file-4');
    upload.block.style.display = "none";

    if (files){
        dataCapturedo(inpfile.files, 1);
    }else {
        dataCapturedo(inpfile.files, 2);
    }
});

function decrypt() {
    const link = dom.getAttribute("data-id");
    const xhr = new XMLHttpRequest();
    xhr.open('get', 'share.php?code='+link);
    xhr.send();
    xhr.onload = function(){
        try {
            address = JSON.parse(this.responseText);
            newUpload();
        }catch (e) {
            console.log(e);
        }
    }
}

function decrypter(link) {
    const xhr = new XMLHttpRequest();
    xhr.open('get', 'share.php?code='+link);
    xhr.send();
    xhr.onload = function(){
        try {
           let address = JSON.parse(this.responseText);
           console.log(address);
        }catch (e) {
            console.log(e);
        }
    }
}