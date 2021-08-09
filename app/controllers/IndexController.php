<?php
declare(strict_types=1);

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class IndexController extends ControllerBase
{

    // Set public variables
    public $pageUrl1;
    public $pageUrl2;
    public $pageUrl3;
    public $pageUrl4;
    public $pageUrl5;
    public $pageContent1;
    public $pageContent2;
    public $pageContent3;
    public $pageContent4;
    public $pageContent5;

    // indexAction
    public function indexAction()
    {
        // nothing to see here
    }

    // file_get_contents function
    public function fileGetContents($url) 
    {
        $pageContent = file_get_contents($url);
        return $pageContent;
    }

    // Get Page Title
    public function getTitle($url) 
    {
        $str = file_get_contents($url);
        if(strlen($str)>0){
            $str = trim(preg_replace('/\s+/', ' ', $str)); 
            preg_match("/\<title\>(.*)\<\/title\>/i",$str,$title); 
            return $title[1];
        }
    }

    // Get HTTP Response Code
    public function getResponseCode($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    // Array Needle Function
    // UNUSED BUT KEPT HERE FOR REFERENCE
    /* 
    public function substrCountArray( $haystack, $needle ) {
         $count = 0;
         foreach ($needle as $substring) {
              $count += substr_count( $haystack, $substring);
         }
         return $count;
    } */

    // Get Internal Domain Unique Links
    public function countUniqueInternalLinks( $pageContent ) {

        // Grab live page html content
        $myhtml = <<<EOF
        $pageContent
EOF;

        // Create a new DOM Document to hold page structure
        $doc = new DOMDocument();
        $doc->loadHTML($myhtml);

        $tags = $doc->getElementsByTagName('a');

        // Create an empty array to hold the results
        $array = array(); 

        // Store each value in an array
        foreach ($tags as $tag) { 

            // Add it to the array if it's an internal link 
            if (
                $tag->getAttribute('href') == "/" || 
                substr($tag->getAttribute('href'), 0, 1) == "/" || 
                substr($tag->getAttribute('href'), 0, 23) == "https://agencyanalytics" || 
                substr($tag->getAttribute('href'), 0, 27) == "https://www.agencyanalytics" || 
                substr($tag->getAttribute('href'), 0, 26) == "http://www.agencyanalytics" || 
                substr($tag->getAttribute('href'), 0, 22) == "http://agencyanalytics" 
            ){
                $array[] = $tag->getAttribute('href'); 

            // don't add it to the array 
            } else {
                // nothing to see here
            }
        } 

        // print_r(array_unique($array)); (to print the array for reference -- commented out)

        // count unique elements in the array 
        $uniq = count(array_unique($array));
        return $uniq;
    }

    // Get External Domain Unique Links
    public function countUniqueExternalLinks( $pageContent ) {

        // Grab live page html content
        $myhtml = <<<EOF
        $pageContent
EOF;

        // Create a new DOM Document to hold page structure
        $doc = new DOMDocument();
        $doc->loadHTML($myhtml);

        $tags = $doc->getElementsByTagName('a');

        // Create an empty array to hold the results
        $array = array(); 

        // Store each value in an array
        foreach ($tags as $tag) { 

            // Add it to the array if it's an internal link 
            if (
                $tag->getAttribute('href') != "/" && 
                substr($tag->getAttribute('href'), 0, 1) != "/" && 
                substr($tag->getAttribute('href'), 0, 23) != "https://agencyanalytics" && 
                substr($tag->getAttribute('href'), 0, 27) != "https://www.agencyanalytics" && 
                substr($tag->getAttribute('href'), 0, 26) != "http://www.agencyanalytics" && 
                substr($tag->getAttribute('href'), 0, 22) != "http://agencyanalytics" 
            ){
                $array[] = $tag->getAttribute('href'); 

            // don't add it to the array 
            } else {
                // nothing to see here
            }
        } 

        // print_r(array_unique($array)); (to print the array for reference -- commented out)

        // count unique elements in the array 
        $uniq = count(array_unique($array));
        return $uniq;
    }

    // Get page Word Count
    public function getWordCount( $pageContent ){ 

        // Grab live page html content
        $myhtml = <<<EOF
        $pageContent
EOF;

        // Create a new DOM Document to hold page structure
        $doc = new DOMDocument(); 

        // load html content
        $doc->loadHTML($myhtml); 

        $htm=$doc->saveHTML(); 

        $htm=strip_tags($htm); 
        $htm=str_word_count($htm); 

        // Calculate word count of the page (excluding tags)
        return $htm; 
    }

    // Calculate single page load time
    public function singlePageLoadTime($pageContent){ 

        // Create a new DOM Document to hold page structure
        $doc = new DOMDocument();

        // Grab the current time
        $start_time = microtime(TRUE); 

        // Specify the html content
        $myhtml = <<<EOF
        $pageContent
EOF;

        // load the html
        $doc->loadHTML($myhtml);

        // Calculate the end time
        $end_time = microtime(TRUE);

        // Calculate the time taken
        $time_taken =($end_time - $start_time)*1000;
        $time_taken = round($time_taken,5);

        // Round it to 2 decimals
        return round($time_taken, 2);
    }

    // Calculate all pages average load time
    // UNUSED, BUT KEPT HERE FOR REFERENCE
    /* 
    public function averagePageLoadTime(){ 

        // Set urls to scan
        $pageUrl1 = 'https://agencyanalytics.com/feature/seo-tools'; // seo tools page
        $pageUrl2 = 'https://agencyanalytics.com/pricing'; // pricing page
        $pageUrl3 = 'https://agencyanalytics.com/feature/automated-marketing-reports'; // automated marketing reports
        $pageUrl4 = 'https://agencyanalytics.com/feature/white-label'; // white label
        $pageUrl5 = 'https://agencyanalytics.com/feature/linkedin-dashboard'; // linkedin dashboard

        // Use file_get_contents to gather data from each page
        $pageContent1 = file_get_contents($pageUrl1); 
        $pageContent2 = file_get_contents($pageUrl2); 
        $pageContent3 = file_get_contents($pageUrl3); 
        $pageContent4 = file_get_contents($pageUrl4); 
        $pageContent5 = file_get_contents($pageUrl5); 

        // Create an empty array to hold the results
        $arrayLoadTimes = array(); 

        // Page 1 

        // Create a new DOM Document to hold page structure
        $doc1 = new DOMDocument();

        // Grab the current time
        $start_time1 = microtime(TRUE); 

        // Specify the html content
        $myhtml1 = <<<EOF
        $pageContent1
EOF;

        // load the html
        $doc1->loadHTML($myhtml1);

        // Calculate the end time
        $end_time1 = microtime(TRUE);

        // Calculate the time taken
        $time_taken1 =($end_time1 - $start_time1)*1000;
        $time_taken1 = round($time_taken1,5);

        // Add the load time to the array 
        $arrayLoadTimes[] = $time_taken1; 

        // Page 2 

        // Create a new DOM Document to hold page structure
        $doc2 = new DOMDocument();

        // Grab the current time
        $start_time2 = microtime(TRUE); 

        // Specify the html content
        $myhtml2 = <<<EOF
        $pageContent2
EOF;

        // Load the html
        $doc2->loadHTML($myhtml2);

        // Calculate the end time
        $end_time2 = microtime(TRUE); 

        // Calculate the time taken
        $time_taken2 =($end_time2 - $start_time2)*1000;
        $time_taken2 = round($time_taken2,5);

        // Add the load time to the array 
        $arrayLoadTimes[] = $time_taken2; 

        // Page 3 

        // Create a new DOM Document to hold page structure
        $doc3 = new DOMDocument();

        // Grab the current time
        $start_time3 = microtime(TRUE); 

        // Load the 3rd page html content
        $myhtml3 = <<<EOF
        $pageContent3
EOF;

        // Load the html content
        $doc3->loadHTML($myhtml3);

        // Calculate the end time
        $end_time3 = microtime(TRUE); 

        // Calculate the time taken
        $time_taken3 =($end_time3 - $start_time3)*1000;
        $time_taken3 = round($time_taken3,5);

        // Add the load time to the array 
        $arrayLoadTimes[] = $time_taken3; 

        // Page 4 

        // Create a new DOM Document to hold page structure
        $doc4 = new DOMDocument();

        // Grab the current time
        $start_time4 = microtime(TRUE); 

        // Load the 4th page html content
        $myhtml4 = <<<EOF
        $pageContent4
EOF;

        // Load the html
        $doc4->loadHTML($myhtml4);

        // Calculate the end time
        $end_time4 = microtime(TRUE); 

        // Calculate the time taken
        $time_taken4 =($end_time4 - $start_time4)*1000;
        $time_taken4 = round($time_taken4,5);

        // Add the load time to the array 
        $arrayLoadTimes[] = $time_taken4; 

        // Page 5 

        // Create a new DOM Document to hold page structure
        $doc5 = new DOMDocument();

        // Grab the current time
        $start_time5 = microtime(TRUE); 

        // Load the 5th page html content
        $myhtml5 = <<<EOF
        $pageContent5
EOF;

        // Load the html
        $doc5->loadHTML($myhtml5);

        // Calculate the end time
        $end_time5 = microtime(TRUE); 

        // Calculate the time taken
        $time_taken5 =($end_time5 - $start_time5)*1000;
        $time_taken5 = round($time_taken5,5);

        // Add the load time to the array 
        $arrayLoadTimes[] = $time_taken5; 

        // Calculate the load time average, excluding empties 
        $a = array_filter($arrayLoadTimes);
        $average = array_sum($a)/count($a);

        return round($average, 2);
    } */


    // Get unique images on a page
    public function countUniqueImages( $pageContent ) {

        // Grab live page html content
        $myhtml = <<<EOF
        $pageContent
EOF;

        // Create a new DOM Document to hold page structure
        $doc = new DOMDocument();
        $doc->loadHTML($myhtml);

        $tags = $doc->getElementsByTagName('img');

        // Create an empty array to hold the results
        $array = array(); 

        // Store each value in an array
        foreach ($tags as $tag) { 
            $array[] = $tag->getAttribute('src'); 
        } 

        // print_r(array_unique($array)); (to print the array for reference -- commented out)

        // count unique elements in the array 
        $uniq = count(array_unique($array));
        return $uniq;
    }


}

