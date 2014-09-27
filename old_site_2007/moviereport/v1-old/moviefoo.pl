#!/afs/athena/contrib/perl/p
#
#This is a perl-based system for tracking movie expenditures.
#Copyright 1999, Dudley Lamming
#
# This program is modifiable/redistributable under the terms
# of the GNU General Public Licence.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

#Here we open the data file and a temporary report file
open(movie, "moviefoo.txt") || die "Missing ~/movie file";
open(REPORT, ">report")|| die "report:$!\n";

#Now we grab the data file, and sort it. we then proceed to disregard fields
# that say "start" or "#" (movie titles)
@ulist = <movie>;
shift @ulist;
shift @ulist;
$totalmoney = 0;
$totalwent= 0;
$numpeople = -2;
@list = sort @ulist;
$ranktotal=0;
$rankcount=0;

$current= start;

#this section is devoted to calculating the financial data
foreach $personinfo (@list) {    
    chop; 
    ($username, $money, $id, $went, $ranking) = split (/:/ , $personinfo) ; 
    if ($username eq $current) { 
	$personinfo[1]= $money + $personinfo[1]; 
	$personinfo[2]= $id + $personinfo[2]; 
	$personinfo[3]= $went + $personinfo[3];
	if ($ranking >=1){
	    $rankcount= $rankcount +1;
	    $ranktotal= $ranktotal +$ranking;
	}
        }
	else {
	    
	    unless ($current eq start) {
	print $current;
	print "\n";
	print $personinfo[1];
	print " ";
	print $personinfo[2];
	print " ";
	print $personinfo[3];
	print "\n"; 
        $totalmoney = $totalmoney + $personinfo[1];
	$totalwent = $totalwent + $personinfo[3];
	if ($rankcount > 0 ){ $rank = $ranktotal/$rankcount} else 
	    {$rank = " "};
	printf REPORT $current;
	printf REPORT " ";
	printf REPORT $personinfo[1];
	printf REPORT " ";
	printf REPORT $personinfo[2];
	printf REPORT " ";
	printf REPORT $personinfo[3];
	printf REPORT " ";
	printf REPORT $rank;
	printf REPORT "\n"; 
        @personinfo= (0, 0, 0, 0, 0); }
	$current = $username;
	    $rankcount =0;
	    $ranktotal =0;
	    if ($ranking >=1){
	    $rankcount= $rankcount +1;
	    $ranktotal= $ranktotal +$ranking;}
	$numpeople = $numpeople+1;
	$personinfo[1]= $money + $personinfo[1]; 
	$personinfo[2]= $id + $personinfo[2]; 
	$personinfo[3]= $went + $personinfo[3]; 
}



};
# we now allow for the possibility that a person appears only once in the 
# database, and is also the last person listed. annoyingly enough, this has 
# happened. 
if ($went=1) {
printf REPORT $current;
	print $current;
	print "\n";
	print $personinfo[1];
	print " ";
	print $personinfo[2];
	print " ";
	print $personinfo[3];
        printf REPORT " ";
	printf REPORT $personinfo[1];
	printf REPORT " ";
	printf REPORT $personinfo[2];
	printf REPORT " ";
	printf REPORT $personinfo[3];
printf REPORT " ";
printf REPORT $ranking;	
printf REPORT "\n"; 
};

close(movie);
close(REPORT);

