<?php
echo "処理を開始します。しばらくお待ちください...<br />";
mb_http_output('pass');
ob_implicit_flush(true);
while(@ob_end_clean()); 

echo "<img src='./images/loading.gif'>";
for ( $i = 1; $i <= 10; $i++ ) {
    sleep( 1 ); // 時間がかかる処理
    echo "<script>document.getElementById( 'loading' ).innerHTML = ''</script>";
    echo $i * 10 ."%完了<br />";
}
echo "処理が完了しました<br />";
?>