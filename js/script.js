// =====================================
// TEMPS LIBRE - MAIN JS
// =====================================



// Header au scroll


window.addEventListener(
"scroll",
()=>{


let header = document.querySelector(".header");


if(window.scrollY > 50){

header.classList.add("sticky");

}else{

header.classList.remove("sticky");

}


});




// Menu mobile


const menuBtn =
document.querySelector(".menu-toggle");


const menu =
document.querySelector(".menu-principal");



if(menuBtn){


menuBtn.addEventListener(
"click",
()=>{

menu.classList.toggle("active");


});


}