<?php
    header('Content-Type: text/html; charset=UTF-8');
    session_start();
    require_once('TwitterAppOAuth.php');
    //エラー無効
    error_reporting(0);

    $ConsumerKey = "7NOMZlf2flq1aefQbG1GJy3Zu";
    $ConsumerSecret = "XHKNEFh2TARhY2hIU2ilnaDAIvKd90fq8J2Lap7DLw5lMuVl6m";

    $connection = new TwitterAppOAuth($ConsumerKey,$ConsumerSecret);
    $tweet = "";
    $now_time = time();
    $today = date("Y-m-d");

    $RT_sort = FALSE;
    $Fav_sort = FALSE;

    if(isset($_GET['option'])){
        if($_GET['option'] == "popular"){
            $tweet_sort = "recent";
        }elseif($_GET['option'] == "rt"){
            $RT_sort = TRUE;
            $tweet_sort = "recent";
        }elseif($_GET['option'] == "fav"){
            $Fav_sort = TRUE;
            $tweet_sort = "recent";
        }else{
            $tweet_sort = "recent";
        }
    }else{
        $tweet_sort = "recent";
        $_GET['search_word'] = htmlspecialchars($_GET['search_word'],ENT_QUOTES,'UTF-8');
        $_SESSION['search_word'] = $_GET['search_word'];
    }

    if(isset($_GET['only_today']) == 1){
        $_SESSION['search_word'] .= " since:$today";
        $only_today = TRUE;
    }else{
        $_SESSION['search_word'] = str_replace( " since:$today", "", $_SESSION['search_word']);
        $only_today = NULL;
    }

    $now_time = time();
    $max_id = NULL;
    $params = array(
        'q' => $_SESSION['search_word'],
        'exclude' => 'retweets',
        'count' => 100,
        'tweet_mode' => 'extended',
        'result_type' => $tweet_sort
    );
    if(strpos($_SESSION['search_word'],'dog') || strpos($_SESSION['search_word'],'cat') !== false){
        $params[filter] = 'images';
    }
    if($_GET['option'] == 'popular'){
        if(isset($params[filter])){
            $params[filter] .= " verified";
        }
        $params[filter] = 'verified';
    }


    ob_implicit_flush(true);
    while(@ob_end_clean()); 
    
    if($RT_sort == TRUE){
        print_r($search_tweet);
        echo "<div id = 'loading' style='position:fixed;top:50%;left:50%;'>";
        echo "<img src= './images/loading1.gif'>";
        echo "<div id = 'percent'>";
        for($i = 0;$i < 10; $i++){
            echo "<script>document.getElementById( 'percent' ).innerHTML = ''</script>";
            echo ($i + 1) * 10 . "%完了<br/>";   
            ${'search_tweets_obj' . $i} = $connection -> get('search/tweets',$params);
            ${'search_tweets' . $i} = json_decode(${'search_tweets_obj' . $i},true);
            unset(${'search_tweets' . $i}[search_metadata]);
            ${'search_tweet' . $i} = ${'search_tweets' . $i}[statuses];
            $max_id = end(${'search_tweets' .$i}[statuses])[id_str];
            if(isset($max_id)){
                if(PHP_INT_SIZE == 4)
                    $params['max_id'] = $max_id;
                elseif(PHP_INT_SIZE == 8)
                    $params['max_id'] = $max_id - 1;
            }
            //echo "$i : " . sizeof(${'search_tweet' . $i}) . "<br>";
        }
        echo "</div>";
        $search_tweet = array_merge_recursive($search_tweet0,$search_tweet1,$search_tweet2,$search_tweet3,$search_tweet4,$search_tweet5,$search_tweet6,$search_tweet7,$search_tweet8,$search_tweet9);
        echo "</div>";
        
        foreach($search_tweet as $key => $value){
            $sort[$key] = $value[retweet_count];
        }
        array_multisort($sort,SORT_DESC,$search_tweet);
    }elseif($Fav_sort == TRUE){
        if(isset($search_tweet0)){
            foreach($search_tweet as $key => $value){
                $sort[$key] = $value[favorite_count];
            }
            array_multisort($sort,SORT_DESC,$seach_tweet);
        }else{
        echo "<div id = 'loading' style='position:fixed;top:50%;left:50%;'>";
        echo "<img src= './images/loading1.gif'>";
        echo "<div id = 'percent'>";
        for($i = 0;$i < 10; $i++){
            echo "<script>document.getElementById( 'percent' ).innerHTML = ''</script>";
            echo ($i + 1) * 10 . "%完了<br/>";        
            //print_r($params);
            ${'search_tweets_obj' . $i} = $connection -> get('search/tweets',$params);
            ${'search_tweets' . $i} = json_decode(${'search_tweets_obj' . $i},true);
            unset(${'search_tweets' . $i}[search_metadata]);
            ${'search_tweet' . $i} = ${'search_tweets' . $i}[statuses];
            $max_id = end(${'search_tweets' .$i}[statuses])[id_str];
            if(isset($max_id)){
                //echo "<br>------<br>next max_id :" . $max_id . "<br>-------<br>";
                $params['max_id'] = $max_id;
            }
            //echo "$i : " . sizeof(${'search_tweet' . $i}) . "<br>";
        }
        echo "</div>";
        $search_tweet = array_merge_recursive($search_tweet0,$search_tweet1,$search_tweet2,$search_tweet3,$search_tweet4,$search_tweet5,$search_tweet6,$search_tweet7,$search_tweet8,$search_tweet9);
        echo "</div>";
        
        foreach($search_tweet as $key => $value){
            $sort[$key] = $value[favorite_count];
        }
        array_multisort($sort,SORT_DESC,$search_tweet);
        }
    }else{
        $search_tweets_obj = $connection -> get('search/tweets',$params);
        $search_tweets = json_decode($search_tweets_obj,true);
        $search_tweet = $search_tweets[statuses];
    }
    $count = sizeof($search_tweet);
    $search_tag = array(
        'total' => "ニュース（総合）",
        'kokunai' => "国内ニュース",
        'NetNews' => "ネットニュース",
        'WorldNews' => "国外ニュース",
        'IT' => "ITニュース",
        'NetNews' => "ネットニュース",
        'soccer' => "サッカー",
        'baseball' => "野球",
        'sports' => "スポーツ（総合）",
        'youtube' => "YouTube",
        'niconico' => "ニコニコ動画",
        '#nicovideo' => "みんなが共有した動画（ニコニコ）",
        "@YouTubeさん" => "みんなが共有した動画（YouTube）",
        'nintendo' => "任天堂",
        'PS' => "PlayStation",
        'smartgame' => "スマホゲーム",
        'game' => "ゲーム（総合）",
        'JPOP' => "JPOP",
        'WorldMusic' => "国外音楽",
        'KPOP' => "KPOP",
        'anime' => "アニメ（総合）",
        'cat' => "猫",
        'dog' => "犬",
    );
    $prev_url_list = array(
        'total' => "news.html",
        'kokunai' => "news.html",
        'NetNews' => "news.html",
        'WorldNews' => "news.html",
        'IT' => "news.html",
        'NetNews' => "news.html",
        'soccer' => "sports.html",
        'baseball' => "sports.html",
        'sports' => "sports.html",
        'youtube' => "douga.html",
        'niconico' => "douga.html",
        '#nicovideo' => "douga.html",
        "@YouTubeさん" => "douga.html",
        'nintendo' => "game.html",
        'PS' => "douga.html",
        'smartgame' => "game.html",
        'game' => "game.html",
        'JPOP' => "music.html",
        'WorldMusic' => "music.html",
        'KPOP' => "music.html",
        'anime' => "anime.html",
        'cat' => "animal.html",
        'dog' => "animal.html",
    );
    foreach($search_tag as $key => $value){
        if(strpos($_SESSION['search_word'],$key) !== false){
            $search_word = $value;
            break;
        }else{
            $search_word = $_SESSION['search_word'];
            str_replace( " since:$today", "", $search_word);
        }
    }
    foreach($prev_url_list as $key => $value){
        if(strpos($_SESSION['search_word'],$key) !== false){
            $prev_url = $value;
            break;
        }else {
            $prev_url = "index.html";
        }
    }
    
 
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>ツイート検索:<?php echo $search_word ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link href="https://fonts.googleapis.com/earlyaccess/sawarabigothic.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/earlyaccess/mplus1p.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/colorbox.css">
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/lightbox.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-ja.js"></script>
    <link rel="stylesheet" type="text/css" href="css/lightbox.css" />
    <script type="text/javascript" src="js/lightbox.js"></script>
    <script type="text/javascript" src="js/jquery.waypoints.min.js"></script>
    <script src="js/slick.js"></script>
    <link rel="stylesheet" href="css/slick.css">
