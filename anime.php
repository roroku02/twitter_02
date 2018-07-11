<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>アニメ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css"  href="css/style.css" />
</head>
<body>
    <div class="form">
        <h1>アニメタグ検索</h1>
        <form action="anime.php" method="get">
            <select name="year">
                <option value="2014">2014</option>
                <option value="2015">2015</option>
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
            </select>
            <select name="season">
                <option value="1">冬</option>
                <option value="2">春</option>
                <option value="3">夏</option>
                <option value="4">秋</option>
            </select>
            <input type="submit" value="表示">
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
