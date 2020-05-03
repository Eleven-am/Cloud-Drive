//The auth logic and modal
function verify(){
    const xhr = new XMLHttpRequest();

    xhr.open('get', 'auth.php?path=root');
    xhr.send();

    xhr.onload = function(){
        try {
            authcheck = JSON.parse(this.responseText);

            if (authcheck === false)
                document.getElementById("auth").innerHTML = "Log In";

            else
                document.getElementById("auth").innerHTML = "Log Out";

        } catch(e) {
            console.log(e);
            authcheck = false;
        }
    }
}

const authform = {
    block: document.getElementById("authdboxblock"),
    div: document.getElementById("authdbox"),
    input: document.getElementById("authbar"),
    button: document.getElementById("authbtn")
};

authform.block.addEventListener("click", function (e) {
    if (!authform.div.contains(e.target)) {
        authform.block.style.display = "none";
    }
});

authform.input.addEventListener("keyup", function (e) {
    if (e.keyCode === 13) {
        authform.button.click();
    }
});

authform.button.addEventListener("click", function (e) {
    authform.block.style.display = "none";
    login();
});


function validate(){
    if (!authcheck){
        authform.block.style.display = "block";
    }
    else {
        logout();
    }
}

function login(){
    const xhr = new XMLHttpRequest();
    const data = new FormData();
    data.append("key", authform.input.value);
    authform.input.value = '';
    authform.input.blur();

    xhr.onload = function(){
        let response = null;
        try {
            response = JSON.parse(this.responseText);
            reportInfo(response);
            dom.click();
        } catch(e) {
            console.log(this.responseText);
        }
    }

    xhr.open("POST", "auth.php");
    xhr.send(data);
}

function logout(){
    const xhr = new XMLHttpRequest();
    xhr.onload = function(){
        let response = null;
        try {
            response = JSON.parse(this.responseText);
            reportInfo(response);
            dom.click();
        } catch(e) {
            console.log(this.responseText);
        }
    }

    xhr.open("GET", "auth.php?key=false");
    xhr.send();
}

// The video modal
const videodiv = {
    block: document.getElementById("videodivblock"),
    element: document.getElementById( "videodiv"),
    leftArrow: document.getElementById("back-arrow"),
    rightArrow: document.getElementById("fow-arrow")
};
var mediaArray = [];

$(document).on("click", ".cons", function (e) {
    if (check){ toggle(e); }
    let bin = e.currentTarget.attributes["class"].nodeValue;

    const videoObj ={
        link: e.currentTarget.attributes["data-id"].nodeValue,
        name: e.currentTarget.attributes["data-name"].nodeValue,
        checker: $(e.target).hasClass("media") || bin === "recent-div cons media"? "video": "image"
    };

    var recentdiv = bin.search("recent-div");

    if (recentdiv !== -1) loadMedia(videoObj);

    else{
        mediaArray = [];
        var list  =  document.getElementsByClassName("cons");
        for(const item of list){
            let tmp1 = item.attributes["class"].nodeValue;
            let tmp2 = tmp1.search("recent-div");
            if (tmp2 === -1) mediaArray.push(item);
        }

        for(let x = 0; x < mediaArray.length; x++){
            if (mediaArray[x] === e.currentTarget){
                loadArrow(videoObj, x);
            }
        }
    }

});

$(document).on("click", ".arrow", function (e) {
    let int  = e.currentTarget.attributes["int"].nodeValue;
    let bin = e.currentTarget.attributes["class"].nodeValue;

    const videoObj ={
        link: e.currentTarget.attributes["data-id"].nodeValue,
        name: e.currentTarget.attributes["data-name"].nodeValue,
        checker: $(e.target).hasClass("media") || bin === "recent-div cons media"? "video": "image"
    };

    loadArrow(videoObj, int);
});

function loadArrow(obj, int) {
    int = parseInt(int);
    var length =  mediaArray.length;
    var checker = int === 0 || int === length - 1;


    if (!checker){
       setbArrows(int);
    }

    else{
        if (int === 0 && length > 1){
            setrArrow(int);
            unsetlArrow();
        }

        else if(int === length - 1 && length > 1){
            setlArrow(int);
            unsetrArrow();
        }

        else{
            unsetbArrows();
        }
    }

    loadMedia(obj);
}

