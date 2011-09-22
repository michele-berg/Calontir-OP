<?PHP

$user_name = "kocdbopinfo";
$password = "CalontirOP0311";
$database = "kocdbopinfo";
$server = "kocdbopinfo.db.7394721.hostedresource.com";

$db_handle = mysql_connect($server, $user_name, $password);

$db_found = mysql_select_db($database, $db_handle);

if ($db_found) {
// how many rows to show per page
$rowsPerPage = 20;

// by default we show first page
$pageNum = 1;

// if $_GET['page'] defined, use it as page number
if(isset($_GET['page']))
{
    $pageNum = $_GET['page'];
}

// counting the offset
$offset = ($pageNum - 1) * $rowsPerPage;

$query = " SELECT Name FROM Populace" .
         " LIMIT $offset, $rowsPerPage";
$result = mysql_query($query) or die('Error, query failed');

// print the random numbers
while($row = mysql_fetch_array($result))
{
   echo $row['Name'] . '<br>';
}

// how many rows we have in database
$query   = "SELECT COUNT(Name) AS numrows FROM Populace";
$result  = mysql_query($query) or die('Error, query failed');
$row     = mysql_fetch_array($result, MYSQL_ASSOC);
$numrows = $row['numrows'];

// how many pages we have when using paging?
$maxPage = ceil($numrows/$rowsPerPage);

// print the link to access each page
$self = $_SERVER['PHP_SELF'];

// creating previous and next link
// plus the link to go straight to
// the first and last page

if ($pageNum > 1)
{
   $page  = $pageNum - 1;
   $prev  = " <a href=\"$self?page=$page\">[Prev]</a> ";

   $first = " <a href=\"$self?page=1\">[First Page]</a> ";
}
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   $next = " <a href=\"$self?page=$page\">[Next]</a> ";

   $last = " <a href=\"$self?page=$maxPage\">[Last Page]</a> ";
}
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}

// print the navigation link
echo $first . $prev .
" Showing page $pageNum of $maxPage pages " . $next . $last;

mysql_close($db_handle);
}
else {
print "Database NOT Found ";
}

?>