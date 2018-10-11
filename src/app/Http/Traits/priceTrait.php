<?php
namespace App\Http\Traits;

trait priceTrait {

    public function setPriceAttribute($value) {
        $this->attributes["price"] = $this->comma2dot($value);
    }

    public function setStartPriceAttribute($value) {
        $this->attributes["start_price"] = $this->comma2dot($value);
    }

    public function setEndPriceAttribute($value) {
        $this->attributes["end_price"] = $this->comma2dot($value);
    }

    public function setBidLimitAttribute($value) {
        $this->attributes["bid_limit"] = $this->comma2dot($value);
    }

    public function comma2dot($value) {
        return str_replace(",",".",$value);
    }
}