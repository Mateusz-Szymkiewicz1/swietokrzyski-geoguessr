let favs = window.fav_maps.split(",");
favs.pop();
document.querySelectorAll("svg").forEach(el => {
    if(favs.includes(el.dataset.map)){
        el.innerHTML = `<path data-map="${el.dataset.map}" fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" fill="white"></path>`;
    }
})