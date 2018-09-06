<!DOCTYPE html>
<html>
<head>
  <title>Estado Parqueaderos</title>
  <!-- META -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link
  rel="stylesheet"
  href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
  integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

  <!-- Mis estilos CSS -->
  <link rel="stylesheet" href="styles.css">

  <!-- Mis otros JS -->
  <script type="text/javascript" src="script.js" ></script>

</head>
<body>
  <p id="demo"></p>
  <p id="map"></p>
  <div id="legend">
    <table id = "hiddenTable" class="table table-striped" style="display:none">
      <tbody>
        <tr>
          <td><img src="imagenes/muyalta.png" height=32 width=28/></td>
          <td>Muchos cupos [81, 100%]</td>
        </tr>
        <tr>
          <td><img src="imagenes/alta.png" height=32 width=28/></td>
          <td>Suficientes cupos [51, 80%]</td>
        </tr>
        <tr>
          <td><img src="imagenes/media.png" height=32 width=28/></td>
          <td>Algunos cupos [31, 50%]</td>
        </tr>
        <tr>
          <td><img src="imagenes/baja.png" height=32 width=28/></td>
          <td>Pocos cupos [11, 30%]</td>
        </tr>
        <tr>
          <td><img src="imagenes/muybaja.png" height=32 width=28/></td>
          <td>Muy pocos cupos [0, 10%]</td>
        </tr>
      </tbody>
    </table>

    <!-- Visible button -->
    <input id ="btnConvenciones" onclick="toogleButton(this,'hiddenButton','hiddenTable')" class="btn btn-outline-secondary btn-sm btn-block" type="button" value="Convenciones">
    <!-- Hidden input -->
    <input id="hiddenButton" type="hidden" value="0"></input>
  </div>

  <!--<script>
  var xmlhttp = new XMLHttpRequest();
  var x, txt="", myObj, map, infoWindow, infoAdicional="", markers = [], estado="";
  //var bogota = {lat: 4.6097100, lng:  -74.0817500};
  var bogota = {lat: 4.634101, lng:  -74.105314};

  var iconos = {
  muyalta: {
    name: 'Muchos cupos [81%, 100%]',
    icono: 'imagenes/muyalta.png'
  },
  alta: {
  name: 'Suficientes cupos [51%, 80%]',
  icono: 'imagenes/alta.png'
  },
  media: {
  name: 'Algunos cupos [31%, 50%]',
  icono: 'imagenes/media.png'
  },
  baja: {
  name: 'Pocos cupos [11%, 30%]',
  icono: 'imagenes/baja.png'
  },
  muybaja: {
  name: 'Muy pocos cupos [0%, 10%]',
  icono: 'imagenes/muybaja.png'
  }
  };

function toogleButton(callingElement,hiddenElement,hiddenTable)
{
// Check the color of the button
if (callingElement.classList.contains('btn-secondary'))
{
// If the button is 'unset'; change color and update hidden element to 1
callingElement.classList.remove('btn-secondary');
callingElement.classList.add('btn-success');
document.getElementById(hiddenElement).value="1";
document.getElementById(hiddenTable).style.display = "block"
}
else
{
// If the button is 'set'; change color and update hidden element to 0
callingElement.classList.remove('btn-success');
callingElement.classList.add('btn-secondary');
document.getElementById(hiddenElement).value="0";
document.getElementById(hiddenTable).style.display = "none"
}
}
function initMap() {
// Creando el mapa centrado en Bogotá
map = new google.maps.Map(document.getElementById('map'), {
center: bogota,
zoom: 13
});

infoWindow = new google.maps.InfoWindow();

// Se ponen los marcadores cuando carga la pagina
ponerMarcadores();

// Poniendo la leyenda de convenciones
var legend = document.getElementById('legend');
/*
for (var key in iconos) {
var type = iconos[key];
var name = type.name;
var icon = type.icono;
var div = document.createElement('div');
div.innerHTML = '<img src="' + icon + '">' + name;
legend.appendChild(div);
}
*/
map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(legend);

setInterval(function () {
// Se quitan todos los marcadores
eliminarMarcadores();
// Se ponen de nuevo los marcadores
ponerMarcadores();
}, 5000); // Se refrescan los marcadores cada 5 segundos

}

function eliminarMarcadores() {
//Elimino todos los marcadores del mapa
for (var i = 0; i < markers.length; i++) {
markers[i].setMap(null);
}

markers = [];
}

function ponerMarcadores(){
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
//console.log(this.responseText); // Muestra que esta llegando desde el servidor error o JSON
myObj = JSON.parse(this.responseText);
//console.log(myObj); // Muestra el array JSON que llegó del servidor
// Ubicando los parqueaderos en el mapa
for (x in myObj) {
estado = estadoOcupacion(parseInt(myObj[x].cupos), parseInt(myObj[x].cuposDisponibles));
var marker = new google.maps.Marker({
position: {lat: parseFloat(myObj[x].latitud), lng: parseFloat(myObj[x].longitud)},
map: map,
icon:iconos[estado].icono,
title: 'Parqueadero Número: ' + myObj[x].idParqueadero
});

// Creando un 'cierre' para retener los datos correctos, observe cómo paso los datos actuales en el ciclo al 'cierre' (marker, myObj, x)
(function(marker, myObj, x) {
// Capturando el evento de 'click' para el marcador
google.maps.event.addListener(marker, "click", function(e) {
infoAdicional = info(myObj[x]);
infoWindow.setContent(infoAdicional);
infoWindow.open(map, marker);
});
})(marker, myObj, x);
markers.push(marker); // Añadiento un marcador a un Array
}
/*
for (x in myObj) {
txt += myObj[x].idParqueadero + "<br>";
}
document.getElementById("demo").innerHTML = txt;
*/
}
};
xmlhttp.open("GET", "getParqueaderos.php", true);
xmlhttp.send();
}

function estadoOcupacion(cuposTotales, cuposDisponibles){

var str="";
var porcentaje = ((cuposDisponibles * 100)/cuposTotales);

if(porcentaje>= 0 && porcentaje <=10){
str = "muybaja";
}else if(porcentaje>10 && porcentaje <=30){
str = "baja";
}else if(porcentaje>30 && porcentaje <=50){
str = "media";
}else if(porcentaje>50 && porcentaje <=80){
str = "alta";
}else if(porcentaje>80 && porcentaje <=100){
str = "muyalta";
}
return str;
}

function info(obj) {
var str='<h6>Parqueadero Número: ' + obj.idParqueadero + '</h6>' +
'Cupos: ' + obj.cupos + '<br>' +
'Cupos Disponibles: ' + obj.cuposDisponibles + '<br>';

if(obj.esSobrecupo == '1'){
str += 'Dejar Llaves: SI <br>';
}else{
str += 'Dejar Llaves: NO <br>';
}

if(obj.esTarifaPlana == '1'){
str += 'Tarifa Plana: SI. Valor: $'+obj.valorTarifaPlana+' pesos <br>';
}else{
str += 'Tarifa Plana: NO <br>';
}

if(obj.valorTarifaBici == null){
str += 'Tarifa Bicicletas: No Registra <br>';
}else{
str += 'Tarifa Bicicletas: $'+obj.valorTarifaBici+' pesos/minuto<br>';
}

if(obj.valorTarifaMoto == null){
str += 'Tarifa Moto: No Registra <br>';
}else{
str += 'Tarifa Moto: $'+obj.valorTarifaMoto+' pesos/minuto<br>';
}

if(obj.valorTarifaCarro == null){
str += 'Tarifa Carro: No Registra <br>';
}else{
str += 'Tarifa Carro: $'+obj.valorTarifaCarro+' pesos/minuto<br>';
}

str += 'Dirección: ' + obj.direccion + '<br>';
return str;
}

</script> -->

<!-- Optional JavaScript --> 
<script
async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrVcEa9ago1h7ak5ePi_zr0kh95iz2hRU&callback=initMap">
</script>
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script
src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
</script>
<script
src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>


</body>
</html>
