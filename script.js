window.maps = {
    Świat: {
        minx: -125,
        maxx: 177,
        miny: -66,
        maxy: 69,
    },
    Polska: {
        minx: 14.10,
        maxx: 24,
        miny: 49,
        maxy: 54.8,
        countries: ["POL"]
    },
    USA: {
        minx: -167,
        maxx: -66,
        miny: 18.3,
        maxy: 71,
        countries: ["USA"]
    },
    UE:{
        minx: -9.5,
        maxx: 34.4,
        miny: 34.9,
        maxy: 70,
        countries: ["POL", "DEU", "FRA", "BGR", "ESP", "ITA","SWE", "LVA","HRV", "EST","FIN","CZE","GRC","BEL","ROU","LTU","HUN","NLD","SVN", "AUT","SVK","PRT","IRL","CYP","DNK","AND","LUX","MLT"]
    }
}
//var xmlHttp = new XMLHttpRequest();
//            xmlHttp.onreadystatechange = function() { 
//                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
//                    let response = JSON.parse(xmlHttp.response);
//                     console.log(response);
//                }
//            }
//            xmlHttp.open("GET", `https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=pjson&featureTypes=&location=14.449%2C35.86`, true);
//            xmlHttp.send(null);
const urlParams = new URLSearchParams(window.location.search);
window.current_map = urlParams.get("map");
function randomFloat(min, max) {
    return (Math.random() * (max - min)) + min; // funkcja tworząca losowe floaty
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
        zoom: 2,
        center: {
            lat: 20,
            lng: 0,
        }, // kordynaty środka mapy
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
                     console.log(response.address.CountryCode);
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
    google.maps.event.addListener(map, 'click', function (e) { // funkcja aktywuje się po kliknięciu na mapę
        if (licznik == 0) { // licznik - ilość postawionych znaczników, przyjmuje wartości 0, 1
            marker.setVisible(true);
            licznik = licznik + 1;
            const marker2 = new google.maps.Marker({ // stworzenie marker2 - znacznika postawionego przez gracza
                position: e.latLng, // e.latLng to kordynaty 'e' czyli miejsca eventu - kliknięcia
                map,
            });
            odleglosc = new google.maps.Polyline({ // Polyline to obiekt linia pomiędzy dwoma punktami na mapie
                path: [marker.getPosition(), marker2.getPosition()],
                map: map
            });
            distance = haversine_distance(marker, marker2);
            let distance2 = ((distance.toFixed(3)) * 1.6).toFixed(3);
            document.getElementById("runda").innerHTML = document.getElementById("runda").innerHTML +" - " + distance2 + " km";
            if(distance2 < 1){
                punkty = 1000;
            }
            else{
             punkty = punkty+Math.round(1000/distance2);  
            }            
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
            document.cookie = "high_score="+punkty+"; expires=Thu, 18 Dec 2030 12:00:00 UTC";
            document.getElementById("runda").innerHTML = "Koniec gry - "+punkty+" pkt. " + "High score: "+punkty+" pkt.  "+'<a href="gra.php">Nowa gra</a>';
        }
        else{
        document.getElementById("runda").innerHTML = "Koniec gry - "+punkty+" pkt. " + "High score: "+high_score+" pkt. "+'<a href="gra.php">Nowa gra</a>';
        }
        document.getElementById("mapa").innerHTML = "";
        document.getElementById("mapa").style.border = "0";
        document.getElementById("pano").style.border = "2px solid #fff";
        document.getElementById("pano").style.background = "transparent";
        document.getElementById("pano").style.paddingTop = "100px";
        document.getElementById("pano").style.textAlign = "center";
        document.getElementById("pano").innerHTML = '<img src="images/game_over.png" height="80%" width="50%">';
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
