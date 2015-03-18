<?php 

include('simple_html_dom.php');

function grabDataFromWeb($URL = "http://livefootballvideo.com/streaming")
{         
    $html = file_get_html($URL);

    if($html != null)
    {
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
    }
}

?>