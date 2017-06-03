# Oxide
Simple and lightweight cURL-based HTTP client for PHP 7.1

### Requirements
Oxide requires PHP 7.1 and php-curl extension

### Basic usage
```php
    <?php declare(strict_types=1);
    
    use Folour\Oxide\Oxide;
    
    $oxide = new Oxide();
    $response = $oxide->get('https://google.com', ['q' => 'php 7.1']);
    
    //get response body
    echo $response->body(); //Or echo $response;
    //get response code
    echo $response->code();
    //get response headers
    var_dump($response->headers());
```

### Configure
```php
    <?php declare(strict_types=1);
    
    use Folour\Oxide\Oxide;
    
    $oxide = new Oxide();
    $oxide
        ->setHeaders([
            'Referer' => 'http://local.dev'
        ])
        ->setCookies([
            'cookie' => 'value'
        ])
        ->setProxy('user:pwd@127.0.0.1:8080');
    
    $response = $oxide->post('http://httpbin.org/post', ['test']);
```

### More HTTP request methods
```php
    <?php declare(strict_types=1);
    
    use Folour\Oxide\Oxide;
    
    $oxide = new Oxide();
    
    echo $oxide->get('http://httpbin.org/get', ['key' => 'value']);
    echo $oxide->head('http://httpbin.org/get', ['key' => 'value']);
    echo $oxide->post('http://httpbin.org/post', ['key' => 'value']);
    echo $oxide->put('http://httpbin.org/put', ['key' => 'value']);
    echo $oxide->delete('http://httpbin.org/delete', ['key' => 'value']);
```