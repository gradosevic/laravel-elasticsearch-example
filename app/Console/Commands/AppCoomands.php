<?php

namespace App\Console\Commands;

use App\Address;
use App\ElasticSearch;
use Illuminate\Console\Command;
use AddressSeeder;

class AppCoomands extends Command
{
    private $elasticSearch;
    private $addressSeeder;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app
    {--index : Sends data from database to Elasticsearch index}
    {--seed : Clears database and seeds test data}
    {--clear : Clears database and Elasticsearch index}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'General commands used for the Address app';

    /**
     * Create a new command instance.
     *
     * @param ElasticSearch $es
     * @param AddressSeeder $as
     */
    public function __construct(ElasticSearch $es, AddressSeeder $as)
    {
        $this->elasticSearch = $es;
        $this->addressSeeder = $as;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('index')) {
            $this->indexWithElasticsearch();
        }
        else if ($this->option('seed')) {
            $this->seedData();
        }
        else if ($this->option('clear')) {
            $this->clear();
        }
    }

    /**
     * Syncs data from database with ElasticSearch
     */
    private function indexWithElasticsearch(){
        $addresses = Address::all();
        foreach($addresses as $address){
            $this->elasticSearch->indexAddress($address);
        }
    }

    /**
     * Runs migrations and seeds database. Clears everything
     */
    private function seedData(){
        $this->addressSeeder->run();
    }

    /**
     * Clears everything, elastic search index and addresses table
     */
    private function clear(){
        $this->elasticSearch->deleteAll();
        Address::query()->truncate();
    }
}
