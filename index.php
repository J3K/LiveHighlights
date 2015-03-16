<?php
ob_start(); 

$cachefile = 'cache/cachefile';
$cachetime = 1200;//120 * 60; // 2 hours

if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))) {
    include($cachefile);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link type="text/css" rel="stylesheet" href="css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="css/bootstrap-theme.css" />
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css" />

    <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>

    <script src="js/bootstrap.js"></script>
    <script src="js/script.js"></script>

    <link href='http://fonts.googleapis.com/css?family=Slabo+27px|Roboto+Condensed|PT+Sans' rel='stylesheet' type='text/css'>
    <title>LiveHighlights - Stream Provider</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="row text-center">
            <div class="col-xs-6 col-sm-4"><img src="img/logo.png" class="img-responsive" style="width: 70px "/></div>
            <div class="col-xs-6 col-sm-4"  style="color:white"><h3><strong>LiveHighlights</strong><h3></div>
            <!-- Optional: clear the XS cols if their content doesn't match in height -->
            <div class="clearfix visible-xs-block"></div>
            <div class="col-xs-6 col-sm-4"></div>
        </div>
    </div>
</nav>


<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-8">
            <?php
            include('simple_html_dom.php');
            $html = file_get_html('http://livefootballvideo.com/streaming');
            $i = 1;
            foreach($html->find('div[class=listmatch] ul li') as $element)
            {

                $LeagueName = $element->children(1)->plaintext;
                $StartTime = $element->children(2)->children(0)->plaintext;
                $EndTime = $element->children(2)->children(1)->plaintext;
                $MatchDate = $element->children(2)->children(2)->plaintext;
                $HomeTeam = $element->children(3)->plaintext;
                $AwayTeam = $element->children(5)->plaintext;
                $StreamingLinks = $element->children(6)->children(0)->getAttribute('href');
                
                echo "<blockquote><span class=\"text-center\"><strong>
                        <h5>".$LeagueName."
                            <mark>".$HomeTeam." - ".$AwayTeam."</mark>  ".$MatchDate." ~ ".$StartTime."-".$EndTime."
                        </h5>
                    </strong></span></blockquote>";

                echo "<table width=\"100%\" class=\"table table-hover table-condensed  id=\"blah\" \">
                <thead>
                    <tr><th></th> <th>Channel</th> <th>Language</th> <th>Bitrate</th> <th>Link</th> </tr>
                </thead>";
                
                $streamings = file_get_html($StreamingLinks);
                
                echo "<tbody>";
                foreach($streamings->find('div[class=single] div[id=livelist] table[class=streamtable] tbody tr') as $element)
                { 
                    echo $element;
                }
                echo "</tbody>";
                echo "</table>";
                $i++;
            }
            ?>

            <?php
            //include('simple_html_dom.php');
            $html3 = file_get_html('http://livefootballvideo.com/streaming/page/2');
            $i = 1;
            foreach($html3->find('div[class=listmatch] ul li') as $element)
            {

                $LeagueName = $element->children(1)->plaintext;
                $StartTime = $element->children(2)->children(0)->plaintext;
                $EndTime = $element->children(2)->children(1)->plaintext;
                $MatchDate = $element->children(2)->children(2)->plaintext;
                $HomeTeam = $element->children(3)->plaintext;
                $AwayTeam = $element->children(5)->plaintext;
                $StreamingLinks = $element->children(6)->children(0)->getAttribute('href');
                
                echo "<blockquote><span class=\"text-center\"><strong>
                        <h5>".$LeagueName."
                            <mark>".$HomeTeam." - ".$AwayTeam."</mark>  ".$MatchDate." ~ ".$StartTime."-".$EndTime."
                        </h5>
                    </strong></span></blockquote>";

                echo "<table width=\"100%\" class=\"table table-hover table-condensed  id=\"blah\" \">
                <thead>
                    <tr><th></th> <th>Channel</th> <th>Language</th> <th>Bitrate</th> <th>Link</th> </tr>
                </thead>";
                
                $streamings = file_get_html($StreamingLinks);
                
                echo "<tbody>";
                foreach($streamings->find('div[class=single] div[id=livelist] table[class=streamtable] tbody tr') as $element)
                { 
                    echo $element;
                }
                echo "</tbody>";
                echo "</table>";
                $i++;
            }
            ?>
        </div>

        
    <div class="col-xs-6 col-md-4">
        <span class="text-center">
            <strong>Ads to help</strong>
        </span>
    </div>
</div>

</body>
</html> 

<script>
$(document).ready(function() { 
        $('tbody tr:nth-child(1)').hide(); 
        $('tbody tr:last').hide(); 
    }); 

$('a[href^="http://"]').attr('target','_blank');
</script>

<?php
    $fp = fopen($cachefile, 'w'); 
    fwrite($fp, ob_get_contents());

    fclose($fp);
    ob_end_flush();
?>