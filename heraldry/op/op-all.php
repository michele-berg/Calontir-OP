<?php

$file = fopen("op-template.html", "r");

if (!$file) 
{
  echo "<P>Template not available.</P>\n";
  exit;
}
else
{
  while (!feof($file) ) 
  {
    $line = fgets($file, 1024);
    if ( strstr($line, "insert content here") )
    {
      break;
    }
    else
    {
      echo "$line";
    }
  }
}

include("dbLogin.inc");
dbLogin();

$awardRS = mysql_query("SELECT AwardDate FROM Awards ORDER BY AwardDate DESC");
$numAward = mysql_num_rows($awardRS);

$lastDate = mysql_result($awardRS,0,"AwardDate");
echo "<H2>Calontir Roll of Awards<SPAN STYLE=\"font-size: 12pt; font-weight: bold;\"> - Current as of $lastDate</SPAN></H2>\n";

$countRS = mysql_query("SELECT Count(PopInd) as recCount FROM Awards");
$numCount = mysql_num_rows($countRS);
$awardCount = mysql_result($countRS,0,"recCount");

$countRS = mysql_query("SELECT Count(PopInd) as recCount FROM Populace");
$numCount = mysql_num_rows($countRS);
$popCount = mysql_result($countRS,0,"recCount");
echo "<H3>Retrieving $awardCount awards for $popCount people, please be patient ...</H3>\n";

echo "<TABLE CELLPADDING=2 CELLSPACING=1 BORDER=1 bordercolordark=#9933CC bordercolorlight=#CC66FF>\n";
echo "<TR><TD><A HREF=\"index.html\">OP Home</A></TD></TR>\n";
echo "<TR><TD COLSPAN=2><HR></TD></TR>\n";

$kListRS = mysql_query("SELECT * FROM Kingdoms ORDER BY KingdomInd");
$numKList = mysql_num_rows($kListRS);

$popRS = mysql_query("SELECT * FROM Populace ORDER BY Name");
$numPop = mysql_num_rows($popRS);
$i = 0;

while($i < $numPop)
{
  $name =  mysql_result($popRS,$i,"Name");
  $altNames = mysql_result($popRS,$i,"AltNames");
  $popInd = mysql_result($popRS,$i,"PopInd");

  echo "<TR>\n";
  echo "<TD WIDTH=600>\n";
  if ( $altNames == "" )
  {
    echo "<SPAN STYLE=\"font-weight: bold;\">$name</SPAN>\n";
  }
  else
  {
    echo "<SPAN STYLE=\"font-weight: bold;\">$name</SPAN><BR>    $altNames\n";
  }
  echo "<HR>\n";

  $awardRS = mysql_query("SELECT * FROM Awards WHERE PopInd =' $popInd ' ORDER BY AwardDate");
  $numAward = mysql_num_rows($awardRS);

  $j = 0;

  echo "<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0>";
  while($j < $numAward)
  {
    $ac = mysql_result($awardRS,$j,"awardCode");
    $awardDate = mysql_result($awardRS,$j,"awardDate");
    $aListRS = mysql_query("SELECT * FROM AwardList WHERE AwardInd = ' $ac '");
    $numAList = mysql_num_rows($aListRS);
    $awardName = mysql_result($aListRS,0,"Name");
    $kc = mysql_result($awardRS,$j,"KingdomCode");
    if ( $kc != 0 )
    {
      $kc = $kc - 1;
      $Kingdom = mysql_result($kListRS,$kc,"Name");
      echo "<TR><TD>$awardName</TD><TD> </TD><TD>$awardDate</TD><TD> </TD><TD>$Kingdom</TD></TR>\n";
    }
    else
    {
      echo "<TR><TD>$awardName</TD><TD> </TD><TD>$awardDate</TD></TR>\n";
    }
    $j++;
  }
  echo "</TABLE></TD>\n";
  echo "</TR>\n";
  echo "<TR><TD HEIGHT=4 BGCOLOR=gold></TD></TR>\n";
  $i++;
}
print("<TR><TD ALIGN=CENTER COLSPAN=4 BGCOLOR=GOLD><A HREF=\"submitupdate.html\">OP Correction Submision Form</A></TD></TR>\n");
echo "</TABLE>\n";

while (!feof($file) )
{
  $line = fgets($file, 1024);
  echo "$line";
}

fclose($file);
?>

