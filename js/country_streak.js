function randomFloat(min, max) {
  return (Math.random() * (max - min)) + min; // funkcja tworząca losowe floaty
}
const urlParams = new URLSearchParams(window.location.search);
window.current_map = urlParams.get("map");
window.move = true;
window.pan = true;
window.zoom = true;
window.time_limit = 0;
if (urlParams.get("move") && urlParams.get("move") == "false") {
  window.move = false;
}
if (urlParams.get("pan") && urlParams.get("pan") == "false") {
  window.pan = false;
}
if (urlParams.get("zoom") && urlParams.get("zoom") == "false") {
  window.zoom = false;
}
if (urlParams.get("time_limit") && urlParams.get("time_limit") != "0") {
  window.time_limit = parseInt(urlParams.get("time_limit"));
}
if (!(window.current_map in window.maps)) {
  window.location.href = 'index.php';
}
let panorama, map, kordynaty, marker, sv, czas, time_interval;
let licznik = 0;
let runda = 0;
window.locations = [];
window.guesses = [];
window.current_country = "";

function initMap() { // funkcja odbywająca się wraz z startem strony
  x = randomFloat(window.maps[window.current_map].minx, window.maps[window.current_map].maxx);
  y = randomFloat(window.maps[window.current_map].miny, window.maps[window.current_map].maxy);
  kordynaty = {lat: y,lng: x};
  sv = new google.maps.StreetViewService();
  map = new google.maps.Map(document.getElementById("mapa"), { // stworzenie obiektu mapa, przypisanie do diva
    zoom: window.maps[window.current_map].zoom,
    center: window.maps[window.current_map].center,
    clickableIcons: false
  });
  panorama = new google.maps.StreetViewPanorama( // stworzenie obiektu streetview (panorama), przypisanie do diva
    document.getElementById("pano")
  );
  window.getPano = function getPano(start = false) {
    sv.getPanorama({
      location: kordynaty,
      source: google.maps.StreetViewSource.OUTDOOR,
      radius: 80000
    }).then(function (res) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
          if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let response = JSON.parse(xmlHttp.response);
            if (!response.address.CountryCode) {
              x = randomFloat(window.maps[window.current_map].minx, window.maps[window.current_map].maxx);
              y = randomFloat(window.maps[window.current_map].miny, window.maps[window.current_map].maxy);
              kordynaty = {lat: y,lng: x};
              getPano();
            } else {
              window.current_country = response.address.CountryCode;
              if (window.current_map != "Świat") {
                if (window.maps[window.current_map].countries.indexOf(window.current_country) >= 0) {
                  processSVData(res).then(function (response) {
                    if (!start) {
                      window.use_time();
                    }
                    document.getElementById("start").style.display = "block";
                    document.getElementById("mapa").style.display = "block";
                  });
                } else {
                  x = randomFloat(window.maps[window.current_map].minx, window.maps[window.current_map].maxx);
                  y = randomFloat(window.maps[window.current_map].miny, window.maps[window.current_map].maxy);
                  kordynaty = {lat: y,lng: x};
                  getPano();
                }
              } else {
                processSVData(res).then(function (response) {
                  if (!start) {
                    window.use_time();
                  }
                  document.getElementById("start").style.display = "block";
                  document.getElementById("mapa").style.display = "block";
                });
              }
            }
          }
        }
        let coords = res.data.location.latLng.toString().slice(1).slice(0, -1);
        let coords2 = coords.split(",");
        let coords3 = coords2.reverse();
        let coords4 = coords3.join(",");
        let coords_good = coords4.split(",");
        xmlHttp.open("GET", `https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=pjson&featureTypes=&location=${coords_good}`, true);
        xmlHttp.send(null);
      },
      function (err) {
        x = randomFloat(window.maps[window.current_map].minx, window.maps[window.current_map].maxx);
        y = randomFloat(window.maps[window.current_map].miny, window.maps[window.current_map].maxy);
        kordynaty = {
          lat: y,
          lng: x
        };
        getPano();
      }
    );
  } // funkcja getPanorama szuka zdjęc streetview na lokalizacji 'kordynaty' w zasięgu 8000 metrów
  window.use_time = function () {
    document.getElementById("czas").innerHTML = "00:00";
    document.querySelector("#czas").style.color = "#fff";
    document.querySelector("#czas").style.animation = "";
    czas = 0;
    time_interval = setInterval(function () {
      if (window.time_limit != 0) {
        if (czas >= window.time_limit) {
          przegrana()
        } else if (window.time_limit - czas < 11) {
          document.querySelector("#czas").style.color = "#d63031";
          document.querySelector("#czas").style.animation = "heartBeat 2s infinite";
        }
      }
      czas++;
      let czas_format = czas;
      if (czas < 10) {
        czas_format = `00:0${czas}`;
      } else if (czas < 60) {
        czas_format = `00:${czas}`;
      } else if (czas >= 60) {
        let minuty = Math.floor(czas / 60)
        let sekundy = czas - (minuty * 60)
        if (minuty < 10) {
          minuty = '0' + minuty;
        }
        if (sekundy < 10) {
          sekundy = '0' + sekundy;
        }
        czas_format = `${minuty}:${sekundy}`;
      }
      if (window.time_limit != 0) {
        if (czas <= window.time_limit) {
          document.getElementById("czas").innerHTML = czas_format;
        }
      } else {
        document.getElementById("czas").innerHTML = czas_format;
      }
    }, 1000)
  }
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
      clearInterval(time_interval)
      document.querySelector("#czas").style.animation = "";
      var xmlHttp = new XMLHttpRequest();
      xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          let response = JSON.parse(xmlHttp.response);
          if (response.address.CountryCode && response.address.CountryCode == window.current_country) {
            document.querySelector("#kordy").style.display = "block";
            runda++;
            document.querySelector("#runda").innerHTML = "Streak : " + runda;
            if (document.querySelector("#login")) {
              let cs_max = parseInt(document.querySelector("#cs_max").innerText);
              let login = document.querySelector("#login").innerText;
              if (runda > cs_max) {
                var xmlHttp2 = new XMLHttpRequest();
                xmlHttp2.open("GET", `set_cs_max.php?login=${login}&cs_max=${runda}&type=max`, true);
                xmlHttp2.send(null);
              }
              window.cs_sum++;
              var xmlHttp3 = new XMLHttpRequest();
              xmlHttp3.onreadystatechange = function () {}
              xmlHttp3.open("GET", `set_cs_max.php?login=${login}&cs_sum=${window.cs_sum}&type=sum`, true);
              xmlHttp3.send(null);
            }
          } else {
            przegrana()
          }
        }
      }
      let coords = e.latLng.toString().slice(1).slice(0, -1);
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
  window.getPano(true);
}
async function processSVData({data}) {
  let location = data.location;
  panorama.setOptions({
    showRoadLabels: false,
    clickToGo: window.move,
    disableDefaultUI: !window.move,
    zoomControl: window.zoom,
    scrollwheel: window.zoom,
    panControl: window.pan,
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
  document.getElementById("runda").innerHTML = "Streak : " + runda;
  clearInterval(time_interval)
  initMap();
}

function przegrana() {
  clearInterval(time_interval)
  document.querySelector("#czas").style.display = "none";
  if (document.querySelector("#login")) {
    let cs_max = parseInt(document.querySelector("#cs_max").innerText);
    if (runda > cs_max) {
      document.querySelector("#runda").innerHTML = `Streak: ${runda} - Koniec<br/><span class="nowy_rekord">Nowy Rekord!</span>`;
    } else {
      document.querySelector("#runda").innerHTML = `Streak: ${runda} - Koniec<br/>Rekord: ${cs_max}`;
    }
  } else {
    document.querySelector("#runda").innerHTML = `Streak: ${runda} - Koniec`;
  }
  document.getElementById("pano").innerHTML = "";
  document.getElementById("start").style.display = "none";
  document.querySelector("#runda").classList.add("runda");
  document.querySelector("#runda").removeAttribute("id");
  document.querySelector(".runda").innerHTML = document.querySelector(".runda").innerHTML + `<br /><a href="country_streak.php?map=${window.current_map}"><button>Nowa gra</button></a>`;
}
