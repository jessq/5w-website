#!/afs/athena/contrib/perl/p

#lets grab some our cgi submitted data
system("cat allwrite/findata >> findata.txt");
system("cat allwrite/rankdata >> rankdata.txt");
system("cp allwrite/findata.perm allwrite/findata");
system("cp allwrite/rankdata.perm allwrite/rankdata");

#the first thing we do is generate a program for ed's cgi scripts to use
open(rankdata, "rankdata.txt");
open(cgi, ">cgitemp.txt");
open(names, ">movienames.txt");
@list = <rankdata>;
@slist = sort @list; 
($cname, $date, $fault, $number, $entry) = split(/@@/, $slist[0], 5);
$last = $#slist;
$movienumber = 1; 
$i = 0; 
foreach $line (@slist){
	($name, $date, $fault, $number, $entry) = split(/@@/, $slist[$i], 5);
	$i = $i + 1;
	
	if ($cname eq $name) {} else {$movienumber = $movienumber +1; 
printf names "$!$cname $!\n"; $cname = $name};
	for ($j = 1; $j <= $number; $j= $j +1){
		($first, $entry) = split(/@@/, $entry, 2);
		($username, $useless, $rank) = split(/:/, $first, 3);
		printf cgi "$!$username:$!$movienumber:$!$rank$!\n";
	};
	  
};
printf names "$!$cname";
close (cgi);
close (rankdata);
close(names);

open(cgitemp, "cgitemp.txt");
open(cgidata, ">cgidata.txt");
@list = <cgitemp>;
@slist = sort @list; 

foreach $list (@slist) {
	($name, $movienum, $rank) = split (/:/, $list, 3);
	if ($movienum >=1) {
		printf cgidata "$!$name:$!$movienum:$!$rank";
	};
};
close (cgidata);
close (cgitemp);

#now we start work on the messiest part, the financial database
open(findata, "findata.txt");
open(rankdata, "rankdata.txt");
open(finpaid, ">finpaid.txt");
open(finrec, ">finrec.txt");
open(total, ">total.txt");

@finlist = <findata>;
@ranklist=<rankdata>;
$linecount = 0;

#this section creates a finpaid.txt file, which lists all financial
# contributions. we'll do a sort and total them up later.
chop @finlist;
foreach $listing (@finlist) {
    $linecount = $linecount +1;
    $totalcost = 0;

    ($moviename, $date, $number, $entry) = split (/@@/ , $listing, 4);

    for ($i=1; $i <= $number; $i=$i+1) {
	($first, $entry) = split (/@@/ , $entry, 2);
	    ($name, $paid, $id) = split (/:/ , $first);
            $totalcost = $totalcost + $paid;

	    printf finpaid "$!$first$!\n";
	}
    printf total "$!$totalcost$!\n"; 
};
close(total);

#this section creates a finrec.txt file, which lists all financial 
#contributions (ie, how much you shoudl have paid, prorated on attendance, 
#expense, etc). we'll do a sort and add later.
open(total, "total.txt");
@total = <total>;
$temp=0;
    foreach $rankline (@ranklist){
    ($moviename, $date, $fault, $number, $entry) = split (/@@/ , $rankline,  5);
    $mult = $total[$temp]/$number; $temp=$temp+1;

    for ($i=1; $i <= $number; $i=$i+1) {
	($first, $entry) = split (/@@/ , $entry, 2);
	($name, $type, $ranking) = split (/:/ , $first);
	$amount = $mult * $type;
	if ($i eq $number) {chop $ranking};
	if ($ranking gt 0) {printf finrec "$!$name $!$amount $!$ranking$!\n";}
	else {printf finrec "$!$name $!$amount$!\n";};
    };
};
close(finrec);
close(total);
close(finpaid);

#now lets do a sort and add on the finpaid.txt file
open(finpaid, "finpaid.txt");
@finpaid=<finpaid>;
open(temp, ">paidtemp.txt");

