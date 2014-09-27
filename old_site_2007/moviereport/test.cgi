#!/afs/athena/contrib/perl/p
print "Content-type: text/html", "\n\n";

print "<HTML>", "\n";
print "$!$QUERY_STRING";
print "$!$test";
print "foo";
print $ENV{'QUERY_STRING'};
print "</BODY></HTML>", "\n";


exit (0);
