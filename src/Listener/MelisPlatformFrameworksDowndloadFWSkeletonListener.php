<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCore\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class MelisPlatformFrameworksDowndloadFWSkeletonListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{

    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();

        $callBackHandler = $sharedEvents->attach(
            'MelisCore',
            [
                'melis_core_auth_login_ok',
            ],
            function ($e) {

                $sm = $e->getTarget()->getServiceLocator();
                $table = $sm->get('MelisUserConnectionDate');
                $params = $e->getParams();
                $userTable = $sm->get('MelisCoreTableUser');
                $authSvc = $sm->get('MelisCoreAuth');

                // update the session last_login_date
                $user = $authSvc->getStorage()->read();

                if (!empty($user)) {
                    $user->usr_last_login_date = $params['login_date'];
                    $isOnline = (bool) $user->usr_is_online;

                    if (!$isOnline) {
                        $table->save([
                            'usrcd_usr_login' => $params['usr_id'],
                            'usrcd_last_login_date' => $params['login_date'],
                            'usrcd_last_connection_time' => $params['login_date'],
                        ]);
                    }

                    $userTable->save([
                        'usr_last_login_date' => $params['login_date'],
                        'usr_is_online' => true,
                    ], $params['usr_id']);

                }
            },
            -10000);

        $this->listeners[] = $callBackHandler;
    }
}