</head>
<script>
    lightbox.option({
        'alwaysShowNavOnTouchDevices': true,
        'fadeDuration': 200,
        'resizeDuration': 400
    })
    $(document).ready(function () {
        $(".iframe").colorbox({ iframe: true, width: "80%", height: "80%" });
   });
</script>

<body onload="end_loading()">
    <div class="bread">
        <ul>
            <li>
                <a href="index.html">トップページ</a>
            </li>
            <li>
                <a href="<?php echo $prev_url; ?>">ジャンル選択画面</a>
            </li>
            <li>
                検索結果
            </li>
        </ul>
    </div>
    <section class="search">
        <div class="container_q">
            <div class="chara">
                <img src="./images/chibi.png" alt="chibi">
            </div>
            <div class="arrow_box">
                <h2>
                    <strong><?php echo $search_word; ?></strong>に関するツイートを検索しました<br>
                </h2>
                <p>
                    <?php echo $count; ?>件のツイートを取得しました
                    <?php if($count == 0){ echo "検索結果は0件でした。<br>検索条件を変えてもう一度試してください！"; }?>
                </p>
            </div>
        </div>
    <?php
    //*******debug mode*********
    //echo "debug mode<br><br>"; print_r($search_tweet);
    //**************************
   ?>
   <div class="search_option">
   <h3>検索条件</h3>
   <form action="search.php" method="get">
       <input type="radio" name="option" value="recent" id="select1" onchange="this.form.submit()" <?php if($tweet_sort == "recent" && $RT_sort == FALSE && $Fav_sort == FALSE) echo "checked"; ?>>
       <label for="select1">新しい順</label>
       <input type="radio" name="option" value="popular" id="select2" onchange="this.form.submit()" <?php if($_GET['option'] == "popular") echo "checked"; ?>>
       <label for="select2">認証済みユーザのみ</label> 
       <input type="radio" name="option" value="rt" id="select3" onclick="load()" onchange="this.form.submit()" <?php if($tweet_sort == "recent" && $RT_sort == TRUE) echo "checked"; ?>>
       <label for="select3">RT順</label> 
       <input type="radio" name="option" value="fav" id="select4" onchange="this.form.submit()" <?php if($tweet_sort == "recent" && $Fav_sort == TRUE) echo "checked"; ?>>
       <label for="select4">いいね順</label>
       <input type="checkbox" name="only_today" id="check1" value="1" <?php if($only_today == TRUE) echo "checked"?>  onchange="this.form.submit()">
       <label for="check1">今日のツイートに限定する</label>
    </form>
