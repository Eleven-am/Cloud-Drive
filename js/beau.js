var minimise = false;

const disk = {
    div: document.getElementById("disk-info"),
    percent: document.getElementById("storage"),
    details: document.getElementById("space-deets"),
    groove: document.getElementById("a-space-filler")
};

function diskfetch(){
    let field  =  sessionStorage.getItem("size")? sessionStorage.getItem("size"): 0;

    const xhr = new XMLHttpRequest();
    xhr.open('get', 'move.php?disk='+field);
    xhr.send();

    xhr.onload = function () {
        try{
            let response = JSON.parse(this.responseText);
            diskinteprete(response);
        }catch (e) {
            console.log(e);
        }
    }
}

function diskinteprete(obj){
    disk.groove.style.width = obj.percent+"%";
    disk.percent.innerHTML = "Storage Used: "+ Math.round(obj.percent)+"%";
    disk.details.innerHTML = obj.used +" of "+ obj.total +" used";
    sessionStorage.setItem('size', obj.size);
}

function hasfocus(link){
    let myArray = {
        "root": document.getElementById("side-root"),
        "home": document.getElementById("side-root"),
        "Private": document.getElementById("side-Private"),
        "Downloads": document.getElementById("side-Downloads"),
        "media": document.getElementById("side-media")
    };

    let tmp = false;
    for (var key in myArray) {
        if (key === link){
            tmp = true;
        }
    }

    if(tmp){
        for (var key in myArray) {
            if (key === link){
                myArray[key].setAttribute("class", "hasfocus");
            }else if (key !== link && key === "media"){
                myArray[key].setAttribute("class", "he");
            }else {
                myArray[key].setAttribute("class", "link he");
            }
        }
    }
}

document.addEventListener("click", function(){
    if (check) {
        if (!properties.contains(event.target)) {
            toggle(event);
        }

    }if (!document.getElementById("moveprop").contains(event.target) && move && !movecontainer.contains(event.target)) {
        movetoggle();

    }
    else if (select){
        if (!sideupload.contains(event.target)) { selectore.style.display = "none"; }
    }

   if (infobool){
        info.block.style.display = "none";
        infobool = false;
    }
});

function retrieve() {
    const xhr = new XMLHttpRequest();
    xhr.open("get", "beau.php?recent=root");
    xhr.send();
    xhr.onload = function(){
        try{
            let result = JSON.parse(this.responseText);
            popRecent(result);
        }catch (e) {
            console.log(e+" "+this.responseText);
        }
    }
}


function popRecent(folders) {
    while (recent.firstChild) {
        recent.removeChild(recent.firstChild);
    }

    if (folders.length === 0){
        var label = document.createElement("label");
            label.setAttribute("id", "recently-opened-label");
            label.innerHTML = "You recently accessed files would appear here";
            recent.append(label);
    }

    for (let x = folders.length-1; x >= 0; x--){
        var label = document.createElement("label");
        var tmp2 = folders[x].name;
        label.innerHTML = (tmp2.length > 31)? tmp2.substring(0, 28) + "...": tmp2;

        var image = document.createElement("img");
        image.setAttribute("class", "recent-thumbnail")
        image.setAttribute("src", "data:image/png;base64,"+folders[x].image);

        var div = document.createElement("div");
        div.setAttribute("class", folders[x].mediaclass);
        div.setAttribute("data-id", folders[x].location);
        div.setAttribute("data-name", folders[x].dataName);


        div.append(image);

        if (folders[x].media){
            var image2 = document.createElement("img");
            image2.setAttribute("src", "src/play-button.svg");
            image2.setAttribute("class", "play-button");
            image2.setAttribute("width", "50px");

            div.append(image2);
        }

        div.append(label);
        recent.append(div);
    }
}

document.getElementById("disk-info").addEventListener("click", function(){
    console.log("click");
    let field = 0;
    if (sessionStorage.getItem("size")) field = sessionStorage.getItem("size");

    const xhr = new XMLHttpRequest();
    xhr.open("get", "beau.php?test="+field);
    xhr.send();

    xhr.onload = function () {
        try{
            console.log(JSON.parse(this.responseText))
            console.log(this.responseText);
        }catch (e) {
            console.log(e);
        }
    }
});

document.getElementById("uploadProgressContainer").addEventListener("click", function () {
    if (minimise){
        document.getElementById("uploadProgressContainer").style.bottom = "0%";
        minimise =  false;
    } else {
        document.getElementById("uploadProgressContainer").style.bottom = "-5%";
        minimise =  true;
    }
});

