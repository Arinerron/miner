var updatespeed = 1; // get updates every x seconds

var power = false;
var ispower = true;
var last = false;
var first = true;
var requesting = false;

function doGET(url) {
    if(!requesting) {
        requesting = true;

        var http = new XMLHttpRequest();

        http.open("GET", url, false);
        http.send(null);

        requesting = false;

        return http.responseText;
    } else {
        return "";
    }
}

function setPower(pow) {
    power = pow;
}

function updatePower(pow) {
    document.getElementById("power").value = (pow ? "Stop Mining" : "Start Mining");
    last = power;
}

function setPowerEnabled(enabled) {
    document.getElementById("power").disabled = !enabled;
}


update = function() {
    var response = doGET("api.php?endpoint=getstats&format=json");
    var stats = "";
    var success = false;
    if(response.length != 0) {
        var stats = JSON.parse(response);
        var success = true;
    }

    if(success && stats.success) {
        if(ispower)
            setPowerEnabled(true);
        if(!last || first)
            updatePower(true);
        setPower(true);

        document.getElementById("totalhashrate").innerHTML = Math.round(stats.hashrate * 10) / 10;
        if(stats.time != 0)
            document.getElementById("totalshareshr").innerHTML = Math.round(stats.shares / stats.time * 60);
        else
            document.getElementById("totalshareshr").innerHTML = 0;
        document.getElementById("totalfails").innerHTML = stats.fails;

        var gpus = new Array();
        gpus.push(["GPU", "Temperature", "Fan Speed", "Hashrate"]);
        for(var i = 0; i < stats.gpus.length; i++)
            gpus.push([i, stats.gpus[i].temperature + "°C", stats.gpus[i].fan + "%", stats.gpus[i].hashrate + " MH/s"]);

        var table = document.getElementById("cards");

        var row = table.insertRow(-1);
        for (var i = 0; i < gpus[0].length; i++) {
           var headerCell = document.createElement("th");
           headerCell.innerHTML = gpus[0][i];
           row.appendChild(headerCell);
        }

        for (var i = 1; i < gpus[0].length; i++) {
           row = table.insertRow(-1);
           for (var j = 0; j < columnCount; j++) {
               var cell = row.insertCell(-1);
               cell.innerHTML = gpus[i][j];
           }
        }
    } else {
        if(!ispower)
            setPowerEnabled(true);
        if(last || first)
            updatePower(false);
        setPower(false);

        document.getElementById("totalhashrate").innerHTML = 0;
        document.getElementById("totalshareshr").innerHTML = 0;
        document.getElementById("totalfails").innerHTML = 0;
    }

    first = false;
}

update();
setInterval(update, updatespeed * 1000);

togglePower = function() {
    document.getElementById("power").value = (power ? "Stopping..." : "Starting...");
    doGET("api.php?endpoint=setpower&on=" + !power + "&format=json")
    setPowerEnabled(false);

    ispower = !power;
}
