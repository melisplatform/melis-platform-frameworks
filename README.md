# melis-platform-frameworks

This Module handle the execution of Third party framework and return the content as response of the 
request, this also provide a Service in order to make a request to the third party.

### Prerequisites
This module requires melisplatform/melis-core in order to have this module running.
This will automatically be done when using composer.

### Installing
```
composer require melisplatform/melis-platform-frameworks
```

### Third Party Framework Execution
In executing the Third party framework a config data MUST declared in the confige file in order to determine 
the application to be executed, this can be done by adding this data array on the config file
```
'third-party-framework' => [
    'index-path' => []
],
```
'index-path' must set the path of the application index.php file inside /thirdparty in the root directory, for example 'laravel/public/index.php'
do not include the /thirdparty directory in the path.

### Service and implementation
This module has a service MelisPlatformService to call in order to get a response from 
third party framework, by specifying the route of the request this will execute a request and 
return the content as the result

Example:
```
$thirdPartySrv = $this->getServiceLocator()->get('MelisPlatformService');
$thirdPartySrv->setRoute('/list');
$response = $thirdPartySrv->getContent();
```



