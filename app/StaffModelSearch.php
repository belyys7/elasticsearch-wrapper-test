<?php

namespace App;

use Belyys72\ElasticsearchItsEasy\ModelSearchBase;

class StaffModelSearch extends ModelSearchBase
{
    /**
     * @return void
     */
    public function setRules() : void
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
                self::RULE_RANGE_NUMBER => [
                    'userAge' => 'user.age',
                    'workSalary' => 'work.salary',
                ],
                self::RULE_RANGE_DATE => [
                    'birthday' => 'user.birthday',
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
    public function setSort() : void
    {
        $this->sort = [
            'user.id' => self::SORT_DESC,
        ];
    }

}