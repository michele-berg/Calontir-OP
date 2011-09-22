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

$countRS = mysql_query("SELECT Count(PopInd) as recCount FROM Populace");
$numCount = mysql_num_rows($countRS);
$popCount = mysql_result($countRS,0,"recCount");
$pages = 26;

print("<TABLE iCELLPADDING=2 CELLSPACING=1 BORDER=1 bordercolordark=#9933CC bordercolorlight=#CC66FF>\n");
print("<TR>\n");
print("<TD VALIGN=top>\n");

print("<TABLE CELLPADDING=8 CELLSPACING=0>\n");
print("<TR><TD><A HREF=\"index.html\">OP Home</A></TD></TR>\n");
print("<TR><TD COLSPAN=2><HR></TD></TR>\n");
print("<TR><TD>Page</TD></TR>\n");
for ($i = 1; $i <= $pages; $i++)
{
  $letter = chr( $i + 64);
  if ( $i == $page )
  {
    print ("<TR><TD STYLE=\"font-weight: bold; color: goldenrod;\">$letter</TD></TR>\n");
  }
  else
  {
    print ("<TR><TD><a href=\"$self?page=$i\">$letter</A></TD></TR>\n");
  }
}
print("</TABLE>\n");

print("</TD>\n");
print("<TD VALIGN=top>\n");

print("<TABLE>\n");
if ( $page >= 1 && $page <= 26 )
{
  $letter = chr($page + 64);
print("<TR><TD COLSPAN=4>Results for the letter <B>$letter</B></TD></TR>\n");
}
else
{
  $letter = " ";
  print("<TR><TD COLSPAN=4>Please Select a letter</TD></TR>\n");
}
$p = $page - 1;
if ( $p < 0 )
{
  $p = 0;
}
$n = $page + 1;
if ( $n > 26 )
{
  $n = 26;
}

print("<TR><TD><a href=\"$self?page=1\">First</A></TD>\n");
print("<TD><a href=\"$self?page=$p\">Previous</A></TD>\n");
print("<TD><a href=\"$self?page=$n\">Next</A></TD>\n");
print("<TD><a href=\"$self?page=$pages\">Last</A></TD></TR>\n");


echo "        <TR>\n";
echo "          <TD COLSPAN=4>\n";
echo "            <TABLE CELLPADDING=2 CELLSPACING=1 BORDER=1 bordercolordark=#9933CC bordercolorlight=#CC66FF>\n";

$kListRS = mysql_query("SELECT * FROM Kingdoms ORDER BY KingdomInd");
$numKList = mysql_num_rows($kListRS);

$start = ( $page - 1 ) * $ppp;
$popRS = mysql_query("SELECT * FROM Populace WHERE Name LIKE '$letter%' ORDER BY Name");
$numPop = mysql_num_rows($popRS);
$i = 0;

while($i < $numPop)
{
  $ind = mysql_result($popRS,$i,"PopInd");
  $name =  mysql_result($popRS,$i,"Name");
  $altNames = mysql_result($popRS,$i,"AltNames");
  $PopInd = mysql_result($popRS,$i,"PopInd");

  echo "              <TR>\n";
  echo "                <TD WIDTH=600>\n";
  if ( $altNames == "" )
  {
#    echo "                  <SPAN STYLE=\"font-weight: bold;\">$name</SPAN> ($ind)\n";
    echo "                  <SPAN STYLE=\"font-weight: bold;\">$name</SPAN>\n";
  }
  else
  {
#    echo "                  <SPAN STYLE=\"font-weight: bold;\">$name</SPAN> ($ind)<BR>    $altNames\n";
    echo "                  <SPAN STYLE=\"font-weight: bold;\">$name</SPAN><BR>    $altNames\n";
  }
  echo "                  <HR>\n";

  $awardRS = mysql_query("SELECT * FROM Awards WHERE PopInd =' $PopInd ' ORDER BY AwardDate");
  $numAward = mysql_num_rows($awardRS);

  $j = 0;

  echo "                  <TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0>";

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
      echo "                    <TR><TD>$awardName</TD><TD> </TD><TD>$awardDate</TD><TD> </TD><TD>$Kingdom</TD></TR>\n";
    }
    else
    {
      echo "                    <TR><TD>$awardName</TD><TD> </TD><TD>$awardDate</TD></TR>\n";
    }
    $j++;
  }
  echo "                  </TABLE>\n";
  echo "                </TD>\n";
  echo "              </TR>\n";
  echo "              <TR><TD HEIGHT=4 BGCOLOR=gold></TD></TR>\n";
  $i++;
}

echo "            </TABLE>\n";

print("          </TD>\n");
print("        </TR>\n");

print("<TR><TD><a href=\"$self?page=1\">First</A></TD>\n");
print("<TD><a href=\"$self?page=$p\">Previous</A></TD>\n");
print("<TD><a href=\"$self?page=$n\">Next</A></TD>\n");
print("<TD><a href=\"$self?page=$pages\">Last</A></TD></TR>\n");
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


