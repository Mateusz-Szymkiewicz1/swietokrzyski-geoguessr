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
    let x = randomFloat(19.75, 21.8); // losowane kordynaty
    let y = randomFloat(50.3, 51.2); //
    kordynaty = {
        lat: y,
        lng: x
    };
    sv = new google.maps.StreetViewService();
    map = new google.maps.Map(document.getElementById("mapa"), { // stworzenie obiektu mapa, przypisanie do diva
        zoom: 8,
        center: {
            lat: 50.76,
            lng: 20.69,
        }, // kordynaty środka mapy
    });
    panorama = new google.maps.StreetViewPanorama( // stworzenie obiektu streetview (panorama), przypisanie do diva
        document.getElementById("pano")
    );
    sv.getPanorama({
        location: kordynaty,
        radius: 8000
    }).then(processSVData); // funkcja getPanorama szuka zdjęc streetview na lokalizacji 'kordynaty' w zasięgu 8000 metrów
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
        icon: 'go.png',
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
        document.getElementById("pano").innerHTML = '<img src="game_over.png" height="80%" width="50%">';
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

function start() {
    sv.getPanorama({
        location: kordynaty,
        radius: 8000
    }).then(processSVData);
}
