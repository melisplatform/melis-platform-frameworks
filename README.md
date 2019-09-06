# melis-platform-frameworks

This Module handles the execution of Third party framework and return the content as response of the 
request, it also provides a Service in order to make a request to the third party.

### Prerequisites
This module requires ``melisplatform/melis-core`` installed.
This will automatically be done when using composer.

### Installing
```
composer require melisplatform/melis-platform-frameworks
```
### Adding Third Party Frameworks
Third Party Frameworks are located at the root of Melis Platform application ``/thirdparty``, inside this directory you can 
you can place your desire Frameworks.
```
...
/module
/public
/storage
/test
/thirdparty
    /Laravel
        /app
        /bootstrap
        /config
        ...
    /Symfony
        /bin
        /config
        /public
        ...
...
```
Above is an example of Laravel and Symfony frameworks added to ``/thirdparty`` directory.
**Note:** You can decide what would be the name of the framework directory name 
and then apply it on your configuration below.

### Third Party Framework Execution
**When executing the third party framework a config data MUST be declared in the config file in order to determine 
the application to be executed**, this can be done by adding this data array on the config file:
```
'third-party-framework' => [
    'index-path' => [
        'Laravel/public/index.php'
    ]
],
```
``'index-path'`` must set the path of the application index.php file inside ``/thirdparty`` in the root directory. 
For example 'Laravel/public/index.php'. DO NOT include the ``/thirdparty`` directory in the path.

### Third Party Frameworks modification
Frameworks response MUST be modified to return the response content and the status of the request,
here's an example of Laravel framework integration as third party framework.

#### Laravel public/index.php 
At the last part of the code in ``public/index.php`` file, Laravel is generating the response by calling ``$response->send()`` method
from ``Response`` Object then after terminated the application by ``$kernel->terminate($request, $response)``, as code syntax below:
```
...

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
```

In the integration of third party framework instead of displaying the response and terminating in the index.php file,
this will return an array of data containing the content using ``getContent()`` and status ``getStatusCode()`` from ``Response`` object.
```
...

// $response->send();

// $kernel->terminate($request, $response);

return [
    'statusCode' => $response->getStatusCode(),
    'content' => $response->getContent(),
];
```
Notice the ``$response->send()`` and ``$kernel->terminate($request, $response)`` are commented 
to avoid rendering the response and terminating the application.

### Service and implementation
This module has a service ``MelisPlatformService`` to call in order to get a response from 
third party framework. By specifying the route of the request it will execute a request and 
return the content as the result.

Example:
```
$thirdPartySrv = $this->getServiceLocator()->get('MelisPlatformService');
$thirdPartySrv->setRoute('/melis/lravel-list');
$response = $thirdPartySrv->getContent();
```

## Authors
* **Melis Technology** - [www.melistechnology.com](https://www.melistechnology.com/)

See also the list of [contributors](https://github.com/melisplatform/melis-core/contributors) who participated in this project.


## License
This project is licensed under the OSL-3.0 License - see the [LICENSE.md](LICENSE.md) file for details


