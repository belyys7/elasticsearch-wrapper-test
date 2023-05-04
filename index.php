<?php

require_once dirname(__DIR__) . '/public/vendor/autoload.php';

use App\StaffModelSearch;

// Reindex

// Соберите данные которые будут записаны в эластику, формат локации поля location должен быть как в примере если она есть
$data = [
    [
        'user' => [
            'id' => 100,
            'email' => 'stepan21@gmail.com',
            'name' => 'Stepan',
            'age' => 21,
            'birthday' => '2001-06-15',
        ],
        'work' => [
            'position' => [
                'id' => 25,
                'name' => 'php developer',
            ],
            'skills' => [
                [
                    'id' => 36,
                    'name' => 'php'
                ],
                [
                    'id' => 40,
                    'name' => 'mysql'
                ],
                [
                    'id' => 56,
                    'name' => 'js'
                ],
            ],
            'salary' => 4000
        ],
        'location' => [
            'lat' => 50.445077,
            'lon' => 30.521215
        ],
    ],
    [
        'user' => [
            'id' => 101,
            'email' => 'luidji@gmail.com',
            'name' => 'Voland',
            'age' => 29,
            'birthday' => '1999-03-20',
        ],
        'work' => [
            'position' => [
                'id' => 12,
                'name' => 'js developer',
            ],
            'skills' => [
                [
                    'id' => 56,
                    'name' => 'js'
                ],
                [
                    'id' => 70,
                    'name' => 'mongodb'
                ],
                [
                    'id' => 1,
                    'name' => 'html'
                ],
                [
                    'id' => 2,
                    'name' => 'css'
                ],
            ],
            'salary' => 2700
        ],
        'location' => [
            'lat' => 47.454589,
            'lon' => 32.915673
        ],
    ],
];

try {

    $staffModelSearch = new StaffModelSearch('es01', '9200', 'staff_search');

//    if ($data) {
//        $staffModelSearch->reCreateIndex();
//        foreach ($data as $item) {
//            $staffModelSearch->addDocument($item, 'user.id', $item['user']['id']);
//        }
//    }

    // Входные данные для поиска (в класе StaffModelSearch нужно настроить метод setRules определив групы поиска и правила пример смотреть в классе ModelSearchBase)


    // LIST
    $params = [
//        'userId' => 100, // GROUP_MUST -> RULE_EQUAL
//        'workPositionId' => 25, // GROUP_FILTER -> RULE_EQUAL
//        'userEmail' => 'stepan21@gmail.com', // GROUP_SHOULD -> RULE_LIKE
//        'userName' => 'Stepan', // GROUP_SHOULD -> RULE_LIKE
//        'workSkillsId' => [36, 40], // GROUP_SHOULD -> RULE_IN
//        'userAge' => ['min' => 22, 'max' => 65], // GROUP_FILTER -> RULE_RANGE
//        'workSalary' => ['min' => 500, 'max' => 5000], // GROUP_FILTER -> RULE_RANGE
        // GROUP_LOCATION -> RULE_GEO
        'location' => [
            'point' => [
                'lat' => 48.454589,
                'lon' => 33.915673,
                'distance' => 100000
            ],
            'rectangle' => [
                'topLeftLat' => 55.710929,
                'topLeftLng' => 14.090451,
                'bottomRightLat' => 41.830140,
                'bottomRightLng' => 41.802791
            ],
        ],
        'page' => 1, // Default field
        'limit' => 20, // Default field
    ];

    //$staffModelSearch->enableFixLimitResult(50);

    $overWriteRules = [
        'must' => [
            [
                'term' => [
                    'user.name.keyword' => 'Stepan',
                ],
            ],
            //...
        ],
        //...
    ];

    $staffModelSearch->setOverWriteRules($overWriteRules);

    $result = $staffModelSearch->searchList($params);

    echo "<pre>";
    print_r($result);
    exit;


    /*
    // MAP
    $params = [
        'userId' => 100, // GROUP_MUST -> RULE_EQUAL
        'workPositionId' => 25, // GROUP_FILTER -> RULE_EQUAL
        'userEmail' => 'stepan21@gmail.com', // GROUP_SHOULD -> RULE_LIKE
        'userName' => 'Stepan', // GROUP_SHOULD -> RULE_LIKE
        'workSkillsId' => [36, 40], // GROUP_SHOULD -> RULE_IN
        'userAge' => ['min' => 18, 'max' => 65], // GROUP_FILTER -> RULE_RANGE_NUMBER
        'workSalary' => ['min' => 500, 'max' => 5000], // GROUP_FILTER -> RULE_RANGE_NUMBER
        'birthday' => ['from' => '1990-01-01', 'to' => '2025-12-31'], // GROUP_FILTER -> RULE_RANGE_DATE
        'location' => [
            'point' => [
                'lat' => 48.454589,
                'lon' => 33.915673,
                'distance' => 100000
            ],
            'rectangle' => [
                'topLeftLat' => 55.710929,
                'topLeftLng' => 14.090451,
                'bottomRightLat' => 41.830140,
                'bottomRightLng' => 41.802791
            ],
            'clustering' => true,
            'zoom' => 1,
        ],
    ];

    $result = $staffModelSearch->searchMap($params);

    echo "<pre>";
    print_r($result);
    exit;
    */

} catch (\Exception $e) {
    echo $e->getMessage();
}