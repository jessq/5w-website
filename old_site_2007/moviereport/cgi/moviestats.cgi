#!/usr/athena/bin/perl
print "Content-type: text/html", "\n\n";

print "<HTML>", "\n";

($number, $name) =  split(/&/, $ENV{'QUERY_STRING'}, 2);

open(rank, "../movienames.txt");
@list = <rank>;
@list = sort @list;
$where = $number -1;
$entry = $list[$where];
($name, $etc) = split(/@@/, $entry);
print "Ranking profile for $!$name: <p>";
print "<img src='http://fw.mit.edu/moviereport/moviedist.cgi?MOVIE=$!$number'>";

exit (0);
