var updatespeed = 2; // get updates every x seconds

var power = false;

function doGET(url) {
    var http = new XMLHttpRequest();
    http.open("GET", url, false);
    http.send(null);
    return http.responseText;
}

function setPower(pow) {
    power = pow;
    document.getElementById("power").value = (power ? "Stop Mining" : "Start Mining");
}

function setPowerEnabled(enabled) {
    document.getElementById("power").disabled = !enabled;
}



setInterval(function() {
    var stats = JSON.parse(doGET("api.php?endpoint=getstats&format=json"));
    setPowerEnabled(stats.success);

    if(stats.success) {
        document.getElementById("totalhashrate").innerHTML = stats.hashrate;
        document.getElementById("totalshareshr").innerHTML = Math.round(stats.shares / stats.time * 60);
        document.getElementById("totalfails").innerHTML = stats.fails;
    } else {
        console.log(stats.message);
    }
}, updatespeed * 1000);
