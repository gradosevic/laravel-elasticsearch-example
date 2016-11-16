<?php

namespace App;

use Elasticsearch\ClientBuilder;
use App\Address as AppAddress;
use Elasticsearch\Client;

/**
 * Elastic search for addresses
 * Class ElasticSearch
 * @package App
 */
class ElasticSearch
{
    /**
     * @var Client
     */
    private $client;

    const INDEX = 'addresses';
    const TYPE = 'address';

    public function __construct()
    {
        $this->createClient();
    }

    /**
     * Sends address to Elasticsearch
     * @param Address $address
     * @return array
     */
    public function indexAddress(AppAddress $address)
    {
        return $this->index($address->toArray(), $address->id);
    }

    public function index($data = [], $id)
    {
        return $this->client->index([
            'body' => $data,
            'index' => self::INDEX,
            'type' => self::TYPE,
            'id' => $id,
        ]);
    }

    /**
     * Returns all documents
     * @return array
     */
    public function getAll()
    {
        try {
            $data = $this->wildcardSearch([
                'zip' => '*'
            ])['hits']['hits'];
            return $data;
        } catch (\Exception $e) {
        }
        return [];
    }

    /**
     * Gets a document by ID
     * @param null $id
     * @return array
     */
    public function getById($id = NULL)
    {
        return $this->client->get([
            'index' => self::INDEX,
            'type' => self::TYPE,
            'id' => $id
        ]);
    }

    //TODO: Not the best way to delete. Check what API method should be used instead
    /**
     * Deletes all records from Elasticsearch
     */
    public function deleteAll()
    {
        $hits = $this->getAll();
        try {
            foreach ($hits as $hit) {
                $this->deleteById($hit['_id']);
            }
        } catch (\Exception $e) {
        }
    }


    /*
     * Deletes a particular record in Elasticsearch by provided id
     */
    public function deleteById($id = NULL)
    {
        $this->client->delete([
            'index' => self::INDEX,
            'type' => self::TYPE,
            'id' => $id
        ]);
    }

    /**
     * Receives a key, value pairs of search query
     * @param array $query
     * @return mixed
     */
    public function wildcardSearch($query = [])
    {
        return $this->search([
            'wildcard' => $query
        ]);
    }

    /**
     * Search any fields of the address
     * @param $searchTerm
     * @return mixed
     */
    public function multiSearch($searchTerm)
    {
        return $this->search([
            "query_string" => [
                "query" => "*" . $searchTerm . "*",
                "fields" => [
                    "address_line_1",
                    "address_line_2",
                    "city",
                    "zip",
                    "country",
                ]
            ]
        ]);
    }

    /**
     * Receives body of the Elasticsearch query
     * @param array $query
     * @return mixed
     */
    public function search($query = [])
    {
        return $this->client->search([
            'index' => self::INDEX,
            'type' => self::TYPE,
            'from' => 0,
            'size' => 100,
            'body' => [
                'query' => $query
            ]
        ]);
    }

    #region PRIVATE METHODS

    /**
     * Creates ES client
     */
    private function createClient()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([
                $this->buildHostUrl()
            ])
            ->build();
    }

    /**
     * Creates Elasticsearch host url with credentials
     * @return string
     */
    private function buildHostUrl()
    {
        return 'http://' . env('ELASTIC_UN') . ":" . env('ELASTIC_PW') . '@' . env('ELASTIC_HOST');
    }

    #endregion
}