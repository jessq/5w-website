#!/usr/athena/bin/perl
print "Content-type: text/html", "\n\n";

print "<HTML>", "\n";
($user, $went, $didpay, $leftid, $shouldpay, $rank) =  split(/&/, $ENV{'QUERY_STRING'}, 6);

print "<h2>User: $!$user </h2><ul>$!\n";
print "<li>Has gone to $!$went movies. $!\n";
print "<li>$!$user has given movies an average rating of $!$rank <\ul>$!\n";
print "<p>$!$user";
print "'s rating profile:<p> ";
print "<img src='http://fw.mit.edu/moviereport/userdist.cgi?USER=$!$user'>";

exit (0);
