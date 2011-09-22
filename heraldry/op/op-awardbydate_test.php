<?php

if ( $submit == "clear" )
{
  $search = "";
  $matchCase = "";
  $startName = "";
  $passName = "";
}

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

print("<TABLE CELLPADDING=2 CELLSPACING=1 BORDER=1 bordercolordark=#9933CC bordercolorlight=#CC66FF>\n");
print("<TR>\n");
print("<TD VALIGN=top>\n");

print("<FORM METHOD=post ACTION='op-awardbydate_test.php'>\n");

print("<TABLE CELLPADDING=2 CELLSPACING=1 BORDER=0>\n");
print("<TR><TD><A HREF=\"index.html\">OP Home</A></TD></TR>\n");
print("<TR><TD COLSPAN=2><HR></TD></TR>\n");
print("<TR>\n");
print("<TD COLSPAN=2>\n");
print("Specify award:\n");
print("</TD>\n");
print("</TR>\n");
print("<TR>\n");
print("<TD COLSPAN=2>\n");
print("<SELECT NAME=award>\n");


$aListRS = mysql_query("SELECT AwardInd, Name FROM AwardList ORDER BY AwardInd");
$numAward = mysql_num_rows($aListRS);
$awardName = mysql_result($aListRS,0,"Name");

$i = 0;


if ( $award == "" )
{
  $award = -30;
}

while($i < $numAward)
{
  print("current = $i, looking for $award<BR>\n");
  $awardCode =  mysql_result($aListRS,$i,"awardInd");
  $awardName =  mysql_result($aListRS,$i,"Name");
  if ( $awardCode <= 100 )
  { 
    if ( $i == abs($award)  - 1 )
    {
      print("<OPTION SELECTED VALUE=$awardCode>$awardName</OPTION>\n");
    }
    else
    {
      print("<OPTION VALUE=$awardCode>$awardName</OPTION>\n");
    }
  }
  else
  {
    if ( $award == 100 )
    {
      print("<OPTION SELECTED VALUE=100>All Others</OPTION>\n");
    }
    else
    {
      print("<OPTION VALUE=100>All Others</OPTION>\n");
    }
    break;
  }
  $i++;
}
print("</SELECT>\n");
print("</TD>\n");
print("</TR>\n");
print("<TR>\n");
print("<TD>\n");
print("<INPUT TYPE='submit' VALUE='select' NAME='submit'>\n");
print("</TD>\n");
print("<TD>\n");
print("<INPUT TYPE='submit' VALUE='clear' NAME='submit'>\n");
print("</TD>\n");
print("</TR>\n");
print("</TABLE>\n");
print("</FORM>\n");

print("</TD>\n");
print("<TD VALIGN=top>\n");

print("<TABLE>\n");

if ( $award == "" || $award == -30 )
{
  print("<TR><TD COLSPAN=2>Please select an award or order.</TD></TR>\n");
}
else
{
  if ( $award != 100 )
  {
    $anameRS = mysql_query("SELECT Name FROM AwardList WHERE AwardInd ='$award'");
    $numAward = mysql_num_rows($anameRS);
    $awardName = mysql_result($anameRS,0,"Name");
    $countRS = mysql_query("SELECT count(PopInd) as recCount FROM Awards WHERE AwardCode ='$award'");
    $awardRS = mysql_query("SELECT * FROM Awards WHERE AwardCode ='$award' ORDER BY AwardDate");
  }
  else
  {
    $awardName = "All non Calontir Awards";
    $countRS = mysql_query("SELECT count(PopInd) as recCount FROM Awards WHERE AwardCode >='$award'");
    $kListRS = mysql_query("SELECT * FROM Kingdoms ORDER BY KingdomInd");
    $numKList = mysql_num_rows($kListRS);
    $awardRS = mysql_query("SELECT * FROM Awards WHERE AwardCode >='$award' ORDER BY AwardCode, AwardDate");
  }

  $numCount = mysql_num_rows($countRS);
  $aCount = mysql_result($countRS,0,"recCount");

  print("<TR><TD COLSPAN=2>Roll of the <SPAN STYLE=\"font-weight: bold;\">$awardName</SPAN>, $aCount bestowed.</TD></TR>\n");
  echo "<TR>\n";
  echo "<TD COLSPAN=4>\n";
  echo "<TABLE CELLPADDING=2 CELLSPACING=1 BORDER=1 bordercolordark=#9933CC bordercolorlight=#CC66FF>\n";

  $numAward = mysql_num_rows($awardRS);

  $i = 0;

  while($i < $numAward)
  {
    echo "<TR>\n";
    echo "<TD>\n";
    $ac =  mysql_result($awardRS,$i,"AwardCode");
    $popInd =  mysql_result($awardRS,$i,"popInd");
    $awardDate =  mysql_result($awardRS,$i,"awardDate");
    echo "<TABLE WIDTH=560 CELLPADDING=2 CELLSPACING=0 BORDER=0>\n";
    echo "<TR>\n";
    $ind = $i + 1;
    if ( $award != 100 )
    {
      echo "<TD WIDTH=40><SPAN STYLE=\"font-weight: normal;\">$ind</SPAN></TD>\n";
    }
    else
    {
      $kc =  mysql_result($awardRS,$i,"kingdomCode");
      $kc = $kc - 1;
      $anameRS = mysql_query("SELECT Name FROM AwardList WHERE AwardInd = '$ac'");
      $numAName = mysql_num_rows($anameRS);
      $awardName = mysql_result($anameRS,0,"Name");
      echo "<TD WIDTH=120><SPAN STYLE=\"font-weight: normal;\">$awardName</SPAN></TD>\n";
      $Kingdom = mysql_result($kListRS,$kc,"Name");
      echo "<TD WIDTH=100><SPAN STYLE=\"font-weight: normal;\">$Kingdom</SPAN></TD>\n";
    }
    echo "<TD WIDTH=90>$awardDate</TD>\n";

    $popRS = mysql_query("SELECT Name FROM Populace WHERE PopInd ='$popInd'");
    $numPop = mysql_num_rows($popRS);
    $popName =  mysql_result($popRS,0,"Name");
    
    $popReason = mysql_result($awardRS,$i,"Reason");

    echo "<TD WIDTH=250><SPAN STYLE=\"font-weight: bold;\"><A HREF=\"op-name.php?passName=$popName\">$popName</SPAN>\n";

	echo "<TD WIDTH=250>$popReason</TD>\n";

    echo "</TR>\n";
    echo "</TABLE>\n";
    echo "</TD>\n";
    echo "</TR>\n";
    echo "<TR><TD HEIGHT=4 BGCOLOR=gold></TD></TR>\n";
    $i++;
  }


  echo "</TABLE>\n";
}

print("</TD>\n");
print("</TR>\n");
print("<TR><TD ALIGN=CENTER COLSPAN=4 BGCOLOR=GOLD><A HREF=\"submitupdate.html\">OP Correction Submission Form</A></TD></TR>\n");
print("</TABLE>\n");

print("</TD>\n");
print("</TR>\n");
print("</TABLE>\n");

while (!feof($file) )
{
  $line = fgets($file, 1024);
  echo "$line";
}

fclose($file);
?>


