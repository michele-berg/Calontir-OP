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

$countRS = mysql_query("SELECT Count(PopInd) as recCount FROM Populace");
$numCount = mysql_num_rows($countRS);
$popCount = mysql_result($countRS,0,"recCount");
$pages = 100;
$ppp = floor($popCount/$pages) + 1;

print("<TABLE iCELLPADDING=2 CELLSPACING=1 BORDER=1 bordercolordark=#9933CC bordercolorlight=#CC66FF>\n");
print("<TR>\n");
print("<TD ALIGN=top>\n");

print("<TABLE>\n");
print("<TR><TD>Page</TD></TR>\n");
for ($i = 1; $i <= $pages; $i++)
{
  if ( $i == $page )
  {
    print ("<TR><TD STYLE=\"font-weight: bold; color: goldenrod;\">$i</TD></TR>\n");
  }
  else
  {
    print ("<TR><TD><A HREF=\"http://www.calontir.info/herald/op/op-page.php?page=$i\">$i</A></TD></TR>\n");
  }
}
print("</TABLE>\n");

print("</TD>\n");
print("<TD ALIGN=top>\n");

print("<TABLE>\n");
print("<TR><TD COLSPAN=2>Results for Page $page</TD></TR>\n");
$p = $page - 1;
$n = $page + 1;
print("<TR><TD><A HREF=\"http://www.calontir.info/herald/op/op-page.php?page=$p\">Previous</A></TD>\n");
print("<TD><A HREF=\"http://www.calontir.info/herald/op/op-page.php?page=$n\">Next</A></TD></TD></TR>\n");

print("<TR><TR>&nbsp;</TD></TR>\n");

print("<TR><TD><A HREF=\"http://www.calontir.info/herald/op/op-page.php?page=$p\">Previous</A></TD>\n");
print("<TD><A HREF=\"http://www.calontir.info/herald/op/op-page.php?page=$n\">Next</A></TD></TD></TR>\n");

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
