#!/usr/bin/perl

use CGI qw(:all);                   # The standard CGI module
use CGI::Carp qw(fatalsToBrowser);  # Send errors to browser window

$upload_dir = "/web/magicmurals/prod/images/customer_gallery/";

print "Content-Type: text/html\n\n";
$q = new CGI;
$uploaded = $q->param("uploaded");

if ($uploaded ne "1") {
    print <<HERE;
    <HTML>
        <HEAD><title>Upload Page</title></HEAD>
        <BODY>  <FORM ACTION="uploader.pl" METHOD="post" ENCTYPE="multipart/form-data">
<h3>Magic Murals - Customer Gallery Admin</h3>
        File to Upload: 
<br>
1) <INPUT TYPE="file" NAME="upfile1">  
<br>
2) <INPUT TYPE="file" NAME="upfile2">  
<br>
3) <INPUT TYPE="file" NAME="upfile3">  
<br>
4) <INPUT TYPE="file" NAME="upfile4">  
<br>
5) <INPUT TYPE="file" NAME="upfile5">  
<br>
	<br>
        <INPUT TYPE="submit" NAME="Submit" VALUE="Upload File">
        <input type="hidden" name="uploaded" value="1">
        </FORM>  </BODY></HTML>
HERE
} else {
    print "<br>\n";
    print "<!-- Form Result -->";
	for ($i=1;$i<6;$i++) {
		$filename = $q->param("upfile$i");
		next if !$filename;
		$filename =~ s/ /\ /g; 
  		$shortname = $filename;
  	  	$shortname =~ s/.*[\\|\/](.+)$/$1/ ; # grab end of path
		if ($shortname !~ /.jpg$/ && $shortname !~ /.png$/ && $shortname != 'info.txt') {
			print "<font color='red'>Invalid filename: $filename</font> - skipping upload.  Filename should end in '.jpg' or '.png':  ($shortname)<br>";
			next;
		}
    		print "Uploading $filename to $upload_dir <br> \n";
  	  	open (FILE,">$upload_dir/$shortname") or die $!;
  	  	while ( read($filename,$buffer,1024) ) {
     	   		print FILE $buffer;
    		}
    		close (FILE);
    		chmod 0664,"$upload_dir/$shortname";
    		print "$filename has been uploaded to $upload_dir <br>\n";
	}


    print "<br>\n<br>\n";
    print qq(<a href="uploader.pl">Submit another upload</a>);
}



print "<h4><a href='example.php' target='_blank'>Example</a></h4>";
print "<h4>Existing Graphics</h4><ul>";

 opendir(DIR, $upload_dir) or die $!;
while (my $file = readdir(DIR)) {
	next unless (-f "$upload_dir/$file");
	 next unless ($file =~ m/\.jpg$/i || $file == 'info.txt');
        print "<li><a href='$file' target='_blank'>$file</a></li>";

    }
print "</ul>";