#Now lets generate a nice HTML file for the accounting stuff
$counter=3;
open(REPORT, "report")|| die "report:$!\n";
open(HTML, ">report.html") || die "report:$!\n";
@list = <REPORT>;
#the following line cleans up the mess that is the starting lines of the 
#database file
shift @list;
@list = splice(@list,0, $#list);

printf HTML "<HTML> <header> <body bgcolor=white > <title>The Movie Going Web Page</title>";

$total = $totalmoney/$totalwent;
foreach $person (@list) {
    chop; 
    ($username, $money, $id, $went, $ranking) = split (/ / , $person) ;
    $t = $total * $went;
    chop $ranking;
    printf HTML "<h2>User: $!$username </h2> $!\n";
    printf HTML "<ul> $!\n";
    printf HTML "<li>Has gone to $!$went movies. $!\n";
    printf HTML "<li>Has paid %2.2f dollars. $!\n", $money;
    printf HTML "<li>Has left ID $!$id times. $!\n";
    printf HTML "<li>$!$username should have paid %2.2f dollars based on usage $!\n", $t;
    printf HTML "<li>$!$username has given movies an average rating of %2.2f $!\n", $ranking;
    printf HTML "</ul> $!\n";
    
    }

close(REPORT);
close(HTML);

#Now lets generate the Movie Listings, by date, and store it in a temporary 
#file. 

open(movie, "moviefoo.txt") || die "Missing ~/movie file";
open(REPORT, ">reportdate")|| die "report:$!\n";
open(pHTML, ">pmovie.html") || die "report:$!\n";
open(rankfile, ">rankfile.txt") || die "rankfile:$!\n";

@list = <movie>;
shift @list; 
$begining = 1;

foreach $personinfo (@list) {    
    chop; 
    ($username, $name, $date, $fault, $ranking) = split (/:/ , $personinfo) ;
    if ($username eq "#")
    {
	if ($count gt 0){
	$rank = $ratingtotal/$count};
	$ratingtotal=0;
        $count=0;
	printf pHTML "$!\n";
	printf pHTML "</ul>";
	if ($begining == 1) {$begining = 0;}
	else
	    {printf pHTML "Average ranking: %2.2f $!\n", $rank;
	     printf rankfile "Average ranking: %2.2f $!\n", $rank;
	 };
	
	printf pHTML  "<h4> $name , seen $date , because $fault wanted to </h4> <ul>";
}
    else {
	if ($fault == "0"){} else{
	printf pHTML "<li> $username : $ranking"};
	if ($ranking >=1) {
	    if ($ranking <= 10) {

	    $ratingtotal = $ratingtotal + $ranking;
	    $count = $count +1;
	}}

    }
};

if ($count gt 0){
	$rank = $ratingtotal/$count};
printf pHTML "</ul>";
printf pHTML "Average ranking: %2.2f $!\n", $rank;
printf rankfile "Average ranking: %2.2f $!\n", $rank;

close(rankfile);
close(movie);
close(REPORT);
close(pHTML);	

open(HTML, ">movie.html") || die "report:$!\n";
open(pHTML, "pmovie.html") || die "report:$!\n";
printf HTML "<HTML> <header> <body bgcolor=white > <title>The Movie Listing Web Page</title>";

@list = <pHTML>;
# these shifts are necessary to remove the foobar movie from our listings
shift @list;
shift @list;
shift @list;
shift @list;
foreach $personinfo (@list) { printf HTML $personinfo}

close(pHTML);
close(HTML);

# now lets try to sort by movie title
open(movie, "moviefoo.txt") || die "Missing ~/movie file";
open(REPORT, ">reportdate")|| die "report:$!\n";
open(rankfile, "rankfile.txt")|| die "rankfile:$!\n";
@ulist = <movie>;
shift @ulist;
$begining = 1;
@rank = <rankfile>;

foreach $person (@ulist) {
    chop;
    ($username, $name, $date, $fault, $ranking) = split (/:/ , $person) ;
    if ($username eq "#"){
        if ($begining == 1) {$begining = 0}
	else {
	   
	 printf REPORT "<p> $rank[0] </p> $!\n"; shift @rank;};
	    
	chop $fault;
printf REPORT "<h4> $name , seen $date , because $fault wanted to </h4> <ul>";
    }
    else {
	$ranking =~ s/\n$//;
	if ($fault == "0"){}else {
	printf REPORT "<li> $username : $ranking"};
    }}

printf REPORT "<p> $rank[0] </p>";
close(movie);
close(REPORT);


open(REPORT, "reportdate")|| die "report:$!\n";
open(HTML, ">moviedate.html")|| die "report:$!\n";
@list = <REPORT>;
shift @list;
@slist = sort @list;
printf HTML "<HTML> <header> <body bgcolor=white > <title>The Sorted Movie Listing Web Page</title>";
foreach $personinfo (@slist) { printf HTML $personinfo; printf HTML "</ul>";};
close(HTML);
close(REPORT);
