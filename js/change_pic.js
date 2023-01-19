const bg = document.querySelector(".img_bg");
const prof = document.querySelector(".img_prof");
bg.style.cursor = "pointer";
prof.style.cursor = "pointer";
bg.addEventListener("mouseenter", function(){
    bg.style.filter = "brightness(0.85)";
    document.querySelector(".bg_button").style.cssText = "pointer-events: auto; opacity: 1;";
    document.querySelector("#delete_bg").style.cssText = "pointer-events: auto; opacity: 1;";
})
prof.addEventListener("mouseenter", function(){
    prof.style.filter = "brightness(0.5)";
     document.querySelector(".prof_button").style.cssText = "pointer-events: auto; opacity: 1;";
})
document.querySelector(".prof_button").addEventListener("mouseenter", function(){
    prof.style.filter = "brightness(0.5)";
     document.querySelector(".prof_button").style.cssText = "pointer-events: auto; opacity: 1;";
})
bg.addEventListener("mouseleave", function(){
    bg.style.filter = "brightness(1)";
    document.querySelector(".bg_button").style.cssText = "pointer-events: none; opacity: 0;";
    document.querySelector("#delete_bg").style.cssText = "pointer-events: none; opacity: 0;";
})
prof.addEventListener("mouseleave", function(){
    prof.style.filter = "brightness(1)";
    document.querySelector(".prof_button").style.cssText = "pointer-events: none; opacity: 0;";
})
document.querySelector(".bg_button").addEventListener("mouseenter", function(){
   document.querySelector(".bg_button").style.cssText = "pointer-events: auto; opacity: 1;";
    document.querySelector("#delete_bg").style.cssText = "pointer-events: auto; opacity: 1;";
})
document.querySelector("#delete_bg").addEventListener("mouseenter", function(){
   document.querySelector(".bg_button").style.cssText = "pointer-events: auto; opacity: 1;";
    document.querySelector("#delete_bg").style.cssText = "pointer-events: auto; opacity: 1;";
})
document.querySelector("#bg_file").addEventListener("input", function(){
    let value = document.querySelector("#bg_file").value.split(/(\\|\/)/g).pop();
    let answer = confirm(`Na pewno "${value}"?`);
    if(answer){
        document.querySelector("#bg_form").submit();
    }else{
        document.querySelector("#bg_file").value = "";
    }
})