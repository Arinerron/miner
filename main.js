var updatespeed = 1; // get updates every x seconds

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


update = function() {
    var stats = JSON.parse(doGET("api.php?endpoint=getstats&format=json"));
    setPowerEnabled(true);

    if(stats.success) {
        setPower(true);
        document.getElementById("totalhashrate").innerHTML = Math.round(stats.hashrate * 10) / 10;
        document.getElementById("totalshareshr").innerHTML = Math.round(stats.shares / stats.time * 60);
        document.getElementById("totalfails").innerHTML = stats.fails;
    } else {
        setPower(false);
        console.log(stats.message);
    }
}
update();
setInterval(update, updatespeed * 1000);

togglePower = function() {
    doGET("api.php?endpoint=setpower&on=" + !power + "&format=json")
    setPowerEnabled(false);
}
