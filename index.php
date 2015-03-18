<?php
ob_start(); 

$cachefile = 'cache/cachefile';
$cachetime = 1600;//120 * 60; // 2 hours

if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))) {
    include($cachefile);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">   
<head>
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="author" content=" JK ">

<link rel="apple-touch-icon" sizes="57x57" href="img/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="img/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="img/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="img/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="img/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="img/apple-touch-icon-152x152.png">
<link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="img/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/png" href="img/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="img/manifest.json">
<meta name="msapplication-TileColor" content="#00a300">
<meta name="msapplication-TileImage" content="img/mstile-144x144.png">
<meta name="theme-color" content="#ffffff">

    <link type="text/css" rel="stylesheet" href="css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="css/bootstrap-theme.css" />
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="//cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="//cdn.datatables.net/responsive/1.0.4/css/dataTables.responsive.css" />

    <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/responsive/1.0.4/js/dataTables.responsive.js"></script>
    <script src="//cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.js"></script>

    <script src="js/bootstrap.js"></script>
    <script src="js/script.js"></script>

    <link href="http://fonts.googleapis.com/css?family=Slabo+27px|Roboto+Condensed|PT+Sans" rel="stylesheet" type="text/css" />
    <title>LiveHighlights &middot; Stream Provider</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="row text-center">
            <div class="col-xs-6 col-sm-4">
                <img src="img/logo.png" class="img-responsive" style="width: 60px;margin:5px"/>
            </div>

            <div class="col-xs-6 col-sm-4"  style="color:white">
                <h3><strong>LiveHighlights</strong></h3>
            </div>

            <div class="col-xs-6 col-sm-4"></div>
        </div>
    </div>
</nav>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div data-example-id="dismissible-alert-js" class="bs-example bs-example-standalone">
            <div role="alert" class="alert alert-info alert-dismissible fade in">
                <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
                <span class="text-center"> <h4>Use <strong>Search</strong> or <strong>"CTRL+F"</strong> To search for your game </h4></span>
            </div>    
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <?php
            include('simple_html_dom.php');

            $html = file_get_html('http://livefootballvideo.com/competitions/premier-league/');
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
                
                echo "
                <div class=\"panel panel-default\">
                <div class=\"panel-heading\">
                <span class=\"text-center\"><strong>
                        <h5><b><font color=\"darkgreen\">".$LeagueName."</font></b>
                         ~ <mark><b>".$HomeTeam." - ".$AwayTeam."</b></mark> ~ <font color=\"darkblue\"><b>".$MatchDate."</b></font>~ <b>".$StartTime."-".$EndTime."
                        </b></h5>
                    </strong></span>
                    </div>

                    <div class=\"row\" style=\"padding:4px\"></div>";

                echo "<table width=\"100%\" class=\"table table-hover table-stripped dt-responsive\" id=\"example".$i."\" cellspacing=\"0\">
                <thead>
                    <tr><th></th><th>Channel</th> <th>Language</th> <th>Bitrate</th> <th>Link</th> </tr>
                </thead>";

                $streamings = file_get_html($StreamingLinks);

                foreach($streamings->find('div[class=single] div[id=livelist] table[class=streamtable] tbody') as $element)
                { 
                    //print_r($element);
                    // echo "<tr>";
                    //     //echo '<td>'.$element->children(0)->plaintext.'</td>';
                    //     echo ''.$element->children(1).'';
                    //     echo ''.$element->children(2).'';
                    //     echo ''.$element->children(3).'';
                    //     echo ''.$element->children(4).'';
                    // echo "</tr>";
                    echo $element->children(0);
                }
                echo "</table></div>";
                $i++;
            }


            $html1 = file_get_html('http://livefootballvideo.com/competitions/premier-league/page/2');
            foreach($html1->find('div[class=listmatch] ul li') as $element)
            {

                $LeagueName = $element->children(1)->plaintext;
                $StartTime = $element->children(2)->children(0)->plaintext;
                $EndTime = $element->children(2)->children(1)->plaintext;
                $MatchDate = $element->children(2)->children(2)->plaintext;
                $HomeTeam = $element->children(3)->plaintext;
                $AwayTeam = $element->children(5)->plaintext;
                $StreamingLinks = $element->children(6)->children(0)->getAttribute('href');
                
                echo "
                <div class=\"panel panel-default\">
                <div class=\"panel-heading\">
                <span class=\"text-center\"><strong>
                        <h5><b><font color=\"darkgreen\">".$LeagueName."</font></b>
                         ~ <mark><b>".$HomeTeam." - ".$AwayTeam."</b></mark> ~ <font color=\"darkblue\"><b>".$MatchDate."</b></font>~ <b>".$StartTime."-".$EndTime."
                        </b></h5>
                    </strong></span>
                    </div>

                    <div class=\"row\" style=\"padding:4px\"></div>";

                echo "<table width=\"100%\" class=\"table table-hover table-stripped dt-responsive\" id=\"example".$i."\" cellspacing=\"0\">
                <thead>
                    <tr><th></th><th>Channel</th> <th>Language</th> <th>Bitrate</th> <th>Link</th> </tr>
                </thead>";

                $streamings = file_get_html($StreamingLinks);

                foreach($streamings->find('div[class=single] div[id=livelist] table[class=streamtable] tbody') as $element)
                { 
                    //print_r($element);
                    // echo "<tr>";
                    //     //echo '<td>'.$element->children(0)->plaintext.'</td>';
                    //     echo ''.$element->children(1).'';
                    //     echo ''.$element->children(2).'';
                    //     echo ''.$element->children(3).'';
                    //     echo ''.$element->children(4).'';
                    // echo "</tr>";
                    echo $element->children(0);
                }
                echo "</table></div>";
                $i++;
            }


            $html2 = file_get_html('http://livefootballvideo.com/competitions/la-liga');
            foreach($html2->find('div[class=listmatch] ul li') as $element)
            {

                $LeagueName = $element->children(1)->plaintext;
                $StartTime = $element->children(2)->children(0)->plaintext;
                $EndTime = $element->children(2)->children(1)->plaintext;
                $MatchDate = $element->children(2)->children(2)->plaintext;
                $HomeTeam = $element->children(3)->plaintext;
                $AwayTeam = $element->children(5)->plaintext;
                $StreamingLinks = $element->children(6)->children(0)->getAttribute('href');
                
                echo "
                <div class=\"panel panel-default\">
                <div class=\"panel-heading\">
                <span class=\"text-center\"><strong>
                        <h5><b><font color=\"darkgreen\">".$LeagueName."</font></b>
                         ~ <mark><b>".$HomeTeam." - ".$AwayTeam."</b></mark> ~ <font color=\"darkblue\"><b>".$MatchDate."</b></font>~ <b>".$StartTime."-".$EndTime."
                        </b></h5>
                    </strong></span>
                    </div>

                    <div class=\"row\" style=\"padding:4px\"></div>";

                echo "<table width=\"100%\" class=\"table table-hover table-stripped dt-responsive\" id=\"example".$i."\" cellspacing=\"0\">
                <thead>
                    <tr><th></th><th>Channel</th> <th>Language</th> <th>Bitrate</th> <th>Link</th> </tr>
                </thead>";

                $streamings = file_get_html($StreamingLinks);

                foreach($streamings->find('div[class=single] div[id=livelist] table[class=streamtable] tbody') as $element)
                { 
                    //print_r($element);
                    // echo "<tr>";
                    //     //echo '<td>'.$element->children(0)->plaintext.'</td>';
                    //     echo ''.$element->children(1).'';
                    //     echo ''.$element->children(2).'';
                    //     echo ''.$element->children(3).'';
                    //     echo ''.$element->children(4).'';
                    // echo "</tr>";
                    echo $element->children(0);
                }
                echo "</table></div>";
                $i++;
            }


            $html3 = file_get_html('http://livefootballvideo.com/competitions/la-liga/page/2');
            foreach($html3->find('div[class=listmatch] ul li') as $element)
            {

                $LeagueName = $element->children(1)->plaintext;
                $StartTime = $element->children(2)->children(0)->plaintext;
                $EndTime = $element->children(2)->children(1)->plaintext;
                $MatchDate = $element->children(2)->children(2)->plaintext;
                $HomeTeam = $element->children(3)->plaintext;
                $AwayTeam = $element->children(5)->plaintext;
                $StreamingLinks = $element->children(6)->children(0)->getAttribute('href');
                
                echo "
                <div class=\"panel panel-default\">
                <div class=\"panel-heading\">
                <span class=\"text-center\"><strong>
                        <h5><b><font color=\"darkgreen\">".$LeagueName."</font></b>
                         ~ <mark><b>".$HomeTeam." - ".$AwayTeam."</b></mark> ~ <font color=\"darkblue\"><b>".$MatchDate."</b></font>~ <b>".$StartTime."-".$EndTime."
                        </b></h5>
                    </strong></span>
                    </div>

                    <div class=\"row\" style=\"padding:4px\"></div>";

                echo "<table width=\"100%\" class=\"table table-hover table-stripped dt-responsive\" id=\"example".$i."\" cellspacing=\"0\">
                <thead>
                    <tr><th></th><th>Channel</th> <th>Language</th> <th>Bitrate</th> <th>Link</th> </tr>
                </thead>";

                $streamings = file_get_html($StreamingLinks);

                foreach($streamings->find('div[class=single] div[id=livelist] table[class=streamtable] tbody') as $element)
                { 
                    //print_r($element);
                    // echo "<tr>";
                    //     //echo '<td>'.$element->children(0)->plaintext.'</td>';
                    //     echo ''.$element->children(1).'';
                    //     echo ''.$element->children(2).'';
                    //     echo ''.$element->children(3).'';
                    //     echo ''.$element->children(4).'';
                    // echo "</tr>";
                    echo $element->children(0);
                }
                echo "</table></div>";
                $i++;
            }


            $html4 = file_get_html('http://livefootballvideo.com/competitions/uefa-champions-league');
            foreach($html4->find('div[class=listmatch] ul li') as $element)
            {

                $LeagueName = $element->children(1)->plaintext;
                $StartTime = $element->children(2)->children(0)->plaintext;
                $EndTime = $element->children(2)->children(1)->plaintext;
                $MatchDate = $element->children(2)->children(2)->plaintext;
                $HomeTeam = $element->children(3)->plaintext;
                $AwayTeam = $element->children(5)->plaintext;
                $StreamingLinks = $element->children(6)->children(0)->getAttribute('href');
                
                echo "
                <div class=\"panel panel-default\">
                <div class=\"panel-heading\">
                <span class=\"text-center\"><strong>
                        <h5><b><font color=\"darkgreen\">".$LeagueName."</font></b>
                         ~ <mark><b>".$HomeTeam." - ".$AwayTeam."</b></mark> ~ <font color=\"darkblue\"><b>".$MatchDate."</b></font>~ <b>".$StartTime."-".$EndTime."
                        </b></h5>
                    </strong></span>
                    </div>

                    <div class=\"row\" style=\"padding:4px\"></div>";

                echo "<table width=\"100%\" class=\"table table-hover table-stripped dt-responsive\" id=\"example".$i."\" cellspacing=\"0\">
                <thead>
                    <tr><th></th><th>Channel</th> <th>Language</th> <th>Bitrate</th> <th>Link</th> </tr>
                </thead>";

                $streamings = file_get_html($StreamingLinks);

                foreach($streamings->find('div[class=single] div[id=livelist] table[class=streamtable] tbody') as $element)
                { 
                    //print_r($element);
                    // echo "<tr>";
                    //     //echo '<td>'.$element->children(0)->plaintext.'</td>';
                    //     echo ''.$element->children(1).'';
                    //     echo ''.$element->children(2).'';
                    //     echo ''.$element->children(3).'';
                    //     echo ''.$element->children(4).'';
                    // echo "</tr>";
                    echo $element->children(0);
                }
                echo "</table></div>";
                $i++;
            }


            $html5 = file_get_html('http://livefootballvideo.com/competitions/uefa-europa-league');
            foreach($html5->find('div[class=listmatch] ul li') as $element)
            {

                $LeagueName = $element->children(1)->plaintext;
                $StartTime = $element->children(2)->children(0)->plaintext;
                $EndTime = $element->children(2)->children(1)->plaintext;
                $MatchDate = $element->children(2)->children(2)->plaintext;
                $HomeTeam = $element->children(3)->plaintext;
                $AwayTeam = $element->children(5)->plaintext;
                $StreamingLinks = $element->children(6)->children(0)->getAttribute('href');
                
                echo "
                <div class=\"panel panel-default\">
                <div class=\"panel-heading\">
                <span class=\"text-center\"><strong>
                        <h5><b><font color=\"darkgreen\">".$LeagueName."</font></b>
                         ~ <mark><b>".$HomeTeam." - ".$AwayTeam."</b></mark> ~ <font color=\"darkblue\"><b>".$MatchDate."</b></font>~ <b>".$StartTime."-".$EndTime."
                        </b></h5>
                    </strong></span>
                    </div>

                    <div class=\"row\" style=\"padding:4px\"></div>";

                echo "<table width=\"100%\" class=\"table table-hover table-stripped dt-responsive\" id=\"example".$i."\" cellspacing=\"0\">
                <thead>
                    <tr><th></th><th>Channel</th> <th>Language</th> <th>Bitrate</th> <th>Link</th> </tr>
                </thead>";

                $streamings = file_get_html($StreamingLinks);

                foreach($streamings->find('div[class=single] div[id=livelist] table[class=streamtable] tbody') as $element)
                { 
                    //print_r($element);
                    // echo "<tr>";
                    //     //echo '<td>'.$element->children(0)->plaintext.'</td>';
                    //     echo ''.$element->children(1).'';
                    //     echo ''.$element->children(2).'';
                    //     echo ''.$element->children(3).'';
                    //     echo ''.$element->children(4).'';
                    // echo "</tr>";
                    echo $element->children(0);
                }
                echo "</table></div>";
                $i++;
            }

            ?>
        </div>

        
        <div class="col-md-3 text-center">
            <div class="row">
                <strong>Ads to help</strong> <br />
            </div>
            <div class="row">
                <img src="http://lorempixel.com/400/400/sports"  class="img-responsive" /> <br />
                <img src="http://lorempixel.com/400/400"  class="img-responsive" /> <br />
                <img src="http://lorempixel.com/400/400/people"  class="img-responsive" /><br />
                <img src="http://lorempixel.com/400/400/transport"  class="img-responsive" />
            </div>
        </div>
    </div>
</div>

</body>
</html> 


<script>
$(document).ready(function() { 
        $('tbody tr:nth-child(1)').hide(); 
        //$('table tbody tr:last').hide(); 
    }); 

$('a[href^="http://"]').attr('target','_blank');
</script>



<?php for($j = 1; $j <= $i ; $j++) { ?>

<script>
$(document).ready(function() {

    $('#example<?php echo $j ?>').dataTable( {
        responsive: true,
        "paging":   true,
        "ordering": false,
        "info":     false,
        "iDisplayLength": 15,
        "dom": '<"container-fluid"  <"row"<"col-md-6 col-md-offset-1"f>> <"row"t><"row text-center"p>>',
        "bLengthChange": false
    } );

} );

</script>

<?php } ?>


<?php
    $fp = fopen($cachefile, 'w'); 
    fwrite($fp, ob_get_contents());

    fclose($fp);
    ob_end_flush();
?>