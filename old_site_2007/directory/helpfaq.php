<?php
require "common.php";

include "header.php";

?>


<H1>Help and FAQ</H1>

<OL type=I>
<H2><LI>Basic questions</H2>

<OL>
<H3><LI>What is this?</H3>
The Fifth-West Resident Database is an online resource for current and former residents to help them find each other!

<H3><LI>Who can join?</H3>
Any current or former resident of the MIT East Campus dormitory, floor 5W.

<H3><LI>Why should I join?</H3>
You can find old friends, old friends can find you, and reunions can be organized! Plus, you'll get the occasional invitation from 5W to our events for alums, like our annual Pre-Thanksgiving dinner. Note that some of the information and features of the database are available only to registered members.

<H3><LI>How can I join?</H3>
Click the join button at the top! You'll be led step-by-step in joining. It will only take a couple minutes to finish the basic questionnaire-- more if you'd like to share a few paragraphs about yourself, or your fifth-west memories.
</OL>

<H2><LI>Other questions</H2>

<OL>
<H3><LI>Who can see my information?</H3>
You can choose. You can set your information so that it is visible to everyone in the world, or just to other members of the database (which might address your privacy concerns.)

<H3><LI>Do I use my Athena username and password?</H3>
No! The database is in no way connected with any other system. In general, you should use a different password for every system you access. We have made some basic efforts to secure the database, but it's conceivable that a determined hacker might be able to retrieve your password.

<H3><LI>I have a suggestion</H3>
Great! Email <A HREF="mailto:eolson@mit.edu">Edwin Olson</A> your suggestion!

</OL>

<H2><LI>Troubleshooting</H2>

<OL>
<H3><LI>I can't see my information!</H3>
Did you just enter your information? New users must be approved by an administrator before their information is made public. This is meant to discourage abuse of the system. The database administrators were automatically sent an email indicating that you need to be approved, and will do so as soon as they can.

<H3><LI>I have forgotten my password!</H3>
No problem. Have an <A HREF="lostpassword.php">email sent</A> to your account to remind you of it!

<H3><LI>Someone else put me in the database. How can I edit it?</H3>
Someone was probably trying to be helpful. If they entered in your email address correctly, click <A HREF="lostpassword.php">here</A> to have an email sent to you with your login and password information. If the password is wrong or absent, go ahead and create yourself a new account. An administrator will delete the old (obsolete) account later.

<H3><LI>I've found a bug!</H3>
Well, that's unfortunate but not entirely surprising. Give <A HREF="mailto:eolson@mit.edu">Edwin Olson</A> an email, and try to describe exactly how to reproduce the bug.

<H3><LI>Nothing seems to work right at all</H3>
Do you have cookies enabled? This site uses cookies extensively to keep track of who you are, and without them, you will be repeatedly asked to log in, but will never be able to.
</OL>

<H2><LI>Technical Information</H2>

<OL>
<H3><LI>How does this work?</H3>
Our hall has a x86 Linux machine, which runs the <A href="http://www.apache.org">Apache</A> web server, compiled with support for <A href="http://www.mysql.com">MySQL</A> and <A href="http://www.php.net">PHP</A>. When you enter your information, it's added to an SQL database which is queried to generate the pages you see in real-time. MySQL does all of the hard work, all we had to do was add some basic functionality on top! I'd be more than willing to chat about this in more detail if you're curious, just drop me a <A href="mailto:eolson@mit.edu">line</A>!

<H3><LI>Who do I send my money to?</H3>
To be entirely honest, the best thing you could do would be to add a nice schpiel to your "where are they now" field about your memories of 5W, and where you are now, and make sure that you have a picture of yourself online! (If you're really trying to put your wallet on a diet, talk to me though! :)

</OL>
</OL>

<?php
include "footer.php";
?>
