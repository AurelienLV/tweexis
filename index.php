    <?php
        require_once("twitteroauth-master/autoload.php");
        require_once("twitteroauth-master/src/TwitterOAuth.php"); //Path to twitteroauth library
        session_start();
    ?>
<!DOCTYPE html>
<html lang="en">
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
            .form {
                text-align: center;
            }
            #ok {
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
            #ok:hover {
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
            th > a {
                color: mediumvioletred;
                font-size: 14px;
                text-decoration: none;
            }
            td > img {
                max-width: 70px;
                max-height: 70px;
            }
            td > a {
                color: black;
            }
            th, td {
                padding: 0 10px;
            }
            .modal {
                 display:    none;
                 position:   fixed;
                 z-index:    1000;
                 top:        0;
                 left:       0;
                 height:     100%;
                 width:      100%;
                 background: rgba( 255, 255, 255, .8 ) 
                             url('http://i.stack.imgur.com/FhHRx.gif') 
                             50% 50% 
                             no-repeat;
            }
            body.loading {
                 overflow: hidden;   
            }
            body.loading .modal {
                 display: block;
            }
        </style>
    </head>
    <body>
        <h1>Tweexis</h1>
    <?php
        
        if (isset($_POST['pwd'])) {
            $hashed_password = '$1$DyEB00Pc$fpvjpnOLSdDfnjRXp3cxk0';
            $user_input = $_POST['pwd'];
            if (hash_equals($hashed_password, crypt($user_input, $hashed_password))) {
                $_SESSION['ok'] = true;
            }
        }
        
        if (!isset($_SESSION['ok'])) {
    ?>
        <div class="form">
            <form action="index.php" method="POST">
                <input type="password" name="pwd" />
                <input type="submit" value="OK" id="ok" />
            </form>
        </div>
    <?php
        } else {
    ?>  

    <?php
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

        if (isset($_POST['all_fav'])) {
            $filename = 'accounts.txt';
            file_put_contents($filename, $_POST['all_fav']);
        }
    ?>
        <div class="info"></div>
        
        <div id="container">            
            <div id="menu">
                <ul id="onglets">
                    <li class="active"><a href="#" id="accounts_link"> Surveiller </a></li>
                    <li><a href="#" id="favorites_link"> Gérer les comptes </a></li>
                </ul>
            </div>
            
            <div id="content">
                <div id="table_content">
                    <p id="count_fav">Nombre de favoris (existants) : <span class='count_fav'></span></p>
                    
                    <table id="table_accounts">
                        <tr>
                            <th></th>
                            <th><a href="?sort=id&order=<?php if (isset($_GET['order']) && isset($_GET['sort']) && $_GET['sort'] == 'id' && $_GET['order'] == 'asc') { echo 'desc'; } else { echo 'asc'; } ?>">Identifiant</a></th>
                            <th><a href="?sort=pseudo&order=<?php if (isset($_GET['order']) && isset($_GET['sort']) && $_GET['sort'] == 'pseudo' && $_GET['order'] == 'asc') { echo 'desc'; } else { echo 'asc'; } ?>">Pseudo</a></th>
                            <th><a href="?sort=followers&order=<?php if (isset($_GET['order']) && isset($_GET['sort']) && $_GET['sort'] == 'followers' && $_GET['order'] == 'asc') { echo 'desc'; } else { echo 'asc'; } ?>">Nombre d'abonnés</a></th>
                            <th><a href="?sort=friends&order=<?php if (isset($_GET['order']) && isset($_GET['sort']) && $_GET['sort'] == 'friends' && $_GET['order'] == 'asc') { echo 'desc'; } else { echo 'asc'; } ?>">Nombre d'abonnements</a></th>
                            <th><a href="?sort=ratio&order=<?php if (isset($_GET['order']) && isset($_GET['sort']) && $_GET['sort'] == 'ratio' && $_GET['order'] == 'asc') { echo 'desc'; } else { echo 'asc'; } ?>">abonnés / abonnements</a></th>
                        </tr>
                    </table>
                    
                    <form id="add_fav" action="index.php" method="POST">100 maximum
                        <textarea id="all_fav" name="all_fav" form="add_fav" rows="20" cols="50"></textarea><br/>
                        <input type="submit" id="add_submit" value="Editer" />
                    </form>
                </div>
            </div>
        </div>
        <div class="modal"><!-- Place at bottom of page --></div>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script language="JavaScript">
            var body = $("body");
            $(document).ajaxStart(function() { body.addClass("loading"); });
            $(document).ajaxStop(function() { body.removeClass("loading"); });
            $( document ).ready(function() {
                init();

                function init() {
                    $('#add_fav').hide();
                    fillAccounts();
                }
                
                $('#accounts_link').click(function() {
                    $('#table_accounts').show();
                    if ($(this).parent().hasClass('active')) {
                        return;
                    }
                    $('li').removeClass('active');
                    $(this).parent().addClass('active');
                    init();
                });
                
                $('#favorites_link').click(function() {
                    $('#add_fav').show();
                    $('li').removeClass('active');
                    $(this).parent().addClass('active');
                    $('#table_accounts').hide();
                    var listUsers = '<?php echo readMyFile(); ?>'.replace(/,/g, '\n');
                    $('#all_fav').text(listUsers);
                });

                function fillAccounts() {
                    $('td').parent().remove();
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
                            $('.count_fav').text(array.length);
                            array = sortList(getURLParameter("sort"), array);
                            $.each(array, function(i,value) {
                                $('#table_accounts').append('<tr><td>'+
                                    '<img src="'+value['profile_image_url']+'" />'
                                +'</td><td><a target="_blank" href="http://www.twitter.com/'+value['screen_name']+'">@'+
                                    value['screen_name']
                                +'</a></td><td>'+
                                    value['name']
                                +'</td><td>'+
                                    value['followers_count']
                                +'</td><td>'+
                                    value['friends_count']
                                +'</td><td>'+
                                    (parseFloat(value['followers_count']) / parseFloat(value['friends_count'])).toFixed(3)
                                +'</td></tr>');
                            });
                        },
                        error: function(data) {
                            displayError('Non');
                        }
                    });
                }
                
                function getURLParameter(name) {
                    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
                }
                
                function sortList(criteria, array) {
                    var newArray = array;
                    switch (criteria) {
                        case 'id':
                            newArray.sort(SortBySName);
                            break;
                        case 'pseudo':
                            newArray.sort(SortByName);
                            break;
                        case 'friends':
                            newArray.sort(SortByFriends);
                            break;
                        case 'followers':
                            newArray.sort(SortByFollow);
                            break;
                        case 'ratio':
                            newArray.sort(SortByRatio);
                            break;
                    }
                    return newArray;
                }
                
                function SortBySName(a, b) { return SortBy('screen_name', a, b); }
                function SortByName(a, b) { return SortBy('name', a, b); }
                function SortByFollow(a, b) { return SortBy('followers_count', a, b); }
                function SortByFriends(a, b) { return SortBy('friends_count', a, b); }
                function SortByRatio(a, b) {
                    a['ratio'] = parseFloat(a['followers_count']) / parseFloat(a['friends_count']);
                    b['ratio'] = parseFloat(b['followers_count']) / parseFloat(b['friends_count']);
                    return SortBy('ratio', a, b);
                }

                function SortBy(criteria, a, b){
                    var a2 = a[criteria];
                    if (typeof a2 === 'string') a2 = a2.toLowerCase();
                    var b2 = b[criteria];
                    if (typeof b2 === 'string') b2 = b2.toLowerCase();
                    var compare = ((a2 < b2) ? -1 : ((a2 > b2) ? 1 : 0));
                    if (getURLParameter('order') === 'asc') {
                        return compare;
                    } else {
                        return (-1)*compare;
                    }
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
            
            var limit = 100; // <---max no of lines you want in textarea
            var textarea = document.getElementById("all_fav");
            var spaces = textarea.getAttribute("cols");

            textarea.onkeyup = function() {
               var lines = textarea.value.split("\n");

               for (var i = 0; i < lines.length; i++) 
               {
                     if (lines[i].length <= spaces) continue;
                     var j = 0;

                    var space = spaces;

                    while (j++ <= spaces) 
                    {
                       if (lines[i].charAt(j) === " ") space = j;  
                    }
                lines[i + 1] = lines[i].substring(space + 1) + (lines[i + 1] || "");
                lines[i] = lines[i].substring(0, space);
              }
                if(lines.length>limit)
                {
                    textarea.style.color = 'red';
                    setTimeout(function(){
                        textarea.style.color = '';
                    },500);
                }    
               textarea.value = lines.slice(0, limit).join("\n");
            };
        </script>
<?php
    }
?>
    </body>
</html>