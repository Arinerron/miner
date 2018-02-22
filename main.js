var updatespeed = 1; // get updates every x seconds

var power = false;
var ispower = true;
var last = false;
var first = true;

function doGET(url) {
    var http = new XMLHttpRequest();
    http.open("GET", url, false);
    http.send(null);
    return http.responseText;
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
    var stats = JSON.parse(doGET("api.php?endpoint=getstats&format=json"));

    if(stats.success) {
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
