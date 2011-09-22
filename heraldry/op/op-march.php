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
      if ($line == "</HEAD>\n")
      {
        print ("<SCRIPT Language=\"Javascript1.2\">\n");
        print ("<!--\n");
        print ("function mysubmit(form) {\n");
#        print ("           document.myform.submit();\n");
#        print ("           document.myform.search.value = document.myform.passName.value;\n");
#        print ("           alert(\"Radio Button Clicked \" + document.myform.order.value);\n");
        print ("         }\n");
        print ("-->\n");
        print ("</SCRIPT>\n");
      }
      echo "$line";
    }
  }
}

$search = ereg_replace("_", " ", $search);

include("dbLogin.inc");
dbLogin();

$awardRS = mysql_query("SELECT AwardDate FROM Awards ORDER BY AwardDate DESC");
$numAward = mysql_num_rows($awardRS);

$lastDate = mysql_result($awardRS,0,"AwardDate");
echo "<H2>Calontir Precedence List<SPAN STYLE=\"font-size: 12pt; font-weight: bold;\"> - Current as of $lastDate</SPAN></H2>\n";

print("<TABLE CELLPADDING=2 CELLSPACING=1 BORDER=1 bordercolordark=\"#9933CC\" bordercolorlight=\"#CC66FF\">\n");
print("  <TR>\n");
print("    <TD VALIGN=top>\n");

print("      <FORM NAME=\"myform\">\n");

print("        <TABLE CELLPADDING=2 CELLSPACING=1 BORDER=0>\n");
print("          <TR><TD><A HREF=\"index.html\">OP Home</A></TD></TR>\n");
print("          <TR><TD COLSPAN=2><HR></TD></TR>\n");
print("          <TR>\n");
print("            <TD COLSPAN=2>\n");
print("              Specify search text:\n");
print("            </TD>\n");
print("          </TR>\n");
print("          <TR>\n");
print("            <TD COLSPAN=2>\n");
print("              <INPUT TYPE=text SIZE=40 NAME=search VALUE='$search'>\n");
print("            </TD>\n");
print("          </TR>\n");
print("          <TR>\n");
print("            <TD>\n");
if ( $matchCase == "on" )
{
  print("              Match Case <INPUT TYPE='checkbox' CHECKED NAME='matchCase'>\n");
}
else
{
  print("              Match Case <INPUT TYPE='checkbox' NAME='matchCase'>\n");
}
print("            </TD>\n");
print("            <TD>\n");
if ( $startName == "on" )
{
  print("              Start of Name <INPUT TYPE='checkbox' CHECKED NAME='startName'>\n");
}
else
{
  print("              Start of Name <INPUT TYPE='checkbox' NAME='startName'>\n");
}
print("            </TD>\n");
print("          </TR>\n");
print("          <TR>\n");
print("            <TD>\n");
print("              <INPUT TYPE='submit' VALUE='search' NAME='submit'>\n");
print("            </TD>\n");
print("            <TD>\n");
print("              <INPUT TYPE='submit' VALUE='clear' NAME='submit'>\n");
print("            </TD>\n");
print("          </TR>\n");
print("        </TABLE>\n");

print("    </TD>\n");
print("    <TD VALIGN=top>\n");
print("      <TABLE>\n");

if ( $passName != "")
{
  $passName = ereg_replace("_", " ", $passName);
  $search = $passName;
#  document.myform.search.value = $passName;
}