</div>
<div class="load">
</div>
</div>
<div class="content_load">
    <div class="wrap">
        <img src="images/loading.gif" alt="">
        <p>コンテンツの読み込みに時間が掛かっています...</p>
    </div>
</div>
    <div class="js-slider">
    <?php
    $count = sizeof($search_tweet);
    for($Tweet_num = 0; $Tweet_num < 100; $Tweet_num++){
        $TweetID = $search_tweet[$Tweet_num]{"id_str"};
        $Date = $search_tweet[$Tweet_num]{"created_at"};
        $Tweet_time = strtotime($Date);
        $relative_time = $now_time - $Tweet_time;
        $Text = $search_tweet[$Tweet_num]{"full_text"};
        $User_ID = $search_tweet[$Tweet_num]{"user"}{"screen_name"};
        $User_Name = $search_tweet[$Tweet_num]{"user"}{"name"};
        $Profile_image_URL = $search_tweet[$Tweet_num]{"user"}{"profile_image_url_https"};
        $Retweet_Count = $search_tweet[$Tweet_num]{"retweet_count"};
        $Favorite_Count = $search_tweet[$Tweet_num]{"favorite_count"};
        $Retweet_TRUE = FALSE;
        $media_URL = NULL;

        //RT処理
        if(isset($search_tweet[$Tweet_num]{"retweeted_status"})){
            $Retweet_TRUE = TRUE;
            $Date = $search_tweet[$Tweet_num]{"retweeted_status"}{"created_at"};
            $RT_User = $User_Name;
            $Text = $search_tweet[$Tweet_num]{"retweeted_status"}{"full_text"};
            $User_ID = $search_tweet[$Tweet_num]{"retweeted_status"}{"user"}{"screen_name"};
            $User_Name = $search_tweet[$Tweet_num]{"retweeted_status"}{"user"}{"name"};
            $Profile_image_URL = $search_tweet[$Tweet_num]{"retweeted_status"}{"user"}{"profile_image_url_https"};
            $Retweet_Count = $search_tweet[$Tweet_num]{"retweeted_status"}{"retweet_count"};
            $Favorite_Count = $search_tweet[$Tweet_num]{"retweeted_status"}{"favorite_count"};    
            if(isset($search_tweet[$Tweet_num]{"retweeted_status"}{"entities"}{"hashtags"}));
                $search_tweet[$Tweet_num]{"entities"}{"hashtags"} = $search_tweet[$Tweet_num]{"retweeted_status"}{"entities"}{"hashtags"};
            if(isset($search_tweetA[$Tweet_num]{"retweeted_status"}{"extended_entities"}{"media"})){
                foreach($search_tweet[$Tweet_num]{"retweeted_status"}{"extended_entities"}{"media"} as $media){
                    $media_URL[] = $mediamedia_url_https;
                }
            }
        }

            //ハッシュタグ処理
            $search_tweet[$Tweet_num]{"entities"}{"hashtags"} = array_reverse($search_tweet[$Tweet_num]{"entities"}{"hashtags"});
            foreach($search_tweet[$Tweet_num]{"entities"}{"hashtags"} as $hashtags){
                if(isset($hashtags)){
                    mb_internal_encoding('UTF-8');
                    $hashtag_text = $hashtags[text];
                    $hashtag_indices = $hashtags[indices];
                    $left_text = mb_substr($Text,0,$hashtag_indices[0]);
                    $right_text = mb_substr($Text,($hashtag_indices[0] + ($hashtag_indices[1] - $hashtag_indices[0])));
                    $after_text = '<a href="http://localhost/twitter_02/search.php?search_word=' . rawurlencode("#" . $hashtag_text) . '" class = "iframe">#' . $hashtag_text . '</a>';
                    $Text = $left_text . $after_text . $right_text;
                }
            }

            //メディア処理
            if(isset($search_tweet[$Tweet_num]{"extended_entities"}{"media"})){
                foreach($search_tweet[$Tweet_num]{"extended_entities"}{"media"} as $media){
                    $media_URL[] = $media[media_url_https];
                }
            }

            //URL処理
            if(isset($search_tweet[$Tweet_num]{"entities"}{"urls"})){
                foreach($search_tweet[$Tweet_num]{"entities"}{"urls"} as $urls){
                    $Text = str_replace($urls[url],'<a href="'.$urls[expanded_url].'" target="_blank">'.$urls[display_url].'</a>',$Text);
                    //YouTubeリンク取得・サムネイル取得
                    if(strpos($urls[expanded_url],'youtu.be') !== false){
                        $y_url = $urls[expanded_url];
                        $y_path = parse_url($y_url,PHP_URL_PATH);
                        $y_thumb = "https://i.ytimg.com/vi$y_path/mqdefault.jpg";
                        $y_url = "https://www.youtube.com/embed$y_path";
                    }
                    //ニコニコ動画リンク取得・サムネイル取得
                    if(strpos($urls[expanded_url],'nico.ms') !== false){
                        $n_url = $urls[expanded_url];
                        $n_id = parse_url($n_url,PHP_URL_PATH);
                        $n_info = simplexml_load_file("https://ext.nicovideo.jp/api/getthumbinfo/$n_id");
                        $n_thumb_url = $n_info -> thumb -> thumbnail_url;
                        $n_thumb = str_replace('http','https',$n_thumb_url);
                        if(@file_get_contents($n_thumb .".M",NULL,NULL,0,1) !== false)
                            $n_thumb = $n_thumb .".M";
                        $n_url = "https://embed.nicovideo.jp/watch$n_id";
                    }
                }
            }   
        $Verified_User = FALSE;
        if($search_tweet[$Tweet_num]{"user"}{"verified"} == "1"){
            $Verified_User = TRUE;
        }
            
        ?>
            <ul class="slider-item">
            <?php if($Retweet_TRUE == TRUE){ ?>
                <p class="retweet_sentence"><i class="fas fa-retweet fa-fw"></i><?php echo $RT_User; ?>がリツイート</p>
            <?php } ?>
            <?php
            if($RT_sort == TRUE || $Fav_sort == TRUE){
                echo '<div class="rank">';
                if($Tweet_num == 0){
                    echo '<img src="images/rank_1.png">';
                }elseif($Tweet_num == 1){
                    echo '<img src="images/rank_2.png">';
                }elseif($Tweet_num == 2){
                    echo '<img src="images/rank_3.png">';
                }else {
                    echo $Tweet_num + 1;
                }
                echo '</div>';
            }
            echo '<div class="tweet">'; ?>
            <div id="Tweet_header">
                <div id="User_info">
                    <li>
                        <img src=<?php echo $Profile_image_URL; ?>></li>
                    <li id="User_Name">
                        <?php echo $User_Name ?>
                    </li>
                    <li id="User_ID">@
                        <?php echo $User_ID ?>
                    </li>
                    <?php if($Verified_User == TRUE){ ?>
                        <li id = "Verified_User" style="padding-left:5px;"><img src="images/verified_account.png"></li>
                    <?php } ?>
                </div>
                <li>
                    <?php if($relative_time < 60){ 
                    echo $relative_time . "秒前";
                    }elseif($relative_time >= 60 && $relative_time < (60 * 60)){
                        echo floor($relative_time / 60) . "分前";
                    }elseif($relative_time >= (60 * 60) && $relative_time < (60 * 60 * 24)){
                    echo floor($relative_time / (60 * 60)) . "時間前";
                    }elseif($relative_time >= (60 * 60 * 24)){
                        echo date("Y/n/j G:i",$Tweet_time);
                    }?>
                </li>
            </div>
            <li id="Tweet_main">
                <?php echo nl2br($Text); ?>
            </li>
            <?php if(isset($media_URL)){ 
                $media_Count = sizeof($media_URL);?>
                <li id="Tweet_media">
                    <?php for($media_num = 0;$media_num < $media_Count;$media_num++) { ?>
                    <a href="<?php echo $media_URL[$media_num]; ?>" class="img" data-lightbox="group<?php echo $Tweet_num; ?>" style="background-image: url(<?php echo $media_URL[$media_num] .':small'; ?>);"></a>
                    <?php } ?>
                </li>
                    <?php } ?>
            <?php if(isset($y_thumb)){
                echo '<li id="youtube_link">
                    <a href='."$y_url".' class="iframe" style="background-image:url('."$y_thumb".');"><i class="fab fa-youtube"></i></a>
                    </li>';
                $y_url = NULL;
                $y_thumb = NULL;
            }
            if(isset($n_thumb)){
                echo '<li id = "nico_link">
                    <a href='."$n_url".' class = "iframe" style="background-image:url('."$n_thumb".');"><i class="far fa-play-circle"></i></a>
                    </li>';
                $n_url = NULL;
                $n_thumb = NULL;
            }
            ?>
                <div id="RT_Counter">
                    <li>
                        <i class="fas fa-retweet fa-fw" style="color: green;"></i>
                        <?php echo $Retweet_Count; ?>
                    </li>
                    <li>
                        <i class="fas fa-heart" style="color: red;"></i>
                        <?php echo $Favorite_Count; ?>
                    </li>
            </div>
        <?php if($RT_sort == TRUE) echo '</div>'; ?>
        </ul>
    <?php
        }
    ?>
    </div>
    <div id="arrows"><img src="images/swipe.png">スワイプして次のツイートを表示します</div>

    </section>
    <nav class="page_top">
        <a href="#" alt="pageTOP"><i class="fas fa-angle-double-up"></i></a>    
    </nav>
    <div id="fade">
    <script src="js/main.js"></script>
</body>

</html>