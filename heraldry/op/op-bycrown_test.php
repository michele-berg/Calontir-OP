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

print("<TABLE CELLPADDING=2 CELLSPACING=1 BORDER=1 bordercolordark=#9933CC bordercolorlight=#CC66FF>\n");
print("<TR>\n");
print("<TD VALIGN=top>\n");

print("<FORM METHOD=post ACTION='op-bycrown_test.php'>\n");

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
print("<SELECT NAME=choice>\n");


$crownList = mysql_query("SELECT ID, CONCAT( LTRIM( RTRIM( Sovereign ) ) , ' and ', LTRIM( RTRIM( Consort ) ) ) AS Crown, StepUp, StepDown FROM ReigningNobles");
$numCrowns = mysql_num_rows($crownList);

$i = 0;

if ( $choice == "" )
{
  $choice = -30;
}

while($i < $numCrowns)
{
  $crownCode =  mysql_result($crownList,$i,"ID");
  $crownName =  mysql_result($crownList,$i,"Crown");
  if ($choice != $crownCode)
  {
    print("<OPTION VALUE=$crownCode>$crownCode. $crownName</OPTION>\n");
    
  }
  else
  {
     print("<OPTION VALUE=$crownCode SELECTED=SELECTED>$crownCode. $crownName</OPTION>\n");
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
print("&nbsp;\n");
print("</TD>\n");
print("</TR>\n");
print("</TABLE>\n");
print("</FORM>\n");

print("</TD>\n");
print("<TD VALIGN=top>\n");

print("<TABLE>\n");

if ( $choice == "" || $choice == -30 )
{
  print("<TR><TD COLSPAN=2>Please select the desired Crown.</TD></TR>\n");
}
else
{
    $desiredCrown = mysql_query("SELECT ID, CONCAT( LTRIM( RTRIM( Sovereign ) ) , ' and ', LTRIM( RTRIM( Consort ) ) ) AS Crown, StepUp, StepDown FROM ReigningNobles WHERE ID = $choice");
    $desiredCrownName = mysql_result($desiredCrown,0,"Crown");
    $desiredCrownStepUp = mysql_result($desiredCrown,0,"StepUp");
    $desiredCrownStepDown = mysql_result($desiredCrown,0,"StepDown");
    $crownAwardList = mysql_query("SELECT Name, Precedence, PopInd, AwardDate FROM AwardList, Awards WHERE AwardInd < 34 AND AwardList.AwardInd = Awards.AwardCode AND Awards.Crown = '$choice' ORDER BY Precedence, AwardDate");

  print("<TR><TD COLSPAN=2>Awards bestowed by <SPAN STYLE=\"font-weight: bold;\">$desiredCrownName</SPAN>, ");
  if ($desiredCrownStepDown == NULL)
  {
    print("during Their current Reign.</TD></TR>\n");
  } 
  else
  {
    print("between $desiredCrownStepUp and $desiredCrownStepDown.</TD></TR>\n");
  }
  echo "<TR>\n";
  echo "<TD COLSPAN=4>\n";
  echo "<TABLE CELLPADDING=2 CELLSPACING=1 BORDER=NONE>\n";

  $i = 0;

  while($row = mysql_fetch_array($crownAwardList, MYSQL_ASSOC))
  {
    $popID = $row["PopInd"];
    $fetchPopNames = mysql_query("SELECT Name FROM Populace WHERE PopInd = '$popID'");
    $popNames = mysql_result($fetchPopNames,0,"Name");
    $precedence = $row["Precedence"];
    $awardName = $row["Name"];
    $awardDate = $row["AwardDate"];
    
    echo "<TR>\n";
    echo "<TD colspan=3 border=none>\n";
    if ($precedence == 7 || $precedence == 8 || $precedence == 9)
    {
      print("<SPAN STYLE=\"font-weight: bold;\">ROYAL PEERS</SPAN></TD></TR>");
    }
    else if ($precedence == 10 || $precedence == 11)
    {
      print("<SPAN STYLE=\"font-weight: bold;\">PATENTS OF ARMS</SPAN></TD></TR>");
    }
    else if ($precedence == 12 || $precedence == 15 || $precedence == 16)
    {
      print("<SPAN STYLE=\"font-weight: bold;\">GRANTS OF ARMS</SPAN></TD></TR>");
    }
    else if ($precedence == 17 || $precedence == 18 || $precedence == 19)
    {
      print("<SPAN STYLE=\"font-weight: bold;\">AWARDS OF ARMS</SPAN></TD></TR>");
    }
    else
    {
      print("<SPAN STYLE=\"font-weight: bold;\">NON-ARMIGEROUS</SPAN></TD></TR>");
    }
    print("<TR><TD border=none>$awardName</TD><TD border=none><A HREF=\"op-name.php?passName=$popNames\">$popNames</A></TD><TD border=none>$awardDate</TD></TR>");   
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


