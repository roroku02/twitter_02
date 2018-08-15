<?php
    session_start();
    require_once('./twitteroauth/autoload.php');

    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "7NOMZlf2flq1aefQbG1GJy3Zu";
    $ConsumerSecret = "XHKNEFh2TARhY2hIU2ilnaDAIvKd90fq8J2Lap7DLw5lMuVl6m";
    $AccessToken = "1010729931750367233-fS26kr6N9NvWTEwBpqlCOR88H1lXve";
    $AccessTokenSecret = "TkWef0v1hjONZsQSmc4QcTWUuOujWYEn3VLss9JqNl1R5";

    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken,$AccessTokenSecret);

    $tweet = "";
    $now_time = time();
    $today = date("Y-m-d");

    $RT_sort = FALSE;
    if(isset($_GET['option'])){
        if($_GET['option'] == "popular"){
            $tweet_sort = $_GET['option'];
        }elseif($_GET['option'] == "rt"){
            $RT_sort = TRUE;
            $tweet_sort = "recent";
        }else{
            $tweet_sort = "recent";
        }
    }else{
        $tweet_sort = "recent";
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

    ob_implicit_flush(true);
    while(@ob_end_clean()); 
    
    if($RT_sort == TRUE){
        echo "<div id = 'loading' style='position:absolute;top:50%;left:50%;'>";
        echo "<img src= './images/loading.gif'>";
        echo "<div id = 'percent'>";
        for($i = 0;$i < 10; $i++){
            echo "<script>document.getElementById( 'percent' ).innerHTML = ''</script>";
            echo ($i + 1) * 10 . "%完了<br/>";        
            //print_r($params);
            ${'search_tweets' . $i} = $connection -> get('search/tweets',$params);
            unset(${'search_tweets' . $i} -> search_metadata);
            ${'search_tweet' . $i} = ${'search_tweets' . $i} -> statuses;
            $max_id = end(${'search_tweets' .$i}->statuses)->id;
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
            $sort[$key] = $value->retweet_count;
        }
        array_multisort($sort,SORT_DESC,$search_tweet);
    }else{
        $search_tweets = $connection -> get('search/tweets',$params);
        $search_tweet = $search_tweets->statuses;
    }
    $count = sizeof($search_tweet);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title><?php echo $_SESSION['search_word']; ?>の検索結果</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link href="http://fonts.googleapis.com/earlyaccess/sawarabigothic.css" rel="stylesheet" />
    <link href="http://fonts.googleapis.com/earlyaccess/mplus1p.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/colorbox.css">
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/lightbox.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-ja.js"></script>
    <link rel="stylesheet" type="text/css" href="css/lightbox.css" />
    <script type="text/javascript" src="js/lightbox.js"></script>
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

<body>
    <div class="bread">
        <ul>
            <li>
                <a href="index.html">トップページ</a>
            </li>
            <li>
                <a href="news.html">ニュースジャンル選択</a>
            </li>
        </ul>
    </div>
    <section class="search">
        <p><?php echo $count ?>件のツイートを取得</p>
        <h2>"<?php echo $_SESSION['search_word']; ?>"のTwitter検索結果</h2>
    <?php
    //*******debug mode*********
    //echo "debug mode<br><br>"; print_r($search_tweet);
    //**************************
   ?>
   <h3>並び替え</h3>
   <form action="search.php" method="get">
       <input type="radio" name="option" value="recent" onchange="this.form.submit()" <?php if($tweet_sort == "recent" && $RT_sort == FALSE) echo "checked"; ?>>新しい順</input>
       <input type="radio" name="option" value="popular" onchange="this.form.submit()" <?php if($tweet_sort == "popular") echo "checked"; ?>>人気度順</input> 
       <input type="radio" name="option" value="rt" onchange="this.form.submit()" <?php if($tweet_sort == "recent" && $RT_sort == TRUE) echo "checked"; ?>>RT順</input> 
       <input type="checkbox" name="only_today" value="1" <?php if($only_today == TRUE) echo "checked"?>  onchange="this.form.submit()">今日のツイートに限定する</input>
    </form>

    <?php
    $count = sizeof($search_tweet);
    for($Tweet_num = 0; $Tweet_num < $count; $Tweet_num++){
        $TweetID = $search_tweet[$Tweet_num]->{"id"};
        $Date = $search_tweet[$Tweet_num]->{"created_at"};
        $Tweet_time = strtotime($Date);
        $relative_time = $now_time - $Tweet_time;
        $Text = $search_tweet[$Tweet_num]->{"full_text"};
        $User_ID = $search_tweet[$Tweet_num]->{"user"}->{"screen_name"};
        $User_Name = $search_tweet[$Tweet_num]->{"user"}->{"name"};
        $Profile_image_URL = $search_tweet[$Tweet_num]->{"user"}->{"profile_image_url_https"};
        $Retweet_Count = $search_tweet[$Tweet_num]->{"retweet_count"};
        $Favorite_Count = $search_tweet[$Tweet_num]->{"favorite_count"};
        $Retweet_TRUE = FALSE;
        $media_URL = NULL;

        //RT処理
        if(isset($search_tweet[$Tweet_num]->{"retweeted_status"})){
            $Retweet_TRUE = TRUE;
            $Date = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"created_at"};
            $RT_User = $User_Name;
            $Text = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"full_text"};
            $User_ID = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"user"}->{"screen_name"};
            $User_Name = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"user"}->{"name"};
            $Profile_image_URL = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"user"}->{"profile_image_url_https"};
            $Retweet_Count = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"retweet_count"};
            $Favorite_Count = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"favorite_count"};    
            if(isset($search_tweet[$Tweet_num]->{"retweeted_status"}->{"entities"}->{"hashtags"}));
                $search_tweet[$Tweet_num]->{"entities"}->{"hashtags"} = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"entities"}->{"hashtags"};
            if(isset($search_tweetA[$Tweet_num]->{"retweeted_status"}->{"extended_entities"}->{"media"})){
                foreach($search_tweet[$Tweet_num]->{"retweeted_status"}->{"extended_entities"}->{"media"} as $media){
                    $media_URL[] = $media->media_url_https;
                }
            }
        }

            //ハッシュタグ処理
            $search_tweet[$Tweet_num]->{"entities"}->{"hashtags"} = array_reverse($search_tweet[$Tweet_num]->{"entities"}->{"hashtags"});
            foreach($search_tweet[$Tweet_num]->{"entities"}->{"hashtags"} as $hashtags){
                if(isset($hashtags)){
                    $hashtag_text = $hashtags->text;
                    $hashtag_indices = $hashtags->indices;
                    $left_text = mb_substr($Text,0,$hashtag_indices[0]);
                    $right_text = mb_substr($Text,($hashtag_indices[0] + ($hashtag_indices[1] - $hashtag_indices[0])));
                    $after_text = '<a href="http://localhost/twitter_02/search.php?search_word=' . rawurlencode("#" . $hashtag_text) . '" class = "iframe">#' . $hashtag_text . '</a>';
                    $Text = $left_text . $after_text . $right_text;
                }
            }

            //メディア処理
            if(isset($search_tweet[$Tweet_num]->{"extended_entities"}->{"media"})){
                foreach($search_tweet[$Tweet_num]->{"extended_entities"}->{"media"} as $media){
                    $media_URL[] = $media->media_url_https;
                }
            }

            //URL処理
            if(isset($search_tweet[$Tweet_num]->{"entities"}->{"urls"})){
                foreach($search_tweet[$Tweet_num]->{"entities"}->{"urls"} as $urls){
                    $Text = str_replace($urls->url,'<a href="'.$urls->expanded_url.'" class= "iframe">'.$urls->display_url.'</a>',$Text);
                    if(strpos($urls->expanded_url,'youtu.be') !== false){
                        $y_url = $urls->expanded_url;
                        $y_path = parse_url($y_url,PHP_URL_PATH);
                        $y_thumb = "http://i.ytimg.com/vi$y_path/mqdefault.jpg";
                        $y_url = "http://www.youtube.com/embed$y_path";
                    }
                }
            }
            
        ?>
            <ul>
            <?php if($Retweet_TRUE == TRUE){ ?>
                <p class="retweet_sentence"><i class="fas fa-retweet fa-fw"></i><?php echo $RT_User; ?>がリツイート</p>
            <?php } ?>
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
            <li>
                <?php echo nl2br($Text); ?>
            </li>
            <?php if(isset($media_URL)){ 
                $media_Count = sizeof($media_URL);?>
                <li>
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
            }?>
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
        </ul>
    <?php
        }
    ?>
    </section>
</body>

</html>