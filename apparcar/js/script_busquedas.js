var posicion_inicial = {lat: 4.634101, lng:  -74.105314}; // Bogotá por defecto
var parqueaderosTrabajo, parqueaderosFiltrados, map, markers_p_trabajo = [];

var iconos = {
  normal: {
    name: 'normal',
    icono: 'imagenes/muyalta.png'
  },
  filtrado: {
    name: 'filtrado',
    icono: 'imagenes/muybaja.png'
  }
};
// Función que inicializa el mapa
function initMap() {
    // Create a map object and specify the DOM element for display.
    // Geolocaliza al usuario para centrar el mapa según dicha posición
    if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
            initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            posicion_inicial = initialLocation;
        });
    }
    // Ubicamos la posición inicial 
    document.getElementById('latLon').innerHTML = 
             '<p>Lat: ' + posicion_inicial.lat + ' Lng: ' + posicion_inicial.lng + '</p>';
    // Ubico el mapa
    map = new google.maps.Map(document.getElementById('map'), {
        center: posicion_inicial,
        zoom: 12
    });
    // Para acceder a información de los marcadores
    infoWindow = new google.maps.InfoWindow();
    // Se ponen los marcadores cuando carga la pagina
    ponerMarcadores();
    // Captura el evento de clik sobre el mapa
    google.maps.event.addListener(map, "click", function (e) {

        var clickLat = e.latLng.lat();
        var clickLon = e.latLng.lng();

        // capturo la latitud y longitud del mapa al hacer click
         document.getElementById('latLon').innerHTML = 
             '<p>Lat: ' + clickLat + ' Lng: ' + clickLon + '</p>';
    });
}
// Función que ubica los marcadores según las búsquedas realizadas
function ponerMarcadores(){

  readTextFile("parqueaderos_trabajo.json", function(text){
    parqueaderosTrabajo = JSON.parse(text);
    //console.log(parqueaderosTrabajo);
    readTextFile("parqueaderos_filtrados.json", function(text){
      parqueaderosFiltrados = JSON.parse(text);
      //console.log(parqueaderosFiltrados);
      // Ubicando los parqueaderos en el mapa
      for (x in parqueaderosTrabajo) {
        // Verifica si el parqueadero esta filtrado
        var esta = 0;
        for(y in parqueaderosFiltrados){
          if(parqueaderosTrabajo[x].idParqueadero == parqueaderosFiltrados[y].idParqueadero){
            esta = 1; // El parqueadero está en los 2 array
          }
        }
        // Como el parqueadero no esta, se almacena
        if(esta == 0){
          var marker_trabajo = new google.maps.Marker({
            position: {lat: parqueaderosTrabajo[x].latitud, lng: parqueaderosTrabajo[x].longitud},
            map: map,
            icon:iconos["normal"].icono, // Se asigna el icono normal por defecto
            title: 'Parqueadero Número: ' + parqueaderosTrabajo[x].idParqueadero
          });
          markers_p_trabajo.push(marker_trabajo);
        }

        // Creando un 'cierre' para retener los datos correctos, observe cómo paso los datos actuales en el ciclo al 'cierre' (marker, myObj, x)
        /*
        (function(marker, myObj, x) {
          // Capturando el evento de 'click' para el marcador
          google.maps.event.addListener(marker, "click", function(e) {
            infoAdicional = info(myObj[x]);
            infoWindow.setContent(infoAdicional);
            infoWindow.open(map, marker);
          });
        })(marker, myObj, x);
        markers.push(marker); // Añadiento un marcador a un Array
        */
      }
      // se agrega el parqueadero al cluster de parqueaderos de trabajo
      var markerCluster = new MarkerClusterer(map, markers_p_trabajo,
        {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
      // Se posicionan los parqueaderos filtrados unicamente

      for (x in parqueaderosFiltrados) {
        var marker_filtrado = new google.maps.Marker({
          position: {lat: parqueaderosFiltrados[x].latitud, lng: parqueaderosFiltrados[x].longitud},
          map: map,
          icon:iconos["filtrado"].icono, // Se asigna el icono normal por defecto
          title: 'Parqueadero Número: ' + parqueaderosFiltrados[x].idParqueadero
        });
      }

    }); // Cierre de la lectura de los parqueaderos filtrados
  }); // Cierre de la laectura de los parquea de trabajo

  // Se ubica la posición inicial del usuario
    var marker = new google.maps.Marker({
      map: map,
      position: posicion_inicial,
      title: 'Yo!'
    });
}
// Lee un archivo de texto de forma asincrona
function readTextFile(file, callback) {
    var rawFile = new XMLHttpRequest();
    rawFile.overrideMimeType("application/json");
    rawFile.open("GET", file, true);
    rawFile.onreadystatechange = function() {
        if (rawFile.readyState === 4 && rawFile.status == "200") {
            callback(rawFile.responseText);
        }
    }
    rawFile.send(null);
}
