let map_size = window.maps[window.current_map].size;
let panorama, map, kordynaty, marker, odleglosc, distance, sv, czas, time_interval;
let licznik = 0;
let runda = 1;
let punkty = 0;
window.locations = [];
window.guesses = [];
window.distances = [];
window.points = [];
window.times = [];
window.random_colors = ["#03e9f4", "#03e9f4", "#03e9f4", "#03e9f4", "#03e9f4"];

function initMap() { // funkcja odbywająca się wraz z startem strony
  let x = randomFloat(window.maps[window.current_map].minx, window.maps[window.current_map].maxx); // losowane kordynaty
  let y = randomFloat(window.maps[window.current_map].miny, window.maps[window.current_map].maxy); //
  kordynaty = {
    lat: y,
    lng: x
  };
  sv = new google.maps.StreetViewService();
  map = new google.maps.Map(document.getElementById("mapa"), { // stworzenie obiektu mapa, przypisanie do diva
    zoom: window.maps[window.current_map].zoom,
    center: window.maps[window.current_map].center, // kordynaty środka mapy
    clickableIcons: false,
    disableDefaultUI: true,
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
            if (window.current_map != "Świat") {
              if (window.maps[window.current_map].countries.indexOf(response.address.CountryCode) >= 0) {
                processSVData(res).then(function (response) {
                  if (!start) {
                    window.use_time();
                  }
                  document.getElementById("kordy").style.display = "block";
                  document.getElementById("start").style.display = "block";
                  document.getElementById("mapa").style.display = "block";
                }, function (error) {
                  console.log(error)
                });
              } else {
                x = randomFloat(window.maps[window.current_map].minx, window.maps[window.current_map].maxx); // losowane kordynaty
                y = randomFloat(window.maps[window.current_map].miny, window.maps[window.current_map].maxy);
                kordynaty = {
                  lat: y,
                  lng: x
                };
                getPano();
              }
            } else {
              processSVData(res).then(function (response) {
                if (!start) {
                  window.use_time();
                }
                document.getElementById("kordy").style.display = "block";
                document.getElementById("start").style.display = "block";
                document.getElementById("mapa").style.display = "block";
              }, function (error) {
                console.log(error)
              });
            }
          }
        }
        xmlHttp.open("GET", `https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=pjson&featureTypes=&location=${x}%2C${y}`, true);
        xmlHttp.send(null);
      },
      function (err) {
        x = randomFloat(window.maps[window.current_map].minx, window.maps[window.current_map].maxx); // losowane kordynaty
        y = randomFloat(window.maps[window.current_map].miny, window.maps[window.current_map].maxy);
        kordynaty = {lat: y,lng: x};
        getPano();
      }
    );
  } // funkcja getPanorama szuka zdjęc streetview na lokalizacji 'kordynaty' w zasięgu 8000 metrów
  getPano();
  google.maps.event.addListener(map, 'dblclick', function (e) { // funkcja aktywuje się po kliknięciu na mapę
    if (licznik == 0) { // licznik - ilość postawionych znaczników, przyjmuje wartości 0, 1
      marker.setVisible(true);
      licznik = licznik + 1;
      const marker2 = new google.maps.Marker({ // stworzenie marker2 - znacznika postawionego przez gracza
        position: e.latLng, // e.latLng to kordynaty 'e' czyli miejsca eventu - kliknięcia
        map,
      });
      document.querySelector("#czas").style.animation = "";
      clearInterval(time_interval);
      window.times.push(czas);
      window.guesses.push(e);
      odleglosc = new google.maps.Polyline({ // Polyline to obiekt linia pomiędzy dwoma punktami na mapie
        path: [marker.getPosition(), marker2.getPosition()],
        map: map
      });
      distance = haversine_distance(marker, marker2);
      let distance2 = ((distance.toFixed(3)) * 1.6).toFixed(3);
      window.distances.push(distance2);
      // size 100 - 1 punkt na 100 metrów
      let punkty_now = Math.round(5000 - (distance2 * 1000 / map_size));
      if (punkty_now < 0) {
        punkty_now = 0;
      }
      window.points.push(punkty_now);
      document.getElementById("runda").innerHTML = document.getElementById("runda").innerHTML + " - " + distance2 + ` km (${punkty_now}pkt)`;
      punkty += Math.round(punkty_now);
    }
  });

}

window.initMap = initMap;

