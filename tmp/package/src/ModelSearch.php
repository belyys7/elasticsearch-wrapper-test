<?php

namespace Belyys7\ElasticsearchItsEasy;

use Belyys7\ElasticsearchItsEasy\forms\FilterForm;

class ModelSearch extends ElasticSearchBase
{
    const TYPE_SEARCH = '_doc';

    private $indexSearch;

    const SORT_BY_DISTANCE = 1;

    const DISTANCE_UNTIL = 'km';

    /** @var FilterForm */
    public $form;

    public static $mappingClass;

    public function __construct($ip, $port, $indexSearch)
    {
        $this->indexSearch = $indexSearch;
        parent::__construct($ip, $port);
    }

    public  function searchMap($params = [])
    {
        if (!$this->client->indices()->exists(['index' => $this->indexSearch])) {
            throw new \Exception('No data in search');
        }

        if (!$this->validateFilter($params, FilterForm::SCENARIO_SEARCH_INFO)) {
            return false;
        }

    }

    /**
     * @param $params
     * @param $scenario
     * @return bool
     * @throws \Exception
     */
    public function validateFilter($params, $scenario)
    {
        $this->form = new FilterForm($scenario);
        $this->form->load($params);

        try {
            if (!$this->form->validate()) {
                return false;
            }
        } catch (\Exception $e) {
            throw new \Exception('Search error');
        }

        return true;
    }


    public function reindexAll()
    {
        $params = [
            'index' => $this->indexSearch,
        ];

        $existIndex = $this->client->indices()->exists($params);

        if ($existIndex) {
            $this->client->indices()->delete($params);
        }

        $params = [
            'index' => $params['index'],
            'body' => [
                'mappings' => [
                    'properties' => [
                        'location' => [
                            'type' => 'geo_point',
                        ],
                    ],
                ],
            ],
        ];

        $this->addIndex($params);

//        $users = User::find()
//            ->where(['status' => User::STATUS_ACTIVE])
//            ->all();
//
//        if ($users) {
//            /** @var User $user */
//            foreach ($users as $user) {
//                if ($user->getRole() == User::ROLE_MASTER && $user->isMasterProfileIsFull()) {
//                    Yii::$app->queue->push(
//                        new ElasticSearchJob([
//                            'master' => $user,
//                        ])
//                    );
//                }
//            }
//        }
    }

    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function addIndex($params)
    {
        return $this->createIndex($params);
    }
}