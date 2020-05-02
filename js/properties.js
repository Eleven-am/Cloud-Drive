var check = false;
var authcheck = false;
var move = false;
var filetomove = 'tmp';
var sharelink = "";
var select = false;
var files = false;
var infobool = false;
const sideupload = document.getElementById("side-upload");


//var fileOpen = null;

$(document).on("contextmenu", ".disk", function(event){
    toggle(event);
    if (infobool){
        info.block.style.display = "none";
        infobool = false;
    }
});

$(document).on("contextmenu", ".recent-div", function(event){
    toggle(event);
});

function toggle(e) {
    var helper = dropzone.offsetHeight - 110;

    e.preventDefault();
    var x = e.pageX + properties.offsetWidth > dropzone.offsetWidth ? dropzone.offsetWidth - properties.offsetWidth: e.pageX;
    var y = e.pageY < helper? e.pageY: helper;
    if (!check) {
        var baseurl = e.currentTarget.attributes["data-id"].nodeValue;
        var name = e.currentTarget.children[1].innerHTML;
        recupeur(baseurl, name);
        properties.style.display = "block";
        properties.style.left = x + "px";
        properties.style.top = y + "px";
        check = true;
    } else {
        properties.style.display = "none";
        check = false;
    }
}

function recupeur(baseurl, name){
    var items = document.getElementsByClassName("element");
    sharelink = baseurl;
    items[0].setAttribute("data-clipboard-text", "https://.(your server)./share.php?url="+baseurl);
    items[1].setAttribute("info-url", baseurl);
    items[2].setAttribute("rename-url", "share.php?path="+baseurl);
    items[3].setAttribute("data-id", baseurl);
    items[4].setAttribute("info-file", baseurl);
    items[5].setAttribute("delete-url", baseurl);
    items[5].setAttribute("delete-file", name);
}

/* The share button logic */

 var clipboard = new ClipboardJS('#sharebtn');
    clipboard.on('success', function (e) {
        const xhr = new XMLHttpRequest();
        xhr.open("get", "share.php?encode="+sharelink);
        xhr.send();

        xhr.onload = function(){
            let response = JSON.parse(this.responseText);
            if(response){
                properties.style.display = "none";
                var obj = {type: "prompt", message: "Link copied succesfully"};
                reportInfo(obj);
                check = false
            }

            else{
                console.log(e);
                properties.style.display = "none";
                var obj = {type: "error", message: "Somethjng went wrong; The link failed to copy"};
                reportInfo(obj);
                check = false
            }
        }
    });

    clipboard.on('error', function (e) {
        console.log(e);
        properties.style.display = "none";
        var obj = {type: "error", message: "Somethjng went wrong; The link failed to copy"};
        reportInfo(obj);
        check = false
    });


// The upload button div
const upload = {
    block: document.getElementById("uploadblock"),
    element: document.getElementById("upload")
};

sideupload.addEventListener("click", function(){
    selectore.style.display = "block";
    select = true;

});

document.getElementById("upload-selector").addEventListener("click", function (e) {
    let obj = ";"
    let temp = "";
    let htmlObject = "";
    if (document.getElementById("use-file").contains(e.target)) {
        files = true;
        obj = '<div class="box" id="uploadcapture"><input type="file" id="file-4" class="inputfile" data-multiple-caption="{count} files selected" multiple><label for="file-4"><span>Choose files to upload</span></label></div>';
        temp = document.createElement('div');
        temp.innerHTML = obj;
        htmlObject = temp.firstChild;
    } else if (document.getElementById("use-folder").contains(e.target)) {
        obj = '<div class="box" id="uploadcapture"><input type="file" id="file-4" class="inputfile" data-multiple-caption="{count} files selected" webkitdirectory multiple><label for="file-4"><span>Choose folders to upload</span></label></div>';
        temp = document.createElement('div');
        temp.innerHTML = obj;
        htmlObject = temp.firstChild;
    }

    var element = document.getElementById("uploadcapture");
    var parent = element.parentNode;
    element.parentNode.removeChild(element);
    parent.append(htmlObject);

    var inputs = document.querySelectorAll( '.inputfile' );
    Array.prototype.forEach.call( inputs, function( input )
    {
        var label	 = input.nextElementSibling,
            labelVal = label.innerHTML;

        input.addEventListener( 'change', function( e )
        {
            var fileName = '';
            if( this.files && this.files.length > 1 )
                fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
            else
                fileName = e.target.value.split( '\\' ).pop();

            if( fileName )
                label.querySelector( 'span' ).innerHTML = fileName;
            else
                label.innerHTML = labelVal;
        });

        // Firefox bug fix
        input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
        input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
    });

    popUp(upload, empty);
});


// The delete button div
const deletemod = {
    block: document.getElementById("deletemodblock"),
    element: document.getElementById("deletemod")
};

document.getElementById("deleteprop").addEventListener("click", function (event) {
    var url = this.attributes["delete-url"].nodeValue;
    var fname = this.attributes["delete-file"].nodeValue;
    document.getElementById("removelabel").innerHTML = fname;
    document.getElementById("rmyes").setAttribute("data-id", "delete.php?file="+url);
    properties.style.display = "none";
    check = false;

    popUp(deletemod, empty);
});

deletemod.block.addEventListener("click", function(){
    if (deletemod.element.contains(event.target)){
        if (document.getElementById("rmno").contains(event.target)){
            deletemod.block.style.display = "none";
        }

        else if (document.getElementById("rmyes").contains(event.target)){
            deletemod.block.style.display = "none";
            deletexhr(event);
        }
    }
});


