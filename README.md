# melis-platform-frameworks

This Module handles the execution of Third party framework and return the content as response of the 
request, it also provides a Service in order to make a request to the third party.

### Prerequisites
This module requires melisplatform/melis-core installed.
This will automatically be done when using composer.

### Installing
```
composer require melisplatform/melis-platform-frameworks
```

### Third Party Framework Execution
When executing the third party framework a config data MUST be declared in the config file in order to determine 
the application to be executed, this can be done by adding this data array on the config file:
```
'third-party-framework' => [
    'index-path' => []
],
```
'index-path' must set the path of the application index.php file inside /thirdparty in the root directory. 
For example 'laravel/public/index.php' do not includes the /thirdparty directory in the path.

### Service and implementation
This module has a service MelisPlatformService to call in order to get a response from 
third party framework. By specifying the route of the request it will execute a request and 
return the content as the result.

Example:
```
$thirdPartySrv = $this->getServiceLocator()->get('MelisPlatformService');
$thirdPartySrv->setRoute('/list');
$response = $thirdPartySrv->getContent();
```



