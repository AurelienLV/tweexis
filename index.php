<!DOCTYPE html>
<html lang="en">
    
    <?php
        require_once("twitteroauth-master/autoload.php");
        require_once("twitteroauth-master/src/TwitterOAuth.php"); //Path to twitteroauth library
        session_start();

        if (!isset($_SESSION['connexion'])) {
            $twitteruser = "Lynus1990";
            $notweets = 30;
            $consumerkey = "ZRdeOBWT80jYi1XDXIHn3mQO4";
            $consumersecret = "Z1pR8rBDvkChGdJCa1Nxs5tUdcue5S1kQkSAmdqxscU6q5upJX";
            $accesstoken = "537389908-hbnuUkpfPqHSz1tJptxcLsOjOZvraDtdILXKd3FK";
            $accesstokensecret = "djWWWjur74S0OsHRdfUWxry4X8iXwOVrD3A0rS60a896D";
            function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
              $connection = new Abraham\TwitterOAuth\TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
              return $connection;
            }

            $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
            $_SESSION['connexion'] = $connection;
        }

        function readMyFile()
        {
            $filename = 'accounts.txt';
            if (is_file($filename)) {
                $content = file_get_contents($filename);
                return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", ',', htmlspecialchars($content));
            }
            return false;
        }
    ?>

    <head>
        <meta charset="utf-8">
        <title>Tweexis</title>
        <style type="text/css">
            body {
                font-size: 1.2em;
            }
            h1 {
                font-size: 3em;
                text-align: center;
                margin: 2px;
            }
            input {
                border-radius: 8px;
                border: 2px solid black;
                font-size: 1em;
                width: 10em;
                margin-top: 6px;
                padding: 2px 5px;
            }
            #onglets {
                font-size: 1.2em;
                position : absolute;
                border : 1px solid transparent;
                padding : 0;
                list-style-type : none;
                left : 50%;
                margin-top : 0;
                width : 430px;
                margin-left : -215px;
            }
            #onglets li {
                float : left;
                height : 30px;
                background-color: #ddddff;
                margin : 2px 2px 0 2px !important;
                margin : 1px 2px 0 2px;
                border : 1px solid #9EA0A1;
            }
            #onglets li.active {
                border-bottom: 1px solid #fff;
                background-color: #fff;
            }
            #onglets a {
                display : block;
                color : #666;
                text-decoration : none;
                padding : 4px;
            }
            #onglets a:hover {
                background : #fff;
            }
            #menu {
                margin-top: 10px;
                border-bottom : 1px solid #9EA0A1;
                padding-bottom : 34px;
            }
            #me, #signout_link {
                display: inline-block;
                margin-left: 10px;
                color: #666;
                width: auto;
                border: 2px solid black;
                padding: 3px;
                border-radius: 10px;
                text-decoration: none;
                background-color: #ddddff;
            }
            #me:hover, #signout_link:hover {
                background-color: white;
            }
            #content {
                margin-top: 30px;
                text-align: center;
            }
            table {
                margin: auto;
            }
            th, td {
                text-align: center;
                border-bottom: 1px solid grey;
            }
            .alert {
                color: red;
            }
            .info {
                color: green;
                font-weight: bold;
                font-size: 20px;
                text-align: center;
                margin-bottom: 30px;
            }
            .count_fav {
                color: green;
                font-weight: bold;
                font-size: 20px;
            }
        </style>
    </head>
    <body>
        <h1>Tweexis</h1>
        
        <div class="info"></div>
        
        <div id="container">            
            <div id="menu">
                <ul id="onglets">
                    <li class="active"><a href="#" id="accounts_link"> Surveiller </a></li>
                    <li><a href="#" id="favorites_link"> Gérer les comptes </a></li>
                </ul>
            </div>
            
            <div id="content">
                <form id="add_fav">
                    <input type="text" placeholder="Entrer un compte" />
                    <input type="submit" id="add_submit" value="Ajouter aux favoris" />
                </form>
                
                <div id="table_content">
                    <p id="count_fav">Nombre de favoris : <span class='count_fav'></span></p>
                    <table id="table_accounts">
                        <tr>
                            <th>Pseudo</th>
                            <th>Identifiant</th>
                            <th>Nombre d'abonnés</th>
                            <th>Nombre d'abonnements</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script language="JavaScript">
            $( document ).ready(function() {
                init();

                function init() {
                    $('#add_fav').hide();
                    fillAccounts();
                }
                
                $('#accounts_link').click(function() {
                    onglet = 0;
                    $('li').removeClass('active');
                    $(this).parent().addClass('active');
                    init();
                });
                
                $('#favorites_link').click(function() {

                });
                
                function fillAccounts() {
                    var listUsers = '<?php echo readMyFile(); ?>';
                    $.ajax({
                        type: "POST",
                        url: "getusers.php",
                        datatype: "html",
                        data: {'users': listUsers},
                        success: function(data) {
                            var s = $('<div/>').html(data).text();
                            s = s.replace(/\\n/g, "\\n")  
                                           .replace(/\\"/g, '\\"')
                                           .replace(/\\&/g, "\\&")
                                           .replace(/\\r/g, "\\r")
                                           .replace(/\\t/g, "\\t")
                                           .replace(/\\b/g, "\\b")
                                           .replace(/\\f/g, "\\f");
                            s = s.replace(/[\u0000-\u0019]+/g,""); 
                            var array = JSON.parse(s);
                            console.debug(array);
                        },
                        error: function(data) {
                            displayError('Non');
                        }
                    });
                }

                function displayInfo(msg) {
                    $('.info').text(msg);
                    $('.info').css('color', 'green').css('font-size', '18px');
                }
                
                function displayError(msg) {
                    $('.info').text(msg)
                    $('.info').css('color', 'red').css('font-size', '18px');
                }
            });
        </script>
    </body>
</html>