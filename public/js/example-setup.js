var airportDatabase;
var airport;
var building;
var floor;
var map;
var floorView;

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function map_loaded(_map)
{
    map = _map;

    var floorId = getParameterByName('floorId');
    if ( floorId ) {
      // The two subsequent lines are necessary to show a specific floor of a venue.

      // Hides all the floors.
      map.getView().hideAllFloors();

      // Shows the floor given.
      map.getView().showFloor( floorId );
    }
    floorView = map.getView().getFloorView(map.getFloorId());

    // Pan and zoom the map to a more interesting part of the airport.
    map.setCenter(new locuslabs.maps.LatLng(47.44511778255521,-122.30211768493555));
    map.setRadius(90);

    example_ready();
}

function show_floor(_floor)
{
    floor = _floor;

    // Render the map of the floor into the #map-canvas div.
    floor.loadMap(document.getElementById('map-canvas'),map_loaded);
}

function show_building(_building)
{
    building = _building;

    // Get the list available floors for this building, then load and show the first one.
    var floors = building.listFloors();
    show_floor( building.loadFloor(floors[0].floorId ) );
}

function airport_loaded(_airport)
{
    airport = _airport;

    // Get the list available buildings for this airport, then load the first one.
    var buildings = airport.listBuildings();
    show_building( airport.loadBuilding( buildings[0].buildingId ) );
}

document.addEventListener("DOMContentLoaded", function(event) {
    // Initialize the Account Id
    locuslabs.setup( { accountId: "A11F4Y6SZRXH4X", assetsBase : "https://assets.locuslabs.com/accounts/" }, function () {
        // Create an AirportDatabase object then load an airport.
        airportDatabase = new locuslabs.maps.AirportDatabase();
        // The LocusLabs#AirportDatabase class method loadAirport takes an airport code such as 'SEA' and a callback when the airport has loaded.
        // We aptly named out callback airport_loaded()
        airportDatabase.loadAirport('sea',airport_loaded);
    });
});
