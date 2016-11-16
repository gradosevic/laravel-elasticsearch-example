<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\ElasticSearch;
use Mockery\CountValidator\Exception;


class SiteController extends Controller
{
    private $es;

    public function __construct(\App\ElasticSearch $es)
    {
        $this->es = $es;
    }

    public function home()
    {
        return view('welcome');
    }

    public function getAddresses()
    {
        return $this->getSourceData(
            $this->es->getAll()
        );
    }

    public function searchAddresses($search = '')
    {

        $data = $this->es->multiSearch($search);

        try {
            $hits = $data['hits']['hits'];
            return $this->getSourceData($hits);
        } catch (Exception $e) {

        }
        return [];
    }

    public function createNewAddress(Request $request)
    {
        $address = factory(Address::class)->create($request->all());
        $this->es->indexAddress($address);
    }

    /**
     * Get the source data only from data set
     * @param null $data
     * @return array
     */
    private function getSourceData($data = NULL)
    {
        $tmp = [];
        foreach ($data as $item) {
            $tmp[] = $item['_source'];
        }
        return $tmp;
    }
}