function kordy() { // losuje nowe kordy, czyści wszystkie divy
  if (runda >= 5) {
    if (window.locations.length > window.guesses.length) {
      clearInterval(time_interval);
      window.guesses.push("brak");
      window.distances.push("brak");
      window.points.push("brak");
      window.times.push("brak");
    }
    document.getElementById("czas").style.display = "none";
    document.getElementById("kordy").style.display = "none";
    document.getElementById("start").style.display = "none";
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
    for (i = 0; i < runda; i++) {
      let location_end;
      let guesses_end;
      if (window.locations[i]) {
        location_end = new google.maps.Marker({
          position: window.locations[i].latLng,
          map: map_end,
          icon: 'images/go.png',
        });
      }
      let randomColor = "";
      while (randomColor.length < 6) {
        randomColor = Math.floor(Math.random() * 0xffffff).toString(16);
      }
      randomColor2 = randomColor;
      randomColor = "#" + randomColor;
      if (window.guesses[i] && window.guesses[i] != "brak") {
        window.random_colors[i] = randomColor;
        guess_end = new google.maps.Marker({
          position: window.guesses[i].latLng,
          map: map_end,
          icon: {
            path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
            strokeColor: randomColor,
            scale: 4
          }
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
    document.querySelector('#pano').style.filter = 'invert()';
    let endbox = document.createElement("div");
    endbox.classList.add("endbox");
    endbox.innerHTML = `<h2>Gra skończona!</h2><br/><h3>Punkty: ${punkty}</h3><table><tr><th>Runda</th><th>Odległość</th><th>Punkty</th><th>Czas</th></tr></table><br /><a class="nowa_gra" href="gra.php?map=${window.current_map}">Nowa gra</a>`;
    document.body.appendChild(endbox);
    for (let j = 1; j <= runda; j++) {
      let odleglosc_end;
      if (window.distances[j - 1] && window.distances[j - 1] != "brak") {
        odleglosc_end = Math.ceil(window.distances[j - 1]) + "km";
      } else {
        odleglosc_end = "Brak";
      }
      let points_end;
      if (window.points[j - 1] && window.points[j - 1] != "brak") {
        points_end = Math.ceil(window.points[j - 1]);
      } else {
        points_end = "Brak";
      }
      let time_end;
      if (window.times[j - 1] && window.times[j - 1] != "brak") {
        time_end = window.times[j - 1];
        let czas_format = time_end;
        if (time_end < 10) {
          czas_format = `00:0${time_end}`;
        } else if (time_end < 60) {
          czas_format = `00:${time_end}`;
        } else if (time_end >= 60) {
          let minuty = Math.floor(time_end / 60)
          let sekundy = time_end - (minuty * 60)
          if (minuty < 10) {
            minuty = '0' + minuty;
          }
          if (sekundy < 10) {
            sekundy = '0' + sekundy;
          }
          czas_format = `${minuty}:${sekundy}`;
        }
        time_end = czas_format
      } else {
        time_end = "Brak";
      }
      document.querySelector("table").innerHTML = document.querySelector("table").innerHTML + `<tr>
                <td>${j}</td>
                <td>${odleglosc_end}</td>
                <td>${points_end}</td>
                <td>${time_end}</td>
            </tr>`;
    }
    let time_total = null;
    window.times.forEach(el => {
      if (el != "brak") {
        time_total += el;
      }
    })
    if (!time_total) {
      time_total = "Brak";
    } else {
      let czas_format = time_total;
      if (time_total < 10) {
        czas_format = `00:0${time_total}`;
      } else if (time_total < 60) {
        czas_format = `00:${time_total}`;
      } else if (time_total >= 60) {
        let minuty = Math.floor(time_total / 60)
        let sekundy = time_total - (minuty * 60)
        if (minuty < 10) {
          minuty = '0' + minuty;
        }
        if (sekundy < 10) {
          sekundy = '0' + sekundy;
        }
        czas_format = `${minuty}:${sekundy}`;
      }
      time_total = czas_format
    }
    document.querySelector("h3").innerHTML += `<br/>Czas: ${time_total}<br/><br/>`;
    let color_count = 0;
    window.random_colors.forEach(color => {
      let style = document.createElement("style");
      style.innerHTML = `tbody:nth-of-type(${color_count+2}) tr:hover td{background: ${color};}`;
      document.body.appendChild(style);
      color_count++;
    })
    if (document.querySelector("#login")) {
      var xmlhttp = new XMLHttpRequest();
      let login = document.querySelector("#login").innerText;
      let ukonczone = document.querySelector("#ukonczone").innerText;
      var url = "?login=" + login + "&ukonczone=" + ukonczone + "&wynik=" + punkty;
      xmlhttp.open("GET", "ukonczone.php" + url, true);
      xmlhttp.send();
    }
  } else {
    document.getElementById("pano").innerHTML = "";
    document.getElementById("mapa").innerHTML = "";
    document.getElementById("start").style.display = "none";
    document.getElementById("kordy").style.display = "none";
    licznik = 0;
    panorama = null;
    map = null;
    runda = runda + 1;
    document.getElementById("runda").innerHTML = "Runda " + runda + "/5";
    if (window.locations.length > window.guesses.length) {
      clearInterval(time_interval);
      window.guesses.push("brak");
      window.distances.push("brak");
      window.points.push("brak");
      window.times.push("brak");
    }
    initMap();
  }
}
