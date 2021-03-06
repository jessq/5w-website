#!/afs/athena/contrib/perl/p

print "Content-type: text/html", "\n\n";

@data= split(/&/,  $ENV{'QUERY_STRING'});
($c, $title) = split(/=/, @data[0]);
$title = join(" ", split(/\+/, $title));
$title = join(":", split(/%3A/, $title));
($c, $date) = split(/=/, @data[1]); $date = join("/", split(/%2F/, $date));
($c, $fault) = split(/=/, @data[2]);
($c, $paid)= split(/=/, @data[3]);
@paidn =  split(/%0D%/, $paid);
($c, $rank) = split(/=/, @data[4]);
@rankn = split(/%0D%/, $rank);

$ranknum= $#rankn +1;
$paidnum = $#paidn +1;

if ($paidnum==0) {$type=0} else{$type=1};

$paid= join("@@", split(/%0D%0A/, $paid)); 
$paid= join(":", split(/\+/, $paid));


$rank= join("@@", split(/%0D%0A/, $rank)); 
$rank = join(":$!$type:", split(/\+/, $rank));


$finstring = join("@@", $title, $date, $paidnum, $paid);
$rankstring= join("@@", $title, $date, $fault, $ranknum, $rank);

system("touch allwrite/findata");
system("touch allwrite/rankdata");
system("echo '$!$finstring' >> allwrite/findata");
system("echo '$!$rankstring' >> allwrite/rankdata");

print "Thank you! Your movie entry has been saved.";



exit (0);
