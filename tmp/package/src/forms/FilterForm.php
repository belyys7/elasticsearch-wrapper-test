<?php

namespace Belyys7\ElasticsearchItsEasy\forms;

class FilterForm
{
    const SCENARIO_SEARCH_LIST = 'search_list';
    const SCENARIO_SEARCH_MAP = 'search_map';
    const SCENARIO_SEARCH_INFO = 'search_info';

    const SCENARIO_GROUP = [
        self::SCENARIO_SEARCH_LIST,
        self::SCENARIO_SEARCH_MAP,
        self::SCENARIO_SEARCH_INFO,
    ];

    const DEFAULT_RECORDS_ON_SIZE = 10;
    const ZOOM_START_CUSTOM_CLUSTER = 13;

    public $scenario;

    public $topLeftLat;
    public $topLeftLng;
    public $bottomRightLat;
    public $bottomRightLng;
    public $myLocationLat;
    public $myLocationLng;
    public $distance;
    public $zoom = null;
    public $page = 1;

    public function __construct($scenario)
    {
        $this->scenario = $scenario;
    }

    /**
     * @param $params
     * @return void
     */
    public function load($params = [])
    {
        $this->topLeftLat = $params['topLeftLat'] ?? null;
        $this->topLeftLng = $params['topLeftLng'] ?? null;
        $this->bottomRightLat = $params['bottomRightLat'] ?? null;
        $this->bottomRightLng = $params['bottomRightLng'] ?? null;
        $this->myLocationLat = $params['myLocationLat'] ?? null;
        $this->myLocationLng = $params['myLocationLng'] ?? null;
        $this->distance = $params['distance'] ?? null;
        $this->zoom = $params['zoom'] ?? null;
        $this->page = $params['page'] ?? null;
    }

    /**
     * @return true
     * @throws \Exception
     */
    public function validate()
    {
        $this->checkScenario();

        // validate fields

        return true;
    }

    /**
     * @return bool
     */
    public function distanceEnabled(): bool
    {
        return ($this->myLocationLat && $this->myLocationLng && $this->distance);
    }

    /**
     * @return void
     * @throws \Exception
     */
    private function checkScenario()
    {
        if (!$this->scenario || !in_array($this->scenario, self::SCENARIO_GROUP)) {
            throw new \Exception('Scenario is wrong');
        }
    }
}