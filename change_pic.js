const bg = document.querySelector(".img_bg");
const prof = document.querySelector(".img_prof");
bg.addEventListener("mouseenter", function(){
    bg.style.filter = "brightness(0.85)";
    document.querySelector(".bg_button").style.pointerEvents = "auto";
    document.querySelector(".bg_button").style.opacity = "1";
})
prof.addEventListener("mouseenter", function(){
    prof.style.filter = "brightness(0.85)";
})
bg.addEventListener("mouseleave", function(){
    bg.style.filter = "brightness(1)";
    document.querySelector(".bg_button").style.pointerEvents = "none";
    document.querySelector(".bg_button").style.opacity = "0";
})
prof.addEventListener("mouseleave", function(){
    prof.style.filter = "brightness(1)";
})
document.querySelector(".bg_button").addEventListener("mouseenter", function(){
    document.querySelector(".bg_button").style.pointerEvents = "auto";
    document.querySelector(".bg_button").style.opacity = "1";
})