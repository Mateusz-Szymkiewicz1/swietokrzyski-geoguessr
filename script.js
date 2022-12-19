//var xmlHttp = new XMLHttpRequest();
//            xmlHttp.onreadystatechange = function() { 
//                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
//                    let response = JSON.parse(xmlHttp.response);
//                     console.log(response);
//                }
//            }
//            xmlHttp.open("GET", `https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=pjson&featureTypes=&location=12.466%2C43.9`, true);
//            xmlHttp.send(null);
const urlParams = new URLSearchParams(window.location.search);
window.current_map = urlParams.get("map");
let map_sizeY = Math.abs(window.maps[window.current_map].maxy-window.maps[window.current_map].miny);
let map_sizeX = Math.abs(window.maps[window.current_map].maxx-window.maps[window.current_map].minx);
let map_size = (map_sizeX+map_sizeY)/2;
function randomFloat(min, max) {
    return (Math.random() * (max - min)) + min; // funkcja tworząca losowe floaty
}
function LightenDarkenColor(col, amt) {
  var num = parseInt(col, 16);
  var r = (num >> 16) + amt;
  var b = ((num >> 8) & 0x00FF) + amt;
  var g = (num & 0x0000FF) + amt;
  var newColor = g | (b << 8) | (r << 16);
  return newColor.toString(16);
}

function getCookie(name) {
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    }
    else
    {
        begin += 2;
        var end = document.cookie.indexOf(";", begin);
        if (end == -1) {
        end = dc.length;
        }
    }
    return decodeURI(dc.substring(begin + prefix.length, end));
} 
let high_score = getCookie("high_score");
function haversine_distance(mk1, mk2) {
    // funkcja obliczająca odległość między punktami na mapie przy użyciu obwodu ziemi
    // wzięta z dokumentacji google
    var R = 3958.8;
    var rlat1 = mk1.position.lat() * (Math.PI / 180);
    var rlat2 = mk2.position.lat() * (Math.PI / 180);
    var difflat = rlat2 - rlat1;
    var difflon = (mk2.position.lng() - mk1.position.lng()) * (Math.PI / 180);
    var d = 2 * R * Math.asin(Math.sqrt(Math.sin(difflat / 2) * Math.sin(difflat / 2) + Math.cos(rlat1) * Math.cos(rlat2) * Math.sin(difflon / 2) * Math.sin(difflon / 2)));
    return d;
}
let panorama;
let map;
let kordynaty;
let licznik = 0;
let marker;
let odleglosc;
let distance;
let sv;
let runda = 1;
let punkty = 0;
window.locations = [];
window.guesses = [];
window.distances = [];
window.points = [];
function initMap() { // funkcja odbywająca się wraz z startem strony
    let x = randomFloat(window.maps[window.current_map].minx, window.maps[window.current_map].maxx); // losowane kordynaty
    let y = randomFloat(window.maps[window.current_map].miny, window.maps[window.current_map].maxy); //
    if(x <= -24 && x >= -34 ){
        initMap();
    }
    kordynaty = {
        lat: y,
        lng: x
    };
    sv = new google.maps.StreetViewService();
    map = new google.maps.Map(document.getElementById("mapa"), { // stworzenie obiektu mapa, przypisanie do diva
        zoom: window.maps[window.current_map].zoom,
        center: window.maps[window.current_map].center  // kordynaty środka mapy
    });
    panorama = new google.maps.StreetViewPanorama( // stworzenie obiektu streetview (panorama), przypisanie do diva
        document.getElementById("pano")
    );
    window.getPano = function getPano(){
    sv.getPanorama({
        location: kordynaty,
        source: google.maps.StreetViewSource.OUTDOOR,
        radius: 80000
    }).then(function(res){
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() { 
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    let response = JSON.parse(xmlHttp.response);
                    if(window.current_map != "Świat"){
                        if(window.maps[window.current_map].countries.indexOf(response.address.CountryCode) >= 0){
                            processSVData(res);
                        }else{
                            x = randomFloat(window.maps[window.current_map].minx, window.maps[window.current_map].maxx); // losowane kordynaty
                            y = randomFloat(window.maps[window.current_map].miny, window.maps[window.current_map].maxy);
                            kordynaty = {
                                lat: y,
                                lng: x
                            };
                            getPano();
                        }
                    }else{
                        processSVData(res);
                    }
                }
            }
            xmlHttp.open("GET", `https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=pjson&featureTypes=&location=${x}%2C${y}`, true);
            xmlHttp.send(null);
    },
    function(err){
        console.log("Nie znaleziono żadnego StreetView, losuje nowe kordynaty....")
            x = randomFloat(window.maps[window.current_map].minx, window.maps[window.current_map].maxx); // losowane kordynaty
            y = randomFloat(window.maps[window.current_map].miny, window.maps[window.current_map].maxy);
            kordynaty = {
                lat: y,
                lng: x
            };
        getPano();
    }
    );
    }// funkcja getPanorama szuka zdjęc streetview na lokalizacji 'kordynaty' w zasięgu 8000 metrów
    getPano();
    google.maps.event.addListener(map, 'dblclick', function (e) { // funkcja aktywuje się po kliknięciu na mapę
        if (licznik == 0) { // licznik - ilość postawionych znaczników, przyjmuje wartości 0, 1
            marker.setVisible(true);
            licznik = licznik + 1;
            const marker2 = new google.maps.Marker({ // stworzenie marker2 - znacznika postawionego przez gracza
                position: e.latLng, // e.latLng to kordynaty 'e' czyli miejsca eventu - kliknięcia
                map,
            });
            window.guesses.push(e);
            odleglosc = new google.maps.Polyline({ // Polyline to obiekt linia pomiędzy dwoma punktami na mapie
                path: [marker.getPosition(), marker2.getPosition()],
                map: map
            });
            distance = haversine_distance(marker, marker2);
            let distance2 = ((distance.toFixed(3)) * 1.6).toFixed(3);
            window.distances.push(distance2);
            document.getElementById("runda").innerHTML = document.getElementById("runda").innerHTML +" - " + distance2 + " km";
            let punkty_now = 10000/parseInt(distance2)*Math.sqrt(map_size); 
            let punkty_setki = punkty_now/100;
            punkty_now = punkty_now*punkty_setki;
            window.points.push(punkty_now);
            punkty += Math.round(punkty_now);
        }
    });
    
}


