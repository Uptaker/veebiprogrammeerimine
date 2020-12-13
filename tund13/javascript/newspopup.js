let modal;
let modalimg;
let captiontext;
let content;
let creationdate;
let expdate;
let author;
// let photoid;
let photodir = "../photoupload_news/";

window.onload = function(){
	modal = document.getElementById("modalarea");
	modalimg = document.getElementById("modalimg");
	captiontext = document.getElementById("modalcaption");
	author = document.getElementById("modalauthor");
	creationdate = document.getElementById("modalnewsadded");
	expdate = document.getElementById("modalnewsexpired");
	content = document.getElementById("modalcontent");
	let allThumbs = document.getElementById("newsarea").getElementsByTagName("img");
	for (let i = 0; i < allThumbs.length; i ++){
		allThumbs[i].addEventListener("click", openModal);
	}
	document.getElementById("modalclose").addEventListener("click", closeModal);
	modalimg.addEventListener("click", closeModal);
}

function openModal(e){
	modalimg.src = photodir + e.target.dataset.fn;
	// photoid = e.target.dataset.id;
	modalimg.alt = e.target.title;
	captiontext.innerHTML = e.target.dataset.title;
	author.innerHTML = e.target.dataset.author;
	content.innerHTML = e.target.dataset.content;
	creationdate.innerHTML = "Loomise kuupäev: " + e.target.dataset.added;
	expdate.innerHTML = "Aegumiskuupäev: " + e.target.dataset.expired;
	modal.style.display = "block";
}

function closeModal(){
	modal.style.display = "none";
	modalimg.src = "../img/empty.png";
}