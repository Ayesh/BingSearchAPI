<?php 
class BingSearch {
  protected $appid = '';
  public $base_url = 'https://api.datamarket.azure.com/Bing/Search/';
  public $skip = 0;
  public $query = '';
  protected $results = 50;
  
  function __construct($search, $start = 0, $results = 50, $appid = NULL) {
    $this->query = $search;
    $this->skip = $start;
    if (!is_null($appid)) {
      $this->appid = $appid;
    }
  }
  
  protected function build_url() {
    $url = $this->base_url . 'Web?$format=json&Query=';
    $url .= urlencode("'{$this->query}'");
    $url .= "&\$top={$this->results}"; 
    if ($this->skip > 0) {
      $url .= "&\$skip={$this->skip}";
    }
    return $url;
  }
  
  function debug_url($return = FALSE) {
    if ($return) {
      return $this->build_url();
    }
    if (function_exists('dpm') && function_exists('krumo')) { 
      dpm($this->build_url()); 
    }
    elseif (function_exists('krumo')) {
      krumo($this->build_url());
    }
    else {
      print '<pre>' . $this->build_url() . '</pre>';
    }
  }
}