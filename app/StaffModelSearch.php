<?php

namespace App;

use Belyys7\ElasticsearchItsEasy\ModelSearchBase;

class StaffModelSearch extends ModelSearchBase
{
    public function __construct($ip, $port, $indexSearch)
    {
        parent::__construct($ip, $port, $indexSearch);
    }

    /**
     * @return void
     */
    public function setRules()
    {
        $this->rules = [
            self::GROUP_MUST => [
                self::RULE_EQUAL => [
                    'userId' => 'user.id',
                ],
            ],
            self::GROUP_SHOULD => [
                self::RULE_LIKE => [
                    'userEmail' => 'user.email',
                    'userName' => 'user.name',
                ],
                self::RULE_IN => [
                    'workSkillsId' => 'work.skills.id',
                ],
            ],
            self::GROUP_FILTER => [
                self::RULE_EQUAL => [
                    'workPositionId' => 'work.position.id'
                ],
                self::RULE_RANGE => [
                    'userAge' => 'user.age',
                    'workSalary' => 'work.salary',
                ],
            ],
            self::GROUP_LOCATION => [
                'location' => self::SORT_DESC,
            ],
        ];
    }

    /**
     * @return void
     */
    public function setSort()
    {
        $this->sort = [
            'user.id' => self::SORT_DESC,
        ];
    }

}