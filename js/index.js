var progress = 0;
var task = false;


window.onload = function(){
    indexload("root");
    task = true;
    progresstracker();
}

$(document).on("click", ".link", function(event){
    task = true;
    progresstracker();
    progress = 2;
    indexload(event.currentTarget.attributes["data-id"].nodeValue);
});

function indexload(link){
    verify();
    retrieve()
    ;
    const xhr = new XMLHttpRequest();
    progress = 5;

    xhr.open('get', 'handler.php?resource='+link);
    xhr.send();
    progress = 8;

    xhr.onload = function(){
        if (isJson(this.responseText)){
            const folders =  JSON.parse(this.responseText);
            progress = 15;
            confirmOBJ(folders, link);
            diskfetch();
        }
        else
            console.log(this.responseText);
        progress = 100;
    }
}

function populate(folders, link){
    hasfocus(link);

    if (exists(document.getElementById("filelist"))){
        var element = document.querySelector("#filelist");
        element.parentNode.removeChild(element);
    }

    var base = document.createElement('ul');
    base.setAttribute("id", "filelist");
    dom.setAttribute("data-id", folders[0].url_link);
    dom.innerHTML = folders[0].name;
    document.title = folders[0].name;
    document.getElementById("backimg").setAttribute("data-id", folders[0].image);

    if (folders.length === 1){
        var div = document.createElement("div");
            div.setAttribute("id", "empty-div");

        var image = document.createElement("img");
            image.setAttribute("src", "src/empty.svg");
            image.setAttribute("id","empty-img");

        var label = document.createElement("label");
            label.innerHTML = "This folder is empty; Drag and drop to upload here.";
            label.setAttribute("id", "empty-label");

            div.append(image);
            div.append(label);
            base.append(div);
    }

    for (let x = 1; x < folders.length; x++){
        var item = document.createElement("li");
            item.setAttribute("class", folders[x].class);
            item.setAttribute("data-id", folders[x].url_link);
            item.setAttribute("data-name", folders[x].data_name);

        var image = document.createElement("img");
            image.setAttribute("class", "image");
            image.setAttribute("width", "30px");
            image.setAttribute("src", folders[x].image);

        var label = document.createElement("label");
            label.innerHTML = folders[x].name;

        var span = document.createElement("span");
            span.setAttribute("class", "disk-size");
            span.innerHTML = folders[x].size;

            item.append(image);
            item.append(label);
            item.append(span);
            base.append(item);
        progress = (((x+1)/folders.length) * 80) + 15;
    }

    progress = (progress === 15)? 100: progress + 5;
    dropzone.append(base);
}

function progresstracker()
{
    if (task === true){
        if (progress < 100){
            setTimeout(progresstracker, 100);
            console.log(progress);
        }
        else {
            setTimeout(progresstracker, 100);
            console.log(progress);
            task = false;
        }
    }

}

//----------------------------leave the site---------------------------------------------------

$(document).on("click", ".leave", function(event){
    window.location.assign(event.currentTarget.attributes["data-id"].nodeValue);
});

//---------------------------search function------------------------------------------
const searchobj = {
    input: document.getElementById("searchb"),
    button: document.getElementById("SearchBtn")
};

searchobj.input.addEventListener("keyup", function(e){
    if (e.keyCode === 13) {
        searchobj.button.click();
    }
});

searchobj.button.addEventListener("click",function(e){
    searchfunc();
});

function searchfunc (){
    const data = new FormData();
    data.append("search", searchobj.input.value);
    query(data);
    searchobj.input.value = '';
    searchobj.input.blur();
}

function media(){
    hasfocus("media");
    const data = new FormData();
    data.append("search", "media");
    query(data);
}

function query(data){
    const xhr = new XMLHttpRequest();
    xhr.onload = function(){
        let response = null;
        try {
            response = JSON.parse(this.responseText);
            confirmOBJ(response);
        } catch(e) {
            console.log(this.responseText);
        }
    }

    xhr.open("POST", "search.php");
    xhr.send(data);

}