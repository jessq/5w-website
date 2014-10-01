#!/afs/athena/contrib/perl/p

print "Content-type: text/html", "\n\n";

@data= split(/&/,  $ENV{'QUERY_STRING'});
($c, $title) = split(/=/, @data[0]);
($c, $date) = split(/=/, @data[1]); $date = join("/", split(/%2F/, $date));
($c, $fault) = split(/=/, @data[2]);
($c, $paid)= split(/=/, @data[3]);
@paidn =  split(/%0D%/, $paid);
($c, $rank) = split(/=/, @data[4]);
@rankn = split(/%0D%/, $rank);

$ranknum= $#rankn +1;
$paidnum = $#paidn +1;


$paid= join("@@", split(/%0D%0A/, $paid)); 
$paid= join(":", split(/\+/, $paid));


$rank= join("@@", split(/%0D%0A/, $rank)); 
$rank = join(":", split(/\+/, $rank));


$finstring = join("@@", $title, $date, $paidnum, $paid);
$rankstring= join("@@", $title, $date, $fault, $ranknum, $rank);

open(rank, ">>rankdata.txt");
printf rank $rankstring;
close(rank);

open(fin, ">>findata.txt");
printf fin $finstring;
close(fin);

print "Thank you! Your movie entry has been saved."



exit (0);