if ( $search == "" )
{
  print("        <TR><TD COLSPAN=2>Please specify search text.</TD></TR>\n");
}
else
{
  print("         <TR><TD COLSPAN=2>Search results for <SPAN STYLE=\"font-weight: bold;\">$search</SPAN>.</TD><TD><INPUT TYPE=\"submit\" VALUE=\"Add to List\" NAME=\"submit\"></TD></TR>\n");
  echo "        <TR>\n";
  echo "          <TD COLSPAN=4>\n";
  echo "            <TABLE CELLPADDING=2 CELLSPACING=1 BORDER=1 bordercolordark=\"#9933CC\" bordercolorlight=\"#CC66FF\">\n";

  $kListRS = mysql_query("SELECT * FROM Kingdoms ORDER BY KingdomInd");
  $numKList = mysql_num_rows($kListRS);

  if ( $matchCase == "on" )
  {
    if ( $startName == "on" )
    {
      $whereClause = "Name REGEXP BINARY \"^$search\"";
    }
    else
    {
      $whereClause = "Name REGEXP BINARY \"$search\"";
    }
  }
  else
  {
    if ( $startName == "on" )
    {
      $whereClause = "Name REGEXP \"^$search\"";
    }
    else
    {
      $whereClause = "Name REGEXP \"$search\"";
    }
  }

  $countRS = mysql_query("SELECT count(PopInd) as recCount FROM Populace WHERE $whereClause ORDER BY Name");
  $numPop = mysql_num_rows($countRS);
  $recCount = mysql_result($countRS,0,"recCount");

  if ( $recCount == 0 )
  {
    echo "              <TR><TD>No resutls returned, please broaden your search.</TD></TR>\n";
  }
  else
  {
    $popRS = mysql_query("SELECT * FROM Populace WHERE $whereClause ORDER BY Name");
    $numPop = mysql_num_rows($popRS);
    $i = 0;

    while($i < $numPop)
    {
      $ind = mysql_result($popRS,$i,"popInd");
      $popName =  mysql_result($popRS,$i,"Name");
      $altNames = mysql_result($popRS,$i,"AltNames");
      $popInd = mysql_result($popRS,$i,"PopInd");

      echo "              <TR>\n";
      echo "                <TD WIDTH=600>\n";
      $linkName = ereg_replace(" ", "_", $popName);
      if ( $altNames == "" )
      {
#        echo "                  <SPAN STYLE=\"font-weight: bold;\">$popName</SPAN> ($ind)\n";
        echo "                  <SPAN STYLE=\"font-weight: bold;\"><A HREF=\"op-name.php?search=$linkName\">$popName</A></SPAN>\n";
      }
      else
      {
#        echo "                  <SPAN STYLE=\"font-weight: bold;\">$popName</SPAN> ($ind)<BR>&nbsp;&nbsp;&nbsp;&nbsp;$altNames\n";
        echo "                  <SPAN STYLE=\"font-weight: bold;\"><A HREF=\"op-name.php?search=$linkName\">$popName</A></SPAN><BR>&nbsp;&nbsp;&nbsp;&nbsp;$altNames\n";
      }
      print ("                  <BR><INPUT TYPE=\"hidden\" NAME=\"PerIndex\" VALUE=\"$ind\">\n");

      if ( $recCount == 1 )
      {
        echo "                  <HR>\n";
        $awardRS = mysql_query("SELECT Awards.PopInd, Awards.AwardCode, Awards.AwardDate, Awards.KingdomCode, AwardList.Precedence FROM Awards, AwardList WHERE Awards.PopInd =' $popInd ' and AwardList.AwardInd = Awards.AwardCode ORDER BY AwardList.Precedence, Awards.AwardDate"); 
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
            echo "                    <TR><TD>$awardName</TD><TD>&nbsp;</TD><TD>$awardDate</TD><TD>&nbsp;</TD><TD>$Kingdom</TD></TR>\n";
          }
          else
          {
            echo "                    <TR><TD>$awardName</TD><TD>&nbsp;</TD><TD>$awardDate</TD></TR>\n";
          }
          $j++;
        }
        echo "                   </TABLE>\n";
      }
      echo "                </TD>\n";
      echo "              </TR>\n";
      $i++;
    }
  }
  print("            </TABLE>\n");
  print("          </TD>\n");
  print("        </TR>\n");
}
echo "      </TABLE>\n";
print("      </FORM>\n");

print("    </TD>\n");
print("  </TR>\n");
print("</TABLE>\n");

# print("</TD>\n");
# print("</TR>\n");
# print("</TABLE>\n");

while (!feof($file) )
{
  $line = fgets($file, 1024);
  echo "$line";
}

fclose($file);
?>