function deletexhr(e){
    var link = e.target.attributes["data-id"].nodeValue;
    const xhr = new XMLHttpRequest();
    xhr.open('get', link);
    xhr.send();

    xhr.onload =function(){
        let response = null;
        try {
            response = JSON.parse(this.responseText);
            reportInfo(response);
            dom.click();
        } catch(e) {
            console.log(this.responseText);
        }
    }
}

// The rename modal
const rename = {
    block: document.getElementById("rendboxblock"),
    div: document.getElementById("rendbox"),
    input: document.getElementById("rename-bar"),
    button: document.getElementById("rename-button")
};

document.getElementById("renameprop").addEventListener("click", function (event) {
    var ren_url = this.attributes["rename-url"].nodeValue;
    document.getElementById("rename-form").setAttribute("data-id", ren_url);
    properties.style.display = "none";
    check = false;

    rename.block.style.display = "block";
});

rename.block.addEventListener("click", function (e) {
    if (!rename.div.contains(e.target)) {
        rename.block.style.display = "none";
    }
});

rename.input.addEventListener("keyup", function (e) {
    if (e.keyCode === 13) {
        rename.button.click();
    }
});

rename.button.addEventListener("click", function (e) {
    rename.block.style.display = "none";
    renamefunc();
});

function renamefunc (){
    var link = document.getElementById("rename-form").attributes["data-id"].nodeValue;
    const xhr = new XMLHttpRequest();
    const data = new FormData();
    data.append("rename", rename.input.value);
    rename.input.value = '';
    rename.input.blur();

    xhr.onload = function(){
        let response = null;
        try {
            response = JSON.parse(this.responseText);
            dom.click();
            reportInfo(response);
        } catch(e) {
            console.log(this.responseText);
        }
    }

    xhr.open("POST", link);
    xhr.send(data);

}

// The download button
$(document).on("click", ".download", function(e){
    var baseurl = "download.php?file="+e.currentTarget.attributes["data-id"].nodeValue;
    properties.style.display = "none";
    check = false;
    window.location.assign(baseurl);
});

function download(){
    window.location.assign("download.php?link=root");
}

// The move js function and logic
document.getElementById("moveprop").addEventListener("click", function (e) {
    filetomove = this.attributes["info-url"].nodeValue;
    movetoggle(e);
});

function movetoggle() {
    if (!move){
        movecontainer.style.right = '-10%';
        move = true;
        properties.style.display = "none";
        gather("root");
    }
    else{
        movecontainer.style.right = '-50%';
        move = false;
    }
}

function gather(url){
    const xhr = new XMLHttpRequest();

    xhr.open('get', 'move.php?path='+url);
    xhr.send();

    xhr.onload = function(){
        try{
            const folders =  JSON.parse(this.responseText);
            popmini(folders);
        }catch {
            console.log(this.responseText);
        }
    }
}

function popmini(folders){
    while (movelist.firstChild) {
        movelist.removeChild(movelist.firstChild);
    }

    for (let x = 0; x < folders.length; x++){

        var item = document.createElement('li');
            item.setAttribute('class',folders[x].css_class);
            item.setAttribute('data-id', folders[x].path);
            item.setAttribute('onclick', folders[x].jsclick);

        var image = document.createElement('img');
            image.setAttribute('src', folders[x].image);
            image.setAttribute('width', '30px');

        var label = document.createElement('label');
            var tmp1 = folders[x].value.replace("-", " ");
            var tmp2 = tmp1.replace(" ", ".");
            label.innerHTML = (tmp2.length > 14)? tmp2.substring(0, 11) + "...": tmp2;

        item.append(image);
        item.append(label);
        movelist.append(item);
    }
}

function opendir(event){
    gather(event.target.attributes["data-id"].value);
}

function back(){
    gather("back");
}

document.getElementById('movebutton').addEventListener('click', function(){
    const xhr = new XMLHttpRequest();

    xhr.open('get', 'move.php?move='+filetomove);
    xhr.send();
    xhr.onload = function(){
        try{
            reportInfo(JSON.parse(this.responseText));
            movetoggle();
            dom.click();
        } catch(e){ console.warn("Could not move file/folder! : "+ this.responseText); }
    }
});

// -------------- the information button and div logic ---------------------------------

const info = {
    block: document.getElementById("information-div"),
    name: document.getElementById("inform-name"),
    time: document.getElementById("inform-time"),
    size: document.getElementById("inform-size"),
    local: document.getElementById("inform-locate")
};

document.getElementById("infoprop").addEventListener("click", function () {
    var file  = event.currentTarget.attributes["info-file"].nodeValue;
    var helper = dropzone.offsetHeight;
    var x = event.pageX + info.block.offsetWidth > dropzone.offsetWidth ? dropzone.offsetWidth - info.block.offsetWidth - 150: event.pageX;
    var y = event.pageY < helper? event.pageY: helper;

    const xhr = new XMLHttpRequest();
    xhr.open("get", "beau.php?code="+file);
    xhr.send();

    xhr.onload = function () {
        try{
            insertInfo(JSON.parse(this.responseText), x, y);
        }catch (e) {
            console.log(e);
        }
    }

});

function insertInfo(obj, x ,y){
    info.name.nextElementSibling.innerHTML = obj.name;
    info.size.nextElementSibling.innerHTML = obj.size;
    info.time.nextElementSibling.innerHTML = obj.time;
    info.local.nextElementSibling.innerHTML = obj.location;

    properties.style.display = "none";
    check = false;
    info.block.style.display = "block";
    info.block.style.left = x + "px";
    info.block.style.top = y + "px";
    infobool = true;
}
