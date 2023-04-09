<?php

namespace Belyys7\ElasticsearchItsEasy;

use Elasticsearch\ClientBuilder;

class ElasticSearchBase
{
    protected $client;

    const PAGE_CONTENT_BATCH = 1000;
    const MAX_RESULT_WINDOW = 5000000;

    const RETRY_ON_CONFLICT = 20;

    public function __construct($ip, $port)
    {
        $this->client = ClientBuilder::create()
            ->setHosts(["{$ip}:{$port}"])
            ->build();
    }

    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function createIndex($params)
    {
        try {
            return $this->client->indices()->create($params);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $index
     * @param $type
     * @param $body
     * @return array|callable
     * @throws \Exception
     */
    public function addDocument($index, $type, $body)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'body' => $body,
        ];

        try {
            return $this->client->index($params);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $index
     * @param $id
     * @param $body
     * @return array|callable
     * @throws \Exception
     */
    public function updateDocument($index, $id, $body)
    {
        $params = [
            'index' => $index,
            'id' => $id,
            'retry_on_conflict' => self::RETRY_ON_CONFLICT,
            'body' => [
                'doc' => $body,
            ],
        ];

        try {
            return $this->client->update($params);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $index
     * @param $id
     * @return array|callable
     * @throws \Exception
     */
    public function deleteDocument($index, $id)
    {
        $params = [
            'index' => $index,
            'id'    => $id,
        ];

        try {
            return $this->client->delete($params);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $index
     * @param $searchField
     * @param $searchFieldValue
     * @param $updateField
     * @param $updateFieldValue
     * @return void
     * @throws \Exception
     */
    public function updateField($index, $searchField, $searchFieldValue, $updateField, $updateFieldValue = null)
    {
        if (empty($searchField) || empty($searchFieldValue) || empty($updateField)) {
            throw new \Exception('Invalid parameters');
        }

        if (is_null($updateFieldValue)) {
            $updateFieldValue = "''";
        }
        elseif (is_string($updateFieldValue)) {
            $updateFieldValue = "'{$updateFieldValue}'";
        }

        $params = [
            'index' => $index,
            'type' => '_doc',
            'body' => [
                'query' => [
                    'term' => [
                        $searchField => $searchFieldValue,
                    ],
                ],
                'script' => "ctx._source.{$updateField} = {$updateFieldValue}"
            ],
        ];

        try {
            $this->client->updateByQuery($params);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}