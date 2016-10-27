<!DOCTYPE html>
<html lang="en">
    
    <?php
        require_once("twitteroauth-master/autoload.php");
        session_start();
        require_once("twitteroauth-master/src/TwitterOAuth.php"); //Path to twitteroauth library

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

        //$content = $connection->get('followers/ids', array('user_id' => '537389908'));
        //$content = $connection->get('friends/ids', array('screen_name' => 'Lynus1990'));
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
            #connection, #search {
                margin-top: 20px;
                text-align: center;
                margin-bottom: 30px;
            }
            input {
                border-radius: 8px;
                border: 2px solid black;
                font-size: 1em;
                width: 10em;
                margin-top: 6px;
                padding: 2px 5px;
            }
            #submit, #search_submit {
                width: 10.8em;
                background-color: #ddddff;
                color: #666;
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
            #search_submit {
                width: auto;
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
        </style>
    </head>
    <body>
        <h1>Tweexis</h1>
        
        <form id="search">
            <input type="text" placeholder="Chercher un compte" id="search_user" />
            <input type="submit" id="search_submit" value="OK" />
        </form>
        
        <div id="container">            
            <div id="menu">
                <ul id="onglets">
                    <li class="active"><a href="#" id="followers_link"> Abonnés </a></li>
                    <li><a href="#" id="followings_link"> Abonnements </a></li>
                    <li><a href="#" id="favorites_link"> Favoris </a></li>
                </ul>
            </div>
            
            <div id="content">
                <form id="add_fav">
                    <input type="text" placeholder="Entrer un compte" />
                    <input type="submit" id="add_submit" value="Ajouter aux favoris" />
                </form>
                
                <div id="follow_content">
                    <p id="count_followers">Nombre d'abonnés : <span class='count_followers'></span></p>
                    <p id="count_followings">Nombre d'abonnements : <span class='count_followings'></span></p>
                    <p id="count_fav">Nombre de favoris : <span class='count_fav'></span></p>
                    <table id="follows">
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
                function redirect(u, o) {
                    document.location.href = 'index.php?user='+u;
                    return false;
                }
                var getUrlParameter = function getUrlParameter(sParam) {
                    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                        sURLVariables = sPageURL.split('&'),
                        sParameterName,
                        i;

                    for (i = 0; i < sURLVariables.length; i++) {
                        sParameterName = sURLVariables[i].split('=');

                        if (sParameterName[0] === sParam) {
                            return sParameterName[1] === undefined ? true : sParameterName[1];
                        }
                    }
                };
                var user = getUrlParameter('user');
                init();

                $('#search_submit').click(function() {
                    user = $('#search_user').val();
                    return redirect(user);
                });

                function init() {
                    $('#count_followers').show();
                    $('#count_followings').hide();
                    $('#count_fav').hide();
                    $('#add_fav').hide();
                }
                
                $('#followers_link').click(function() {
                    $('li').removeClass('active');
                    $(this).parent().addClass('active');
                    init();
                });
                
                $('#followings_link').click(function() {
                    $('li').removeClass('active');
                    $(this).parent().addClass('active');
                    $('#count_followings').show();
                    $('#count_followers').hide();
                    $('#count_fav').hide();
                    $('#add_fav').hide();
                });
                
                $('#favorites_link').click(function() {
                    $('li').removeClass('active');
                    $(this).parent().addClass('active');
                    $('#count_fav').show();
                    $('#count_followers').hide();
                    $('#count_followings').hide();
                    $('#add_fav').show();
                });

                /*if (onglet === 0) {
                    var result = <?php /*echo $connection->get('followers/ids', array('screen_name' => $_GET['user']));*/ ?>
                    alert(result);
                }*/
            });
        </script>
    </body>
</html>