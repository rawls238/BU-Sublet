<?php
include("include/header.php");
echo $header;

echo<<<END
<html>
<body>
<h1>About Us</h1><br>
<h3>So who made it?</h3>
<b>Guy Aridor</b> - Developer and all that other stuff<br>
<b>Michael Bronfman</b> - Design
<br><br>

Thanks to Matt Auerbach and to all my friends for their invaluable help with this. <br>Also, check out our 'sister site' - <a href = 'http://www.buroomswap.com'>BU Room Swap</a>.<br><br>
<h3>Why do this?</h3>


<p>Well, why does anyone make anything they feel is worth making? Either because it would be a good learning experience or because 
there was something that he or she felt was done inefficiently and that he or she could make it better and more efficient. This site happens to be a combination of both,
but, of course, a single mind is blinded by its own vision so, naturally, there's some things that could be missing and please email us your ideas, no
matter how trivial you may think they are. Mostly, In any case, this site exists to make the subletting process a little bit more seamless for both subletters and subletees. Some features of this site could
naturally be extended to apartment rentals and leases and all that stuff, but unless there's a serious want for it, it's probably not coming.</p>

<h3>Site Overview</h3>
<b>1.</b> Only BU Students can post and view listings (network verified via Facebook).<br>
<b>2.</b> Subletees can 'track' regions to be notified of any new apartments in the tracked region.<br>
<b>3.</b> From (2), people looking to sublet their apartment can find people who have already said that they would be interested in subletting in that area.<br>
<br>
<br>
The site is currently in beta so email any additional feature ideas / bugs to <a href = 'mailto:busublet@gmail.com'>busublet@gmail.com</a>. Thanks!

</body>
</html>
END;

include("include/footer.php");
echo $footer;


?>