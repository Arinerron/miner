<html>
    <head>
        <title>Mining Monitor</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body class="container">
        <div class="title">
            <h1 id="heading">Mining Rig - Control Panel</h1>
        </div>
        <div class="row">
            <div id="column" class="col-sm-4">
                <h2>Statistics</h2>
                <table width="100%">
                    <tr>
                        <th id="right">Hashrate</th>
                        <td id="left"><div class="inline" id="totalhashrate"></div> MH/s</td>
                    </tr>
                    <tr>
                        <th id="right">Shares/Hour</th>
                        <td id="left"><div class="inline" id="totalshareshr"></div> shares/hour</td>
                    </tr>
                    <tr>
                        <th id="right">Failures</th>
                        <td id="left"><div class="inline" id="totalfails"></div> fails</td>
                    </tr>
                </table>
            </div>
            <div id="column" class="col-sm-8">
                <h2>Controls</h2>
                <center><input type="button" class="button" id="power" value="Stop Mining" onclick="togglePower()" disabled=""></center>
            </div>
            <div id="column" class="col-sm-12">
                <h2>Graphics Cards</h2>
                <table id="cards" width="100%">

                </table>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="main.js"></script>
    </body>
</html>