function start() {
    window.getPano();
}

function processSVData({
    data
}) {
    let location = data.location;
    panorama.setOptions({
        showRoadLabels: false // usuwa nazwy dróg, bo z nimi gra by była za łatwa ;)
    });
    marker = new google.maps.Marker({ // tworzy znacznik wylosowanego miejsca, domyślnie niewidoczny
        position: location.latLng,
        map,
        animation: google.maps.Animation.DROP,
        icon: 'images/go.png',
    });
    window.locations.push(location);
    marker.setVisible(false);
    panorama.setPano(location.pano); // ustawia panoramę
    panorama.setPov({
        heading: 270,
        pitch: 0,
    });
}
window.initMap = initMap;

function kordy() { // losuje nowe kordy, czyści wszystkie divy
    if(runda >= 5){
        document.getElementById("kordy").style.display = "none";
        document.getElementById("start").style.display = "none";
        if(high_score == null || punkty > high_score){
            document.cookie = "high_score="+punkty+"; expires=Thu, 18 Dec 2030 12:00:00 UTC;";
            high_score = punkty;
        }else{
            high_score = "Brak ;(";
        }
        document.getElementById("runda").innerHTML = "";
        document.getElementById("mapa").innerHTML = "";
        document.getElementById("mapa").style.border = "0";
        document.getElementById("pano").style.cssText = `background: transparent;padding-top:100px;border:2px solid #fff;height: 45vh;width: 100%;text-align:center;`;
        document.getElementById("pano").innerHTML = '';
        document.getElementById("body").style.overflowY = "auto";
        let map_end = new google.maps.Map(document.getElementById("pano"), {
        zoom: window.maps[window.current_map].zoom,
        center: window.maps[window.current_map].center
        });
        for(i = 0; i < runda;i++){
            let location_end;
            let guesses_end;
            if(window.locations[i]){
                location_end = new google.maps.Marker({
                position: window.locations[i].latLng,
                map: map_end,
                icon: 'images/go.png',
            });
            }
            let randomColor = "";
            while(randomColor.length < 6){
                randomColor = Math.floor(Math.random()*0xffffff).toString(16);
            }
            randomColor2 = randomColor;
            randomColor = "#"+randomColor;
            if(window.guesses[i]){
                guess_end = new google.maps.Marker({
                position: window.guesses[i].latLng,
                map: map_end,
                icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|'+randomColor2
            });
           let odl_end = new google.maps.Polyline({
                path: [location_end.getPosition(), guess_end.getPosition()],
                map: map_end,
               strokeColor: randomColor,
    strokeOpacity: 1.0,
    strokeWeight: 4,
            });
            }
        }
        let endbox = document.createElement("div");
        endbox.classList.add("endbox");
        endbox.innerHTML = `<h2>Gra skończona!</h2><br/><h3>Punkty: ${punkty}</h3><table><tr><th>Runda</th><th>Odległość</th><th>Punkty</th></tr></table><br /><a class="nowa_gra" href="gra.php?map=${window.current_map}">Nowa gra</a>`;
        document.body.appendChild(endbox);
        for(let j = 1; j<=runda;j++){
            let odleglosc_end;
            if(window.distances[j-1]){
                odleglosc_end = Math.ceil(window.distances[j-1])+"km";
            }else{
                odleglosc_end = "Brak";
            }
            let points_end;
            if(window.points[j-1]){
                points_end = Math.ceil(window.points[j-1]);
            }else{
                points_end = "Brak";
            }
            document.querySelector("table").innerHTML = document.querySelector("table").innerHTML+`<tr>
                <td>${j}</td>
                <td>${odleglosc_end}</td>
                <td>${points_end}</td>
            </tr>`;
        }
    }
    else{
    document.getElementById("pano").innerHTML = "";
    document.getElementById("mapa").innerHTML = "";
    licznik = 0;
    panorama = null;
    map = null;
    runda = runda+1;
    document.getElementById("runda").innerHTML = "Runda "+runda+"/5";
    initMap(); 
    }
}