@spaid = sort @finpaid;

chop @spaid;
($cname, $paid, $id) = split(/:/, @spaid[0]);
$paidtotal = 0;
$idtotal = 0;
foreach $person (@spaid) {
   ($name, $paid, $id) = split(/:/, $person); 
   if ($name eq $cname)   {
       $paidtotal = $paidtotal + $paid;
       $idtotal= $idtotal +$id;}
   else {
       printf temp "$!$cname:%2.2f:$!$idtotal$!\n",$paidtotal;
       $paidtotal = $paid;
       $idtotal = $id;
       $cname = $name;}
};
#now we take of the last person in the file
printf temp "$!$cname:%2.2f:$!$idtotal$!\n",$paidtotal;

close(temp);
close(finpaid);

#now lets do a sort and add on the finrec.txt file
open(finrec, "finrec.txt");
@finrec=<finrec>;
open(temp, ">rectemp.txt");

@spaid = sort @finrec;
($cname, $paid, $ranking) = split(/ /, @spaid[0]);
$paidtotal = 0.00;

$ranktotal = 0;
$counttotal = 0;
$countreal= 0;
foreach $person (@spaid) {
    ($name, $paid, $ranking) = split(/ /, $person);
    if ($name eq $cname)   {
	 
	$paidtotal = $paidtotal + $paid;

	$ranktotal = $ranktotal +$ranking;
	$countreal= $countreal +1;
	if ($ranking gt 0) {$counttotal = $counttotal +1;}
 }
 else {
     if ($counttotal gt 0) {$avg = $ranktotal / $counttotal;} else {$avg =0};
     chop $paidtotal;
     printf temp "$!$cname $!$paidtotal %2.2f $!$countreal$!\n", $avg;
       $paidtotal = $paid;
     $ranktotal = $ranking;
       $cname = $name;
     $countreal = 1;
     if ($ranking gt 0)
     {$counttotal = 1}
     else {$counttotal = 0}
;}
};
#now we take of the last person in the file
if ($counttotal gt 0) {$avg = $ranktotal / $counttotal ;} else {$avg =0};
printf temp "$!$cname $!$paidtotal $!$avg $!$countreal$!\n";

close(temp);
close(finrec);

#now that we've got that taken care of, lets combine stuff into a user info
#html file. 

open(fin, "paidtemp.txt");
open(rec, "rectemp.txt");
system("cp index.html user.html");
open(html, ">>user.html");
@fin = <fin>;
@rec = <rec>;
$i = 0;

foreach $person (@rec) {
    ($name, $shouldpay, $rank, $went) = split (/ /, $person);
    @test=grep(/$name/i, @fin);
    ($finame, $didpay, $leftid) = split (/:/, $test[0]);

    if ($finame eq $name) {
	printf html "<h2>User: <a href ='http://fw.mit.edu/moviereport/cgi/userstats.cgi?$!$name&$!$went&$!$didpay&$!$leftid&$!$shouldpay&$!$rank'>$!$name </a></h2> $!\n";
	printf html "<ul> $!\n";
	printf html "<li>Has gone to $!$went movies. $!\n";
	printf html "<li>Has paid %2.2f dollars. $!\n", $didpay;
	printf html "<li>Has left ID $!$leftid times. $!\n";
    printf html "<li>$!$name should have paid %2.2f dollars based on usage $!\n", $shouldpay;
    printf html "<li>$!$name has given movies an average rating of %2.2f $!\n", $rank;
	printf html "</ul> $!\n";
}
else {    
    $i=$i+1;
    if ($went ge 5) {
	printf html "<h2>User:<a href ='http://fw.mit.edu/moviereport/cgi/use\
rstats.cgi?$!$name&$!$went&$!$didpay&$!$leftid&$!$shouldpay&$!$rank'> $!$name </a></h2> $!\n";
        printf html "<ul> $!\n";
        printf html "<li>Has gone to $!$went movies. $!\n";
        printf html "<li>$!$name should have paid %2.2f dollars based on usage $!\n", $shouldpay;
	printf html "<li>$!$name has never paid and never left id. $!\n";
	printf html "<li>$!$name has given movies an average rating of %2.2f $!\n", $rank;

	printf html "</ul> $!\n";
    }}};

