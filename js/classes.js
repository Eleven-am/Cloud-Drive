const properties = document.getElementById('properties');
const dropzone = document.getElementById('dropzone');
const dndModal = document.querySelector("#dndbox");
const dom = document.getElementById("foldername");
const movecontainer = document.getElementById("movecontainer");
const movelist = document.getElementById("movelist");
const myvideo = document.getElementById('myvideo');
const createform = document.getElementById("createform");
const recent = document.getElementById("recently-opened");
const selectore =  document.getElementById("upload-selector");

function popUp(object, task2) {
    object.block.style.display = "block";

    object.block.addEventListener("click", function (event) {
        if (!object.element.contains(event.target)) {
            object.block.style.display = "none";
            task2(event);
        }
    });
}

function reportInfo(obj){
    let info;
    let src;
    let color;
    let timer;

    const infobox = {
        modal: document.getElementById("info-reporting"),
        image: document.getElementById("info-reporting-image"),
        title:  document.getElementById("info-type-label"),
        message: document.getElementById("info-reporting-message")
    };

    if (obj.type === "alert") {
        info = "Alert!";
        src = "src/alert.svg";
        color = "rgba(143, 169, 201, .9)";
        timer = 5000
    } else if (obj.type === "error") {
        info = "Error!";
        src = "src/error.svg";
        color = "rgba(209, 36, 36, .8)";
        timer = 10000;
    } else {
        info = "Prompt!";
        src = "src/tick.svg";
        color = "rgba(50, 168, 82, .8)";
        timer = 2000;
    }

    infobox.modal.style.backgroundColor = color;
    infobox.image.setAttribute("src", src);
    infobox.title.innerHTML = info;
    infobox.message.innerHTML = obj.message;
    infobox.modal.style.right = "0%";

    setTimeout(function () {
        infobox.modal.style.right = "-100%";
    }, timer);
}

function dir_name(path) {
    return path.match(/.*\//);
}

function empty(e){
    console.log(e);
}

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function confirmOBJ(object, link){
    if (Array.isArray(object)){
        populate(object, link);

    } else {
        var x  = object.hasOwnProperty("type");
        if (x == true){ reportInfo(object); }
        else { populate(object, link); }
    }
}

function exists(node) {
    return (node === document.body) ? false : document.body.contains(node);
}
