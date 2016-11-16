<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory;
use App\Address;
use App\ElasticSearch;

class AddressTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();
        $this->truncateAddresses();
    }

    private function truncateAddresses(){
        Address::query()->truncate();
    }

    private function seedAddresses(){
        (new AddressSeeder())->run();
    }


    /**
     * @group address
     */
    public function testCreateAddressFromModelFactory()
    {
        $address = factory(App\Address::class)->make();

        $this->assertNotEmpty($address->address_line_1);
        $this->assertNotEmpty($address->address_line_2);
        $this->assertNotEmpty($address->zip);
        $this->assertNotEmpty($address->city);
        $this->assertNotEmpty($address->country);
    }

    /**
     * Test address seeder creates addresses
     * @group address
     */
    public function testAddressSeeder()
    {
        $this->assertEquals(0, Address::all()->count());
        $this->seedAddresses();
        $this->assertEquals(15, Address::all()->count());
    }

    /**
     * Test address seeder creates addresses
     * @group address
     */
    public function testAddressSeedCommand()
    {
        $this->assertEquals(0, Address::all()->count());
        $this->artisan('app', [
            '--seed' => true
        ]);
        $this->assertEquals(15, Address::all()->count());

    }

    /**
     * Test address clear command
     * @group address
     */
    public function testAddressClearCommand()
    {
        $es = new ElasticSearch();
        $es->indexAddress(factory(Address::class)->create());
        sleep(1);
        $this->artisan('app', [
            '--clear' => true
        ]);
        sleep(1);
        $records = $es->getAll();
        $this->assertEquals(0, sizeof($records));
        $this->assertEquals(0, Address::all()->count());
    }


    /**
     * @group address
     */
    public function testAddressIndexCommand()
    {
        $this->seedAddresses();
        $es = new ElasticSearch();

        $es->deleteAll();
        sleep(1);
        $records = $es->getAll();
        $this->assertEquals(0, sizeof($records));

        $this->artisan('app', [
            '--index' => true
        ]);
        sleep(1);
        $records = $es->getAll();
        $this->assertEquals(15, sizeof($records));
    }
}
