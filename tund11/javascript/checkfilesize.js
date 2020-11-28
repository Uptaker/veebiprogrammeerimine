let filesizelimit = 20971520;

window.onload = function() {
    // window.alert("See töötab!");
    // console.log(filesizelimit);
    document.getElementById("photosubmit") .disabled = true;
    document.getElementById("photoinput") .addEventListener("change", checkSize);
}

function checkSize() {
    if(document.getElementById("photoinput") .files[0].size <= filesizelimit) {
        document.getElementById("photosubmit") .disabled = false;
    } else {
        document.getElementById("photosubmit") .disabled = true;
        document.getElementById("notice") .innerHTML = "Valitud fail on liiga suur! Limiit on 20Mb";
    }
}