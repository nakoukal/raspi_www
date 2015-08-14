<?
$time=date('H:i');
$on_from='05:00';
$on_to='11:00';
if($time>=$on_from && $time<=$on_to)
{
  echo 1;
}
else echo 0;
?>
