<?php
    session_start();
    require_once('./twitteroauth/autoload.php');

    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "7NOMZlf2flq1aefQbG1GJy3Zu";
    $ConsumerSecret = "XHKNEFh2TARhY2hIU2ilnaDAIvKd90fq8J2Lap7DLw5lMuVl6m";
    $AccessToken = "1010729931750367233-fS26kr6N9NvWTEwBpqlCOR88H1lXve";
    $AccessTokenSecret = "TkWef0v1hjONZsQSmc4QcTWUuOujWYEn3VLss9JqNl1R5";

    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken,$AccessTokenSecret);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>トレンドワード選択</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
        crossorigin="anonymous">

</head>
<body>
    <div class="bread">
        <ul>
            <li><a href="index.html">トップページ</a></li>
            <li>トレンドワード選択</li>
        </ul>
    </div>
    <div class="container_q">
        <div class="chara">
            <img src="./images/chibi.png" alt="chibi">
        </div>
    <div class="arrow_box">
        <p>
            現在のTwitterトレンドワードを表示しています<br>
            ワードをクリックするとツイートを表示します
        </p>
</div>
</div>
<section class="Trend">
        <?php $Trend_responce = $connection -> get('trends/place', array('id' => '1110809'));
        foreach($Trend_responce[0] -> {"trends"} as $Trend){
            $Trend_word[] = $Trend->name;
        }
        if(isset($Trend_word)){
        ?>
        <form action="search.php" method="get" id="trend_search_button">
        <?php for($i = 0;$i < count($Trend_word); $i++){
            echo '<button type="submit" name="search_word" value='."$Trend_word[$i]".'>'."$Trend_word[$i]".'</button>';
        }
        echo '</form>';
    }else echo "トレンドワードの取得に失敗しました\n";
        ?>
    </section>
</body>

<script src="js/main.js"></script>
</html>