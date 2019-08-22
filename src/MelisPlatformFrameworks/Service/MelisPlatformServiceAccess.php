<?php

namespace MelisPlatformFrameworks\Service;

Abstract class MelisPlatformServiceAccess extends MelisPlatformService
{
    abstract function getService($serviceName);
}