close(html);
close(rec);
close(fin);

#we're done with financial stuff. everything from here on is movie info.
open(movie, "rankdata.txt");
@movie = <movie>;

#the first thing we want to do is find out the average rating and the 
#normalized rating for each movie. 
open(rec, "rectemp.txt");
@rec = <rec>;

open(list, ">list.txt");
$count = 0;
foreach $movie (@movie) {
($title, $date, $fault, $number, $entry) = split(/@@/ , $movie, 5);
$realsum = 0;
$normsum = 0;
$num = scalar($number);

for ($i = 1; $i <= $num; $i = $i+1) {
    ($first, $entry)= split(/@@/ , $entry, 2);
    ($name, $type, $rank) = split(/:/, $first);
    @test=grep(/$name/i, @rec);
        ($name, $fin, $avg) = split(/ /, $test[0]);
    $diff = $avg -5.5;
    $rate = $rank - $diff;
    if ($rank >= 1) {
    $realsum = $realsum + $rank;
    $normsum =$normsum + $rate;
    $count = $count +1;
}};
$realsum = $realsum/$count;
$normsum = $normsum/$count;
$count = 0;
printf list "$!$title@@$!$date@@%2.2f@@$normsum$!\n", $realsum;
};
close(list);

#now, lets do a sort, add, and format on the list :)
open(list, "list.txt");
@list=<list>;

system("cp index.html quicklist.html");
system("cp index.html table.html");
open(table, ">>table.html");
printf table "<TABLE border=0 cellpadding=3 > <TR> <TD> Movie Title  </TD><TD> Rating  </TD><TD> Normalized Rating  </TD><TD> Date(s) Shown </TD><TD> </TR>";
open(html, ">>quicklist.html");
@slist = sort @list;

($cname, $date, $ranking, $norm) = split(/@@/, @slist[0]);
$normtotal = 0;
$ranktotal = 0;
$count = 0;
$datestring = "";
$ccolor="skyblue";
$movienum = 0;
foreach $line (@slist) {
    ($name, $date, $ranking, $norm) = split(/@@/, $line);
    if ($name eq $cname)   {
        $normtotal = $normtotal + $norm;
	$datestring = join(" ", $datestring, $date);
        $ranktotal = $ranktotal +$ranking;
        $count= $count +1;
            }
    else {
        $r = $ranktotal/$count; $n = $normtotal/$count;
	$htmlname = join("", split( / /, $cname));
	
	printf html "<p><a href='byname.html/#$!$htmlname'> $!$cname </a>";
	
	printf table "<tr bgcolor=$!$ccolor><td> <a href='http://web.mit.edu/5west/www/moviereport/byname.html/#$!$htmlname'> $!$cname </a></td>";
	if ($ccolor eq "skyblue") {$ccolor = "white"} else {$ccolor="skyblue"};
	if ($n <= 1) {$n =1};
	if ($n >= 10) {$n =10};
        printf html "<ul><li>Rating: %2.2f<li>Normalized Rating: %2.2f", $r, $n; 
	printf html "<li> Seen on: $!$datestring </ul>";
	$movienum = $movienum +1;
	printf table "<td><a href='http://fw.mit.edu/moviereport/cgi/moviestats.cgi?$!$movienum&$!$cname'>%2.2f</a></td><td>%2.2f</td>", $r, $n;
	printf table "<td> $!$datestring </td></tr>";
	$normtotal = $norm;
	$ranktotal = $ranking;
#we do this so we can get a listing of each date. spiffy, huh?
	$datestring = "$!$date";
	$cname = $name;
	$count = 1;
     	;}
};
#now we take of the last movie in the file
$r = $ranktotal/$count; $n = $normtotal/$count;
$htmlname = join("", split( / /, $cname));
printf html "<p><a href='http://web.mit.edu/5west/www/moviereport/byname.html/#$!$htmlname'> $!$cname </a>"; 
printf table "<tr bgcolor=$!$ccolor><td> <a href='byname.html/#$!$htmlname'> $!$cname </a></td>";

