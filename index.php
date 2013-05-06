<?php 
 include 'class.BingSearch.php';
 $bing = new BingSearch('Ayesh Karunaratne', 5000);
 $bing->set_fetcher('curl');
 $bing->debug_url();
 //$bing->next_page();
 $bing->debug_url();
 $bing->search();
