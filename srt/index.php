<?php
  ini_set('display_errors',1);
  ini_set('display_startup_errors',1);
  error_reporting(-1);
  if($_SERVER['REQUEST_METHOD']=='POST'){
  $decalage=intval($_POST['decal']);
  $tmp_name=$_FILES["file"]["tmp_name"];
  $name=$_FILES["file"]["name"];
  $uploads_dir='uploads';
  move_uploaded_file($tmp_name, $uploads_dir.'/'.$name);
  $file=file($uploads_dir.'/'.$name);
  //var_dump($file);
  foreach($file as $key=>$line){
    $pattern='/\d\d:\d\d:\d\d/';
    $replace='$1000';
    preg_match($pattern,$line,$match);
    if($match){
      $hour_begin=intval(substr($line,0,2));
      $min_begin=intval(substr($line,3,2));
      $sec_begin=intval(substr($line,6,2));
      $milli_begin=intval(substr($line,9,3));
      $hour_end=intval(substr($line,17,2));
      $min_end=intval(substr($line,20,2));
      $sec_end=intval(substr($line,23,2));
      $milli_end=intval(substr($line,26,3));

      $total_begin=$milli_begin+$sec_begin*1000+$min_begin*60000+$hour_begin*60*60000+$decalage;
      $sec_begin=($total_begin/1000)%60;
      $min_begin=($total_begin/(1000*60))%60;
      $hour_begin=($total_begin/(1000*60*60))%24;
      $milli_begin=$total_begin%1000;

      $total_end=$milli_end+$sec_end*1000+$min_end*60000+$hour_end*60*60000+$decalage;
      $sec_end=($total_end/1000)%60;
      $min_end=($total_end/(1000*60))%60;
      $hour_end=($total_end/(1000*60*60))%24;
      $milli_end=$total_end%1000;

      // if($milli_begin%1000>0){
      //   $milli_begin=$milli_begin%1000;
      //   $sec_begin=$sec_begin+(intval($milli_begin/1000));
      //   if($sec_begin%60>0){
      //     $sec_begin=$sec_begin%60;
      //     $min_begin=$min_begin+(intval($sec_begin/60));
      //     if($min_begin%60>0){
      //       $min_begin=$min_begin%60;
      //       $hour_begin=$hour_begin+(intval($min_begin/60));
      //     }
      //   }
      // }
      // if($milli_end%1000>0){
      //   $milli_begin=$milli_begin%1000;
      //   $sec_end++;
      //   if($sec_end%60>0){
      //     $sec_end=$sec_end%60;
      //     $min_end++;
      //     if($min_end%60>0){
      //       $min_end=$min_end%60;
      //       $hour_end++;
      //     }
      //   }
      // }
      $line=$hour_begin.':'.$min_begin.':'.$sec_begin.','.$milli_begin.'-->'.$hour_end.':'.$min_end.':'.$sec_end.','.$milli_end;
      $file[$key]=$line;
    }

  }

  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
      <h1>Un formulaire pour décaler les sous-titres</h1>
      <?php if(isset($file)):?>
      <?php foreach($file as $line):?>
        <p>
          <?php echo $line; ?>
        </p>
      <?php endforeach;?>
      <?php endif;?>
      <form  action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
        <p class="">
          <label for="fichier">Votre fichier srt</label>
          <input type="file"  id="fichier" name="file" value="">
        </p>
        <p class="">
          <label for="decal">Décalage en milliseconde</label>
          <input type="text" id="decal" name="decal" value="">
        </p>

        <input type="submit">
      </form>
  </body>
</html>
