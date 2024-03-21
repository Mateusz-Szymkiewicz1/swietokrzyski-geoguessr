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
  if(window.locations){
    window.locations.push(location)
  }
  marker.setVisible(false);
  panorama.setPano(location.pano); // ustawia panoramę
  panorama.setPov({
    heading: 270,
    pitch: 0,
  })
}

function start() {
  window.getPano(true);
}

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