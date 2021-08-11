<?php
declare(strict_types=1);

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;

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
