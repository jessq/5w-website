#!/afs/athena/contrib/perl/p
#
#This is a perl-based system for inputing info into the movie database.
#Dudley Lamming, 1999
#
$continue=true;
while ($continue eq "true") {

system("clear");
print "The Fifth West Movie Database$!\n";
print "Enter Movie Data$!\n";
print "Enter the name of the movie$!\n";
$moviename=<STDIN>;
print "Enter movie date$!\n";
$date=<STDIN>;
print "Who's fault was this movie?$!\n";
$fault=<STDIN>;

print "The following information has been entered. Is this correct? (y/n)$!\n";
print $moviename, $date, $fault;
$test=<STDIN>;
chop $test;
if ($test eq "y") {$continue=false};
}
$continue=true;


while ($continue eq "true") {
    print "Enter payment and id info. Ie, dlamming 0.50 1 => that dlamming paid $0.50 and left id. Enter one person per line. Press Control-D when done $!\n";
    @paid = <STDIN>;
    print "The following information has been entered. Is this correct? (y/n)$!\n";
    print @paid;
    $test=<STDIN>;
    chop $test;
    if ($test="y") {$continue=false};
};
$continue=true;

while ($continue eq "true") {
    
    print "Please enter ranking info for each person who attended. Ie, dlamming 0 $!\n";
    @list = <STDIN>;
    print "The following information has been entered. Is this correct? (y/n)$!\n";
    print @list;
    $test=<STDIN>;
    chop $test;
    if ($test eq "y") {$continue=false};
}

open(findata, ">>findata.txt");
open(rankdata, ">>rankdata.txt");

printf findata "$!$moviename**$!$date**";
$size=$#paid +1;
printf findata "$!$size";
foreach $person (@paid) {
    ($name, $money) = split(/ /, $person);
    printf findata "**$!$name:$!$money"};
printf findata "$!\n";
close(findata);

printf rankdata "$!$moviename**$!$date**$!$fault**";
$size=$#list +1;
printf rankdata "$!$size";
foreach $person (@list){
    ($name, $rank) = split(/ /, $person);
    printf rankdata "**$!$name:$!$rank"};
printf findata "$!$/n";
close(rankdata);
