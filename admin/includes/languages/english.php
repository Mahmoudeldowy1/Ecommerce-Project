<?php


function lang($phrase)
{
    static  $lang = [

        //Navebar Links

        'HOME_ADMIN' =>'Home',
        'CATEGORIES' =>'Categories',
        'ITEMS'      =>'items',
        'MEMBERS'    =>'members',
        'STATISTICS' =>'statistics',
        'COMMENTS' =>'Comments',
        'DASHBOARD'       =>'dashboard'


    ];

    return $lang[$phrase];
}