function randomFloat(min, max) {
    return (Math.random() * (max - min)) + min; // funkcja tworząca losowe floaty
}
let panorama;
let map;
let kordynaty;
let licznik = 0;
let marker;
let sv;
let runda = 0;
window.locations = [];
window.guesses = [];
window.current_country = "";
function initMap() { // funkcja odbywająca się wraz z startem strony
    let x = randomFloat(-125, 177); // losowane kordynaty
    let y = randomFloat(-66, 69); //
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
        center: {lat: 20,lng: 0}  // kordynaty środka mapy
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
                    if(!response.address.CountryCode){
                        let x = randomFloat(-125, 177); // losowane nowe kordynaty
                        let y = randomFloat(-66, 69);
                        kordynaty = {
                            lat: y,
                            lng: x
                        };
                        getPano();
                    }else{
                    window.current_country = response.address.CountryCode;
                        processSVData(res).then(function(response){
                            document.getElementById("start").style.display = "block";
                            document.getElementById("mapa").style.display = "block";
                            }, function(error){
                                console.log(error)
                            });
                    }
                }
            }
        let coords = res.data.location.latLng.toString().slice(1).slice(0,-1);
        let coords2 = coords.split(",");
        let coords3 = coords2.reverse();
        let coords4 = coords3.join(",");
        let coords_good = coords4.split(","); 
            xmlHttp.open("GET", `https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=pjson&featureTypes=&location=${coords_good}`, true);
            xmlHttp.send(null);
    },
    function(err){
        console.log("Nie znaleziono żadnego StreetView, losuje nowe kordynaty....")
             let x = randomFloat(-125, 177); // losowane nowe kordynaty
            let y = randomFloat(-66, 69);
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
            odleglosc = new google.maps.Polyline({ // Polyline to obiekt linia pomiędzy dwoma punktami na mapie
                path: [marker.getPosition(), marker2.getPosition()],
                map: map
            });
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    let response = JSON.parse(xmlHttp.response);
                    if(response.address.CountryCode && response.address.CountryCode == window.current_country){
                       document.querySelector("#kordy").style.display = "block";
                        runda++;
                        document.querySelector("#runda").innerHTML = "Streak : "+runda;
                        if(document.querySelector("#login")){
                        let cs_max = parseInt(document.querySelector("#cs_max").innerText);
                        let login = document.querySelector("#login").innerText;
                        if(runda > cs_max){
                            var xmlHttp2 = new XMLHttpRequest();
                            xmlHttp2.onreadystatechange = function() { 
                                if (xmlHttp2.readyState == 4 && xmlHttp2.status == 200){
                                    
                                }
                            }
                            xmlHttp2.open("GET", `set_cs_max.php?login=${login}&cs_max=${runda}&type=max`, true);
                            xmlHttp2.send(null);
                        }
                        window.cs_sum++;
                            var xmlHttp3 = new XMLHttpRequest();
                            xmlHttp3.onreadystatechange = function() { 
                            }
                            xmlHttp3.open("GET", `set_cs_max.php?login=${login}&cs_sum=${window.cs_sum}&type=sum`, true);
                            xmlHttp3.send(null);
                        }
                    }else{
                        document.querySelector("#runda").innerHTML = "Streak : "+runda+" - Koniec";
                        document.getElementById("pano").innerHTML = "";
                        document.getElementById("start").style.display = "none";
                        document.querySelector("#runda").classList.add("runda");
                        document.querySelector("#runda").removeAttribute("id");
                        document.querySelector(".runda").innerHTML = document.querySelector(".runda").innerHTML+`<br /><a href="country_streak.php"><button>Nowa gra</button></a>`;
                    }
                }
            }
            let coords = e.latLng.toString().slice(1).slice(0,-1);
            let coords2 = coords.split(",");
            let coords3 = coords2.reverse();
            let coords4 = coords3.join(",");
            let coords_good = coords4.split(","); 
            xmlHttp.open("GET", `https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=pjson&featureTypes=&location=${coords_good}`, true);
            xmlHttp.send(null);
        }
    });
    
}


function start() {
    window.getPano();
}

async function processSVData({
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
    })
}
window.initMap = initMap;

function kordy() { // losuje nowe kordy, czyści wszystkie divy
    document.getElementById("pano").innerHTML = "";
    document.getElementById("mapa").innerHTML = "";
    document.getElementById("kordy").style.display = "none";
    licznik = 0;
    panorama = null;
    map = null;
    document.getElementById("runda").innerHTML = "Streak : "+runda;
    initMap(); 
}