if ($n <= 1) {$n =1};
if ($n >= 10) {$n =10};
printf html "<ul><li>Rating: %2.2f<li>Normalized Rating: %2.2f", $r, $n;     
printf html "<li> Seen on: $!$datestring </ul>";
$movienum = $movienum +1;
printf table "<td><a href='http://fw.mit.edu/moviereport/cgi/moviestats.cgi?$!$movienum&$!$cname'>%2.2f</a></td><td>%2.2f</td>", $r, $n;
printf table "<td> $!$datestring </td></tr>";
printf table "</table>";
close(table);
close(html);

#now, lets create a sorted by title list, _but_, we'll include all the
#attendance (and ranking info). 

system("cp index.html byname.html");
open(html, ">>byname.html");
open(movies, "rankdata.txt");
@movies = <movies>;
@mlist = sort @movies;


foreach $line (@mlist) {
    ($moviename, $date, $fault, $number, $entry) = split (/@@/ , $line,  5 );
    $imdblink = join("+", split(/ /, $moviename));
    $frontlink = "http://us.imdb.com/Title";
    $imdblink= join("?", $frontlink , $imdblink); 
    $htmlname = join("", split( / /, $moviename));
    printf html "<p><a name='$!$htmlname'> <a href=$!$imdblink>$!$moviename</a></a>, seen on $!$date, because $!$fault wanted to<ul>";
    $ranktotal = 0; $count =0;
    for ($i=1; $i <= $number; $i=$i+1) {
	($first, $entry) = split (/@@/, $entry, 2);
	($name, $type, $rank) = split(/:/, $first, 3);
	
	if ( $rank >= 1 )  {
	    $ranktotal = $ranktotal+$rank;
	    $count = $count +1;
	};
	printf html "<li>$!$name: $!$rank"; 
    };
    if ($count gt 0) {$r = $ranktotal/$count};
    printf html "<p> Average Rating: %2.2f", $r;
    printf html "</ul>";
}; 

close(html);


#ok, now, for convience and historical reasons, we're going to do the
#same thing, but this time, we're going to sort by date. we'll reverse
#the list so its easier for people to see new movies. we use @movies
 
system("cp index.html bydate.html"); 
open(html, ">>bydate.html");
open(temp, ">temp.txt");

$end = $#movies;

for ($i=$end ; $i ge 0; $i=$i-1) {
    printf temp $movies[$i];
};

close(temp);
open(temp, "temp.txt");
@mlist = <temp>;


foreach $line (@mlist) {
    ($moviename, $date, $fault, $number, $entry) = split (/@@/ , $line,  5 );
    $imdblink = join("+", split(/ /, $moviename));
    $frontlink = "http://us.imdb.com/Title";
    $imdblink= join("?", $frontlink , $imdblink);
    $htmlname = join("", split( / /, $moviename));
    printf html "<p><a name='$!$htmlname'> <a href=$!$imdblink>$!$moviename</a></a>, seen on $!$date, because $!$fault wanted to<ul>";
    $ranktotal = 0; $count =0;
    for ($i=1; $i <= $number; $i=$i+1) {
        ($first, $entry) = split (/@@/, $entry, 2);
        ($name, $type, $rank) = split(/:/, $first);
	if ($rank >= 1) {
            $ranktotal = $ranktotal+$rank;
            $count = $count +1;
        };
        printf html "<li>$!$name: $!$rank";
    };
    if ($count gt 0) {$r = $ranktotal/$count};
    printf html "<p> Average Rating: %2.2f", $r;
    printf html "</ul>";
};

close(html);