function setlArrow(int){
    var down = int - 1;
    let ltemp  =  mediaArray[int-1].attributes["class"].nodeValue.search("media") === -1? "image": "media";
    videodiv.leftArrow.setAttribute("class", "video-img arrow "+ltemp);
    videodiv.leftArrow.setAttribute("int", down);
    videodiv.leftArrow.setAttribute("data-id", mediaArray[int-1].attributes["data-id"].nodeValue);
    videodiv.leftArrow.setAttribute("data-name", mediaArray[int-1].attributes["data-name"].nodeValue);
}

function setrArrow(int) {
    var up = int + 1;
    let rtemp  =  mediaArray[int+1].attributes["class"].nodeValue.search("media") === -1? "image": "media";
    videodiv.rightArrow.setAttribute("class", "video-img arrow "+rtemp);
    videodiv.rightArrow.setAttribute("int", up);
    videodiv.rightArrow.setAttribute("data-id", mediaArray[int+1].attributes["data-id"].nodeValue);
    videodiv.rightArrow.setAttribute("data-name", mediaArray[int+1].attributes["data-name"].nodeValue);
}

function setbArrows(int){
    setlArrow(int);
    setrArrow(int);
}

function unsetlArrow() {
    videodiv.leftArrow.removeAttribute("int");
    videodiv.leftArrow.removeAttribute("data-name");
    videodiv.leftArrow.removeAttribute("data-id");
    videodiv.leftArrow.setAttribute("class", "video-img");
}

function unsetrArrow(){
    videodiv.rightArrow.removeAttribute("int");
    videodiv.rightArrow.removeAttribute("data-name");
    videodiv.rightArrow.removeAttribute("data-id");
    videodiv.rightArrow.setAttribute("class", "video-img");
}

function unsetbArrows() {
    unsetrArrow();
    unsetlArrow();
}

function loadMedia(obj){
    videodiv.element.textContent = '';

    if (obj.checker === "video"){
        var video = document.createElement("video");
            video.setAttribute("controls", "controls");
        var source = document.createElement("source");
            source.setAttribute("src", "media.php?push="+obj.link);

        video.append(source);
        video.preload;
        videodiv.element.append(video);
        document.title = "\u25B6   " + obj.name;

        videodiv.block.style.display = "block";
        videodiv.block.addEventListener("click", function(){
            if (!videodiv.element.contains(event.target) && !videodiv.leftArrow.contains(event.target) && !videodiv.rightArrow.contains(event.target)){
                document.title = dom.innerHTML;
                videodiv.block.style.display = "none";
                unsetbArrows();
                video.pause();
            }
        });
    } else if (obj.checker === "image"){
        var image = document.createElement("img");
            image.setAttribute("id", "videodiv-image");
            image.setAttribute("src", "download.php?file="+obj.link);

        videodiv.element.append(image);
        document.title = obj.name;
        videodiv.block.style.display = "block";

        videodiv.block.addEventListener("click", function(){
            if (!videodiv.element.contains(event.target) && !videodiv.leftArrow.contains(event.target) && !videodiv.rightArrow.contains(event.target)){
                document.title = dom.innerHTML;
                videodiv.block.style.display = "none";
                unsetbArrows();
            }
        });
    }

}

// The create logic and modal
const create = {
    block: document.getElementById("createboxblock"),
    div: document.getElementById("createbox"),
    input: document.getElementById("create-bar"),
    button: document.getElementById("create-button")
};

create.block.addEventListener("click", function (e) {
    if (!create.div.contains(e.target)) {
        create.block.style.display = "none";
    }
});

create.input.addEventListener("keyup", function (e) {
    if (e.keyCode === 13) {
        create.button.click();
    }
});

create.button.addEventListener("click", function (e) {
    create.block.style.display = "none";
    createFolder();
});

$(document).on("click", ".newfldr", function(event){
    let link = "share.php?path="+dom.attributes["data-id"].nodeValue;
    createform.setAttribute("data-id", link);
    properties.style.display = "none";

    create.block.style.display = "block";
});

function createFolder(){
    const link  = createform.attributes["data-id"].nodeValue;
    const xhr = new XMLHttpRequest();
    const data = new FormData();
    data.append("create", create.input.value);
    create.input.value = '';
    create.input.blur();

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
