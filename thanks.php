<!DOCTYPE html>
<html lang="ja">
 <head>
   <meta charset="UTF-8">
   <title>複数ファイル送信ページ1</title>
 </head>
 <body>
   <form method="post" enctype="multipart/form-data" >
     <input type="file" name="up[]" multiple>
     <input type="submit" value="送信"　name="regist">
   </form>
 </body>
</html>

<?php
if (isset($_FILES["regist"])) {
$files = [];
$MAXS = count($_FILES["up"]["tmp_name"] ?? []);
for($i=0,$j=0; $i < $MAXS; $i++)
{
 $size     = $_FILES["up"]["size"][$i]     ?? "";
 $tmp_file = $_FILES["up"]["tmp_name"][$i] ?? "";
 $org_file = $_FILES["up"]["name"][$i]     ?? "";
 if( $tmp_file != "" && $org_file != "" && 0 < $size &&       $size < 1048576 &&
     is_uploaded_file($tmp_file) )
 {
   $split = explode('.', $org_file); $ext = end($split);
   if( $ext != "" && $ext != $org_file )
   {
     $up_file = "file/" .date("Ymd_His.").mt_rand(1000,9999). ".$ext";
     if( move_uploaded_file( $tmp_file, $up_file) )
       $files[$j++] = array('size' => $size, 'up_file'  => $up_file,
                    'tmp_file' => $tmp_file, 'org_file' => $org_file);
   }
 }
}
}
?>