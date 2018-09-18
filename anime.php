<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>アニメタグ検索</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
        crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"  href="css/style.css" />
</head>
<body>
    <div class="bread">
        <ul>
            <li><a href="index.html">トップページ</a></li>
            <li>タグ検索画面</li>
        </ul>
    </div>
    <div class="form">
        <h1>アニメタグ検索</h1>
        <form action="anime.php" method="get" id="anime_tag_search_button">
            <select name="year">
                <option value="2014" <?php if($_GET['year'] == 2014) echo "selected"; ?>>2014</option>
                <option value="2015" <?php if($_GET['year'] == 2015) echo "selected"; ?>>2015</option>
                <option value="2016" <?php if($_GET['year'] == 2016) echo "selected"; ?>>2016</option>
                <option value="2017" <?php if($_GET['year'] == 2017) echo "selected"; ?>>2017</option>
                <option value="2018" <?php if($_GET['year'] == 2018) echo "selected"; ?>>2018</option>
            </select>
            <select name="season">
                <option value="1" <?php if($_GET['season'] == 1) echo "selected"; ?>>冬</option>
                <option value="2" <?php if($_GET['season'] == 2) echo "selected"; ?>>春</option>
                <option value="3" <?php if($_GET['season'] == 3) echo "selected"; ?>>夏</option>
                <option value="4" <?php if($_GET['season'] == 4) echo "selected"; ?>>秋</option>
            </select>
            <input type="submit" value="表示" id="anime_tag_search_submit">
        </form>
    <?php
    $year = $_GET['year'];
    $season = $_GET['season'];
    
    $anime_tag_url = "http://api.moemoe.tokyo/anime/v1/master/$year/$season";
    //echo $anime_tag_url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $anime_tag_url); // 取得するURLを指定
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 実行結果を文字列で返す
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // サーバー証明書の検証を行わない
    $response = curl_exec($ch);
    $response = json_decode($response, true);
    //print_r($response);
    curl_close($ch); 
    ?>
    </div>
   
    <?php if(isset($response)){ ?>
        <div class="option_button">
            <form action="search.php" method="get" id="search_button">
                <?php for($i = 0;$i < count($response);$i++){
                        $anime_title[] = $response[$i]['title'];
                        if(isset($response[$i]['twitter_hash_tag'])){
                            $anime_tag[] = $response[$i]['twitter_hash_tag'];
                        }else $anime_tag[$i] = NULL;
                }
                for($i = 0;$i < count($anime_title);$i++){ ?>
                    <button type="submit" name="search_word" value="#<?php echo $anime_tag[$i]; ?>"><?php echo $anime_title[$i]; ?></button>
                <?php } ?>
            </form>
    <?php } ?>
    </div>
</body>
</html>
