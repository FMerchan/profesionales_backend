function addMapToRow() {

  var longitude = $(".data-longitude").find('.field-value').text().trim();
  var latitude = $(".data-latitude").find('.field-value').text().trim();
  var address = $(".data-address").find('.field-value').text().trim();

  var mapDiv = document.createElement("div");
  mapDiv.id = "map"; // Asegúrate de que el ID sea único

  var row = document.querySelector(".row");
  row.appendChild(mapDiv);

  var map = L.map('map').setView([latitude, longitude],15);

  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(map);

  L.marker([latitude, longitude]).addTo(map)
    .bindPopup(address)
    .openPopup();
}

document.addEventListener("DOMContentLoaded", function () {
    addMapToRow();
});