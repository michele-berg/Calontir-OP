<?php
$eSite = "www.calontir.org/heraldry/op/update/";
$sPage = "update-main.php";
session_start();
include("dbLogin.inc");
dbLogin();
if ($userid && $password);
{  $qryStr = "SELECT UsrKey FROM Users where Name = '$userid' and Password = '$password'";  $userRS = mysql_query($qryStr);  $numUsers = mysql_numrows($userRS);   if ($numUsers > 0 )  
{     $valid_user="$userid";     session_register("valid_user");  }}$date = sprintf("%04d-%02d-%02d", $year, $mon+1, $day);function dateSelect()
{  $monName = array( "1, Jan.", "2, Feb.", "3, Mar.", "4, Apr.", "5, May", "6, June", "7, July", "8, Aug.", "9, Sep.", "10, Oct.", "11, Nov.", "12, Dec." );  print("Date: <select name='mon'>\n");  for ($i=0;$i<12;$i++)  
{    print("<option value='$i'>$monName[$i]\n");  }  print("</select>\n");  print(" <select name='day'>\n");  for ($i=1;$i<32;$i++)  {    print("<option value='$i'>$i\n");  }  
print("</select>\n");  print(" <select name='year'>\n");  $curYear = date("Y");  for ($i=1980;$i<=$curYear;$i++)  {    if ($i == $curYear)    
{      print("<option selected value='$i'>$i\n");    }    else    
{      print("<option value='$i'>$i\n");    }  }  print("</select>\n");}function OPUpdateMenu($sPage, $eSite)
{   print("<ul>\n");   print("<li><a href='http://$eSite/$sPage?action=addper'>Add Person</a>\n");   
print("<li><a href='http://$eSite/$sPage?action=cornam'>Correct a Name</a>\n");   print("<li><a href='http://$eSite/$sPage?action=chanam'>Change a Name</a>\n");   
print("<li><a href='http://$eSite/$sPage?action=recawd'>Record a Bestowed Award</a>\n");   #print("<li><a href='http://$eSite/$sPage?action=corawd'>Correct a Given Award</a>\n");   
#print("<li><a href='http://$eSite/$sPage?action=delawd'>Delete a Given Award</a>\n");   
#print("<li><a href='http://$eSite/$sPage?action=addawd'>Add an award to the the award list</a>\n");   
print("<li><a href='http://$eSite/$sPage?action=logout'>Logout</a><br>\n");   print("</ul>\n");}function AddPerson($sPage, $eSite, $addPer, $name ){  print("<p>\n");  if ( $addPer == "submit" )  
{    print("Ading name to OP...<BR>\n");    
$actStr = "INSERT into Populace (Name) VALUES('$name')"; #    print("action = $actStr<BR>\n");    
$result = mysql_query($actStr);#    print("Result = $result<BR>\n");   print("Name Added<BR>\n");    
print("<HR>\n");  }  print("</p>\n");  print("<p>\n");  print("<form method='post' action='$sPage?action=addper'>\n");  print("<table>\n");  
print("<tr>\n<td colspan='2'><b>Enter name to add to populace list.</b></td>\n</tr>\n");  print("<tr>\n<td>Name:</td>\n<td><input type='text' size='30' maxlen='50' name='name'>\n</td>\n</tr>\n");  
print("<tr>\n<td colspan='2' align='left'><input type='submit' value='submit' name='addPer'></td>\n</tr>\n");  print("</table>\n");  print("</form>");  
print("</p>\n");}function CorName($sPage, $eSite, $corNam, $oldname, $recInd, $newname ){  print("<p>\n");  if ( $corNam == "Rename" )  {    print("Updating OP...<BR>\n");    
$actStr = "Update Populace set Name = '$newname' WHERE PopInd = '$recInd'";#    print("action = $actStr<BR>\n");    $result = mysql_query($actStr);#    print("Result = $result<BR>\n");    
print("Name Corrected.<BR>\n");  }  elseif ( $corNam == "Check" )  {    print("Looking up name...<BR>\n");    $qryStr = "SELECT PopInd, Name FROM Populace WHERE Name = '$oldname'";    
$recRS = mysql_query($qryStr);    $numNames = mysql_numrows($recRS);    if ( $numNames == 1)    
{      $recInd = mysql_result($recRS,0,"PopInd");      print ("Name '$oldname' found, enter correct name.<BR>\n");      print("<HR>\n");      print("<p>\n");      
print("<form method='post' action='$sPage?action=cornam'>\n");      print("<input type='hidden' name='recInd' value='$recInd'>\n");      print("<table>\n");      
print("<tr>\n<td colspan='2'><b>New name for $oldname</b></td>\n</tr>\n");      print("<tr>\n<td>\n");      print("<tr>\n<td>New Name:</td>\n<td><input type='text' size='30' maxlen='50' name='newname'>\n</td>\n</tr>\n");      
print("</select>\n");      print("</td>\n</tr>\n");      print("<tr>\n<td colspan='2' align='left'><input type='submit' value='Rename' name='corNam'></td>\n</tr>\n");      
print("</table>\n");      print("</form>");      print("</p>\n");    }    else    {      print ("Name '$oldname' not found, try again.<BR>\n");      print("<HR>\n");      print("<p>\n");      
print("<form method='post' action='$sPage?action=cornam'>\n");      print("<table>\n");      print("<tr>\n<td colspan='2'><b>Enter current name.</b></td>\n</tr>\n");      
print("<tr>\n<td>Name:</td>\n<td><input type='text' size='30' maxlen='50' name='oldname'>\n</td>\n</tr>\n");      print("<tr>\n<td colspan='2' align='left'><input type='submit' value='Check' name='corNam'></td>\n</tr>\n");      
print("</table>\n");      print("</form>");      print("</p>\n");    }  }  else  {    print("</p>\n");    print("<p>\n");    print("<form method='post' action='$sPage?action=cornam'>\n");    
print("<table>\n");    print("<tr>\n<td colspan='2'><b>Enter current name.</b></td>\n</tr>\n");    
print("<tr>\n<td>Name:</td>\n<td><input type='text' size='30' maxlen='50' name='oldname'>\n</td>\n</tr>\n");    print("<tr>\n<td colspan='2' align='left'><input type='submit' value='Check' name='corNam'></td>\n</tr>\n");    
print("</table>\n");    print("</form>");    
print("</p>\n");  }}function ChaName($sPage, $eSite, $chaNam, $oldname, $recInd, $newname )
{  print("<p>\n");  if ( $chaNam == "Rename" )  
{    print("Updating OP...<BR>\n");    $qryStr = "SELECT Name, AltNames FROM Populace WHERE PopInd = '$recInd'";    $recRS = mysql_query($qryStr);    
$oldname = mysql_result($recRS,0,"Name");    
$altNames = mysql_result($recRS,0,"AltNames");    if ( $altNames == NULL )    
{      $newAltNames = $oldname;    }    else    
{      $newAltNames = $altNames . ", " . $oldname;    }    $actStr = "Update Populace set Name = '$newname', AltNames ='$newAltNames' WHERE PopInd = '$recInd'";#    
print("action = $actStr<BR>\n");    $result = mysql_query($actStr);#    
print("Result = $result<BR>\n");    
print("Name Changed.<BR>\n");  }  elseif ( $chaNam == "Check" )  
{    print("Looking up name...<BR>\n");    $qryStr = "SELECT PopInd, Name FROM Populace WHERE Name = '$oldname'";    
$recRS = mysql_query($qryStr);    $numNames = mysql_numrows($recRS);    if ( $numNames == 1)    
{      $recInd = mysql_result($recRS,0,"PopInd");      print ("Name '$oldname' found, enter new name.<BR>\n");      
print("<HR>\n");      print("<p>\n");      
print("<form method='post' action='$sPage?action=chanam'>\n");      print("<input type='hidden' name='recInd' value='$recInd'>\n");      print("<table>\n");      
print("<tr>\n<td colspan='2'><b>New name for $oldname</b></td>\n</tr>\n");      
print("<tr>\n<td>\n");      print("<tr>\n<td>New Name:</td>\n<td><input type='text' size='30' maxlen='50' name='newname'>\n</td>\n</tr>\n");      
print("</select>\n");      print("</td>\n</tr>\n");      print("<tr>\n<td colspan='2' align='left'><input type='submit' value='Rename' name='chaNam'></td>\n</tr>\n");      
print("</table>\n");      print("</form>");      
print("</p>\n");    }    else    {      
print ("Name '$oldname' not found, try again.<BR>\n");      print("<HR>\n");      
print("<p>\n");      print("<form method='post' action='$sPage?action=chanam'>\n");      
print("<table>\n");      
print("<tr>\n<td colspan='2'><b>Enter current name.</b></td>\n</tr>\n");      
print("<tr>\n<td>Name:</td>\n<td><input type='text' size='30' maxlen='50' name='oldname'>\n</td>\n</tr>\n");      
print("<tr>\n<td colspan='2' align='left'><input type='submit' value='Check' name='chaNam'></td>\n</tr>\n");      
print("</table>\n");      print("</form>");      print("</p>\n");    }  }  else  {    
print("</p>\n");    
print("<p>\n");    print("<form method='post' action='$sPage?action=chanam'>\n");    
print("<table>\n");    print("<tr>\n<td colspan='2'><b>Enter current name.</b></td>\n</tr>\n");    
print("<tr>\n<td>Name:</td>\n<td><input type='text' size='30' maxlen='50' name='oldname'>\n</td>\n</tr>\n");    
print("<tr>\n<td colspan='2' align='left'><input type='submit' value='Check' name='chaNam'></td>\n</tr>\n");    
print("</table>\n");    print("</form>");    
print("</p>\n");  }}function RecAward($sPage, $eSite, $addAwd, $name, $recInd, $award, $date, $kingdom )
{  print("<p>\n");  if ( $addAwd == "Add Award" )  {    print("Updating OP...<BR>\n");    $actStr = "INSERT into Awards (PopInd,AwardCode,AwardDate,KingdomCode,Crown) VALUES('$recInd','$award','$date','$kingdom','49')";#    
print("action = $actStr<BR>\n");    
$result = mysql_query($actStr);    print("Result = $result<BR>\n");    print("Award Added<BR>\n");  }  
elseif ( $addAwd == "Check" )  {    print("Looking up name...<BR>\n");    
$qryStr = "SELECT PopInd, Name FROM Populace WHERE Name = '$name'";    $recRS = mysql_query($qryStr);    
$numNames = mysql_numrows($recRS);    if ( $numNames == 1)    {      $recInd = mysql_result($recRS,0,"PopInd");      
print ("Name '$name' found, select award and date.<BR>\n");      print("<HR>\n");      print("<p>\n");      
print("<form method='post' action='$sPage?action=recawd'>\n");      print("<input type='hidden' name='recInd' value='$recInd'>\n");      
print("<table>\n");      print("<tr>\n<td colspan='2'><b>Enter award and date for $name.</b></td>\n</tr>\n");      
print("<tr>\n<td>\n");      print("Award: <select name='award'>\n");      $qryStr = "SELECT AwardInd, Name FROM AwardList ORDER BY AwardInd";      
$awdRS = mysql_query($qryStr);      $numAwards = mysql_numrows($awdRS);      for ($i=0;$i<$numAwards;$i++)      
{        $awdInd = mysql_result($awdRS,$i,"AwardInd");        $awdNam = mysql_result($awdRS,$i,"Name");        
print("<OPTION VALUE='$awdInd'> $awdNam\n");      }      print("</select>\n");      print("</td>\n");      
print("<td>\n");      dateSelect();      print("</td>\n");      print("<td>\n");      
print("Kingdom: <select name='kingdom'>\n");      $qryStr = "SELECT KingdomInd, Name FROM Kingdoms ORDER BY KingdomInd";      
$kinRS = mysql_query($qryStr);      $numKin = mysql_numrows($kinRS);      for ($i=0;$i<$numKin;$i++)      
{        $kinInd = mysql_result($kinRS,$i,"KingdomInd");        $kinNam = mysql_result($kinRS,$i,"Name");        
if ( $kinNam == "Calontir")        {          print("<OPTION SELECTED VALUE='$kinInd'> $kinNam\n");        }        
else        {          print("<OPTION VALUE='$kinInd'> $kinNam\n");        }      }      
print("</select>\n");      print("</td>\n");      print("</td>\n</tr>\n");      
print("<tr>\n<td colspan='2' align='left'><input type='submit' value='Add Award' name='addAwd'></td>\n</tr>\n");      
print("</table>\n");      
print("</form>");      print("</p>\n");    }    else    {      print ("Name '$name' not found, try again.<BR>\n");      
print("<HR>\n");      print("<p>\n");      print("<form method='post' action='$sPage?action=recawd'>\n");      
print("<table>\n");      print("<tr>\n<td colspan='2'><b>Enter recipient name.</b></td>\n</tr>\n");      
print("<tr>\n<td>Name:</td>\n<td><input type='text' size='30' maxlen='50' name='name'>\n</td>\n</tr>\n");      
print("<tr>\n<td colspan='2' align='left'><input type='submit' value='Check' name='addAwd'></td>\n</tr>\n");      
print("</table>\n");      print("</form>");      print("</p>\n");    }  }  else  {    print("</p>\n");    
print("<p>\n");    print("<form method='post' action='$sPage?action=recawd'>\n");    print("<table>\n");    
print("<tr>\n<td colspan='2'><b>Enter recipient name.</b></td>\n</tr>\n");    
print("<tr>\n<td>Name:</td>\n<td><input type='text' size='30' maxlen='50' name='name'>\n</td>\n</tr>\n");    
print("<tr>\n<td colspan='2' align='left'><input type='submit' value='Check' name='addAwd'></td>\n</tr>\n");    
print("</table>\n");    print("</form>");    
print("</p>\n");  }}function CorAward($sPage, $eSite, $corAwd, $name, $recInd, $award, $date, $kingdom ){  
print("<p>\n");  if ( $corAwd== "Add Award" )  {    print("Updating OP...<BR>\n");    if ( $kingdom == 10 )    
{      $kingdom = 0;    }    $actStr = "INSERT into Awards (PopInd,AwardCode,AwardDate,KingdomCode,Crown) VALUES('$recInd','$award','$date','$kingdom','38')";#    print("action = $actStr<BR>\n");    $result = mysql_query($actStr);#    
print("Result = $result<BR>\n");    print("Award Added<BR>\n");  }  elseif ( $corAwd== "Check" )  
{    print("Looking up name...<BR>\n");    $qryStr = "SELECT PopInd, Name FROM Populace WHERE Name = '$name'";    
$recRS = mysql_query($qryStr);    $numNames = mysql_numrows($recRS);    if ( $numNames == 1)    
{      $recInd = mysql_result($recRS,0,"PopInd");      print ("Name '$name' found, select award and date.<BR>\n");      
print("<HR>\n");      print("<p>\n");      print("<form method='post' action='$sPage?action=corawd'>\n");      
print("<input type='hidden' name='recInd' value='$recInd'>\n");      print("<table>\n");      
print("<tr>\n<td colspan='2'><b>Enter award and date for $name.</b></td>\n</tr>\n");      
print("<tr>\n<td>\n");      print("Award: <select name='award'>\n");      
$qryStr = "SELECT Awards.AwardCode, AwardList.Name FROM `Awards` INNER JOIN AwardList ON Awards.AwardCode = AwardList.AwardInd WHERE Awards.PopInd = $recInd Order By AwardList.Precedence";      $awdRS = mysql_query($qryStr);      $numAwards = mysql_numrows($awdRS);      for ($i=0;$i<$numAwards;$i++)      {        $awdInd = mysql_result($awdRS,$i,"AwardCode");        $awdNam = mysql_result($awdRS,$i,"Name");        print("<OPTION VALUE='$awdInd'> $awdNam\n");      }      print("</select>\n");      print("</td>\n");      print("<tr>\n<td colspan='2' align='left'><input type='submit' value='Select Award' name='corAwd'></td>\n</tr>\n");      print("</table>\n");      print("</form>");    }  }  elseif ( $corAwd== "Select Award" )  {    print("Looking up award info...<BR>\n");    $qryStr = "SELECT PopInd, Name FROM Populace WHERE Name = '$name'";    $recRS = mysql_query($qryStr);    $numNames = mysql_numrows($recRS);    if ( $numNames == 1)    {      $recInd = mysql_result($recRS,0,"PopInd");      print ("Name '$name' found, select award and date.<BR>\n");      print("<HR>\n");      print("<p>\n");      print("<form method='post' action='$sPage?action=corawd'>\n");      print("<input type='hidden' name='recInd' value='$recInd'>\n");      print("<table>\n");      print("<td>\n");      dateSelect();      print("</td>\n");      print("<td>\n");      print("Kingdom: <select name='kingdom'>\n");      $qryStr = "SELECT KingdomInd, Name FROM Kingdoms ORDER BY KingdomInd";      $kinRS = mysql_query($qryStr);      $numKin = mysql_numrows($kinRS);      for ($i=0;$i<$numKin;$i++)      {        $kinInd = mysql_result($kinRS,$i,"KingdomInd");        $kinNam = mysql_result($kinRS,$i,"Name");        if ( $kinNam == "Calontir")        {          print("<OPTION SELECTED VALUE='$kinInd'> $kinNam\n");        }        else        {          print("<OPTION VALUE='$kinInd'> $kinNam\n");        }      }      print("</select>\n");      print("</td>\n");      print("</td>\n</tr>\n");      print("<tr>\n<td colspan='2' align='left'><input type='submit' value='Add Award' name='corAwd'></td>\n</tr>\n");      print("</table>\n");      print("</form>");      print("</p>\n");    }    else    {      print ("Name '$name' not found, try again.<BR>\n");      print("<HR>\n");      print("<p>\n");      print("<form method='post' action='$sPage?action=corawd'>\n");      print("<table>\n");      print("<tr>\n<td colspan='2'><H4>Award Correction</H4></td>\n</tr>\n");      print("<tr>\n<td colspan='2'><b>Enter recipient name</b></td>\n</tr>\n");      print("<tr>\n<td>Name:</td>\n<td><input type='text' size='30' maxlen='50' name='name'>\n</td>\n</tr>\n");      print("<tr>\n<td colspan='2' align='left'><input type='submit' value='Check' name='corAwd'></td>\n</tr>\n");      print("</table>\n");      print("</form>");      print("</p>\n");    }  }  else  {    print("</p>\n");    print("<p>\n");    print("<form method='post' action='$sPage?action=corawd'>\n");    print("<table>\n");    print("<tr>\n<td colspan='2'><H4>Award Correction</H4></td>\n</tr>\n");    print("<tr>\n<td colspan='2'><b>Enter recipient name</b></td>\n</tr>\n");    print("<tr>\n<td>Name:</td>\n<td><input type='text' size='30' maxlen='50' name='name'>\n</td>\n</tr>\n");    print("<tr>\n<td colspan='2' align='left'><input type='submit' value='Check' name='corAwd'></td>\n</tr>\n");    print("</table>\n");    print("</form>");    print("</p>\n");  }}function Logout($sPage, $sSite, $valid_user){   $old_user = $valid_user;   $results = session_unregister("valid_user");   session_destroy();   if (!empty($old_user))   {      if ($results)      {          print("Logged out.<br>\n");          print("<a href='http=://$eSite'>Log In</a><br>\n");      }      else      {         print("Could not log you out.<br>\n");         print("<a href='http://$sSite/$sPage?action=menu'>Back</a><br>\n");      }   }   else   {      print("You weren't even logged in.<br>\n");      print("<a href='http=://$eSite/$sPage?action=login'>Log In</a><br>\n");   }}$file = fopen("update-template.html", "r");if (!$file){  echo "<P>Template not available.</P>\n";  exit;}else{  while (!feof($file) )  {    $line = fgets($file, 1024);    if ( strstr($line, "insert content here") )    {      break;    }    else    {      echo "$line";    }  }}?><H2> Calontir OP Update System </H2><?php   if (session_is_registered("valid_user"))   {      print("<DIV STYLE=\"font-family: arial, helvetica, san-serif; font-size: 14pt;\">\n");      print("<H3>Welcome $valid_user</H3>\n");      if ($action == "menu")      {         print("<SPAN STLYE=\"font-weight: bold;\">Select an option</SPAN><br>\n");         OPUpdateMenu($sPage, $eSite);      }      elseif ($action == "addper")      {        AddPerson($sPage, $eSite, $addPer, $name);        print("<p><a href='http://$eSite/$sPage?action=menu'>Return to Main Menu</a></p>\n");      }      elseif ($action == "cornam")      {        CorName($sPage, $eSite, $corNam, $oldname, $recInd, $newname);        print("<p><a href='http://$eSite/$sPage?action=menu'>Return to Main Menu</a></p>\n");      }      elseif ($action == "chanam")      {        ChaName($sPage, $eSite, $chaNam, $oldname, $recInd, $newname);        print("<p><a href='http://$eSite/$sPage?action=menu'>Return to Main Menu</a></p>\n");      }      elseif ($action == "recawd")      {        RecAward($sPage, $eSite, $addAwd, $name, $recInd, $award, $date, $kingdom);        print("<p><a href='http://$eSite/$sPage?action=menu'>Return to Main Menu</a></p>\n");      }      elseif ($action == "corawd")      {        CorAward($sPage, $eSite, $corAwd, $name, $recInd, $award, $date, $kingdom);        print("<p><a href='http://$eSite/$sPage?action=menu'>Return to Main Menu</a></p>\n");      }      elseif ($action == "delawd")      {        print("The Delete Award option is not yet implimented<BR>\n");        print("<p><a href='http://$eSite/$sPage?action=menu'>Return to Main Menu</a></p>\n");      }      elseif ($action == "addawd")      {        print("The Add Award option is not yet implimented<BR>\n");        print("<p><a href='http://$eSite/$sPage?action=menu'>Return to Main Menu</a></p>\n");      }      elseif ($action == "logout")      {         Logout($sPage, $sSite, $valid_user);      }      else      {         print("<SPAN STLYE=\"font-weight: bold;\">Select an option</SPAN><br>\n");         OPUpdateMenu($sPage, $eSite);      }      print("</DIV>\n");   }   else   {      print("<form method='post' action='$sPage'>\n");      print("<table>\n");      print("<tr>\n<td>UserID:</td>\n<td><input type='text' name='userid'>\n</td>\n</tr>\n");      print("<tr>\n<td>Password:</td>\n<td><input type='password' name='password'>\n</td>\n</tr>\n");      print("<tr>\n<td colspan='2' align='left'><input type='submit' value='login' name='submit'></td>\n</tr>\n");      print("</table>\n");      print("</form>");   }  while (!feof($file) )  {    $line = fgets($file, 1024);    if ( strstr($line, "insert content here") )    {      break;    }    else    {      echo "$line";    }  }?>