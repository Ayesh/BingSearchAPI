<?php 
class BingSearch {
  protected $appid = '';
  public $base_url = 'https://api.datamarket.azure.com/Bing/Search/';
  public $skip = 0;
  public $query = '';
  public $top = 50;
  protected $results = 50;
  public $fetchers = array('curl', 'file_get_contents');
  public $fetcher = 'file_get_contents';
  
  function __construct($search, $start = 0, $results = 50, $appid = NULL) {
    $this->query = $search;
    $this->skip = $start;
    $this->top = $results;
    if (!is_null($appid)) {
      $this->appid = $appid;
    }
  }
  
  function set_fetcher($method) {
    if (method_exists($this, $method) && in_array($method, $this->fetchers)) {
      $this->fetcher = $method;
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
  
  function build_context() {
    
    return $context;
  }
  
  function next_page() {
    $this->skip += $this->top;
  }
  
  protected function fetch() {
    $url = $this->build_url();
    return $this->{$this->fetcher}($url, $this->appid);
  }
  
  function search() {
    print '<pre>';
      print_r(json_decode($this->fetch()));
    print '</pre>';
  }
  
  protected function file_get_contents($url, $appid) {
    $url = $this->build_url();
    $context = stream_context_create(
      array(
        'http' => array(
          'request_fulluri' => true,       
          'header'  => "Authorization: Basic " . base64_encode($appid . ':' . $appid)
        )
      )
    );
    return file_get_contents($url, 0, $context);
  }
  
  protected function curl($url, $appid) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$appid:$appid");
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
  }

  
}