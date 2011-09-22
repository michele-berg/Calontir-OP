<?php

function dbLogin()
{
  mysql_connect("kocdbopinfo.db.7394721.hostedresource.com", "kocdbopinfo", "CalontirOP0311") or die("<BR><H2>Sorry the NEW database server is not available at this time.</H2>");
  mysql_select_db("kocdbopinfo") or die("<BR><H2>Sorry the NEW OP is not available at this time.</H2>");
}

?>

