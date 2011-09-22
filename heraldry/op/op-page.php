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

$qryStr = "SELECT AwardDate FROM Awards ORDER BY AwardDate DESC";
$awardRS = mysql_query($qryStr);
$numAward = mysql_num_rows($awardRS);

$lastDate = mysql_result($awardRS,0,"AwardDate");
echo "<H2>Calontir Roll of Awards<SPAN STYLE=\"font-size: 12pt; font-weight: bold;\"> - Current as of $lastDate</SPAN></H2>\n";

$countRS = mysql_query("SELECT Count(PopInd) as recCount FROM Populace");
$numCount = mysql_num_rows($countRS);
$popCount = mysql_result($countRS,0,"recCount");
$pages = 100;
$ppp = floor($popCount/$pages) + 1;
$pages = ceil($popCount/$ppp);

print("<TABLE CELLPADDING=2 CELLSPACING=1 BORDER=1 bordercolordark=#9933CC bordercolorlight=#CC66FF>\n");
print("  <TR>\n");
print("    <TD VALIGN=top>\n");

print("      <TABLE CELLPADDING=4 CELLSPACING=0>\n");
print("        <TR><TD><A HREF=\"index.html\">OP Home</A></TD></TR>\n");
print("        <TR><TD COLSPAN=2><HR></TD></TR>\n");
print("        <TR><TD>Page</TD></TR>\n");
for ($i = 1; $i <= $pages; $i++)
{
  $start = ( $i - 1 ) * $ppp + 1;
  $end = $start + $ppp - 1;

  $popRS = mysql_query("SELECT Name FROM Populace WHERE PopInd =' $start ' ORDER BY name");
if(!$popRS) {
  echo mysql_error();
}
  $numPop = mysql_num_rows($popRS);
  $first =  mysql_result($popRS,0,"Name");
  $first = substr($first, 0, 3);

  $popRS = mysql_query("SELECT Name FROM Populace WHERE PopInd =' $end ' ORDER BY Name");
if(!$popRS) {
  echo mysql_error();
}
  $numPop = mysql_num_rows($popRS);
  if ( $numPop > 0 )
  {
    $last = mysql_result($popRS,0,"Name");
  }
  else
  {
    $end = $popCount - 1;
    $popRS = mysql_query("SELECT Name FROM Populace WHERE PopInd =' $end '");
if(!$popRS) {
  echo mysql_error();
}
    $last = mysql_result($popRS,0,"Name");
  }
  $last = substr($last, 0, 3);

  if ( $i == $page )
  {
    print ("        <TR><TD STYLE=\"font-weight: bold; color: goldenrod;\">$i  $first - $last</TD></TR>\n");
  }
  else
  {
    print ("        <TR><TD><A HREF=\"http://www.calontir.org/heraldry/op/op-page.php?page=$i\">$i  $first - $last</A></TD></TR>\n");
  }
}
echo 'line '.__LINE__.': $page is now '.$page.'<br>';
print("        <TR><TD><A HREF=\"index.html\">OP Home</A></TD></TR>\n");
print("      </TABLE>\n");

print("    </TD>\n");
print("    <TD VALIGN=top>\n");

print("      <TABLE BORDER=0>\n");
$page = $page ? $page : 1;
$start = ( $page - 1 ) * $ppp + 1;
$end = $start + $ppp - 1;
echo 'line '.__LINE__.': $page is now '.$page.'<br>';

$popRS = mysql_query("SELECT Name FROM Populace WHERE PopInd =' $start ' ORDER BY Name");
if(!$popRS) {
  echo mysql_error();
}
$numPop = mysql_num_rows($popRS);
$first =  mysql_result($popRS,0,"Name");

$popRS = mysql_query("SELECT Name FROM Populace WHERE PopInd =' $end ' ORDER BY Name");
if(!$popRS) {
  echo mysql_error();
}
$numPop = mysql_num_rows($popRS);
if ( $numPop > 0 )
{
  $last = mysql_result($popRS,0,"Name");
}
else
{
  $end = $popCount - 1;
  $popRS = mysql_query("SELECT Name FROM Populace WHERE PopInd =' $end '");
if(!$popRS) {
  echo mysql_error();
}
  $last = mysql_result($popRS,0,"Name");
}
#print("        <TR><TD COLSPAN=4><H2>Results for Page $page <SPAN STYLE=\"font-weight: normal; font-size: 12pt;\">($start-$first to $end-$last)</SPAN)</H2></TD></TR>\n");
print("        <TR><TD COLSPAN=4><H2>Results for Page $page <SPAN STYLE=\"font-weight: normal; font-size: 12pt;\">($first to $last)</SPAN)</H2></TD></TR>\n");
$p = $page - 1;
if ( $p < 1 )
{
  $p = 1;
}
echo 'line '.__LINE__.': $page is now '.$page.'<br>';

$n = $page + 1;
if ( $n > $pages )
{
  $n = $pages;
}
echo 'line '.__LINE__.': $page is now '.$page.'<br>';

print("        <TR><TD><a href=\"$self?page=1\">First</A></TD>\n");
print("            <TD><a href=\"$self?page=$p\">Previous</A></TD>\n");
print("            <TD><a href=\"$self?page=$n\">Next</A></TD>\n");
print("            <TD><a href=\"$self?page=$pages\">Last</A></TD></TR>\n");

echo "        <TR>\n";
echo "          <TD COLSPAN=4>\n";
echo "            <TABLE CELLPADDING=2 CELLSPACING=1 BORDER=1 bordercolordark=#9933CC bordercolorlight=#CC66FF>\n";

$kListRS = mysql_query("SELECT * FROM Kingdoms ORDER BY KingdomInd");
if(!$kListRS) {
  echo mysql_error();
}
$numKList = mysql_num_rows($kListRS);

$start = ( $page - 1 ) * $ppp + 1;
$popRS = mysql_query("SELECT * FROM Populace ORDER BY Name LIMIT $start, $ppp");
if(!$popRS) {
  echo mysql_error();
}
  echo "Query: " . $popRS;
$numPop = mysql_num_rows($popRS);
$i = 0;
echo "SELECT * FROM Populace ORDER BY Name LIMIT $start, $ppp";
echo 'line '.__LINE__.': $page is now '.$page.'<br>';

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
if(!$awardRS) {
  echo mysql_error();
}
  $numAward = mysql_num_rows($awardRS);

  $j = 0;

  echo "                  <TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0>";
  while($j < $numAward)
  {
    $ac = mysql_result($awardRS,$j,"awardCode");
    $awardDate = mysql_result($awardRS,$j,"awardDate");
    $aListRS = mysql_query("SELECT * FROM AwardList WHERE AwardInd = ' $ac '");
if(!$aListRS) {
  echo mysql_error();
}
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
print("        <TR><TD><a href=\"$self?page=1\">First</A></TD>\n");
print("            <TD><a href=\"$self?page=$p\">Previous</A></TD>\n");
print("            <TD><a href=\"$self?page=$n\">Next</A></TD>\n");
print("            <TD><a href=\"$self?page=$pages\">Last</A></TD></TR>\n");
print("        <TR><TD ALIGN=CENTER COLSPAN=4 BGCOLOR=GOLD><A HREF=\"submitupdate.html\">OP Correction Submission Form</A></TD></TR>\n");

print("      </TABLE>\n");

print("    </TD>\n");
print("  </TR>\n");
print("</TABLE>\n");

while (!feof($file) )
{
  $line = fgets($file, 1024);
  echo "$line";
}

fclose($file);
?>



