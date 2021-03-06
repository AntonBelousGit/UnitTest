<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\CurrencyService;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_convert_usd_to_eur()
    {
        $amount_in_usd = 100;
        $this->assertEquals(89, (new CurrencyService())->convert($amount_in_usd, 'usd','eur'));
    }

    public function test_convert_gbp_to_eur()
    {
        $amount_in_gbp = 100;
        $this->assertEquals(0, (new CurrencyService())->convert($amount_in_gbp, 'gbp','eur'));
    }

    public function test_convert_usd_to_gbp()
    {
        $amount_in_usd = 100;
        $this->assertEquals(123, (new CurrencyService())->convert($amount_in_usd, 'usd','gbp'));
    }


}
