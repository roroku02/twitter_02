<?php
echo "処理を開始します。しばらくお待ちください...<br />";
mb_http_output('pass');
ob_implicit_flush(true);
while(@ob_end_clean()); 
 
for ( $i = 1; $i <= 10; $i++ ) {
sleep( 1 ); // 時間がかかる処理
echo $i * 10 ."件の処理を完了しました<br />";
}
echo "処理が完了しました<br />";
?>