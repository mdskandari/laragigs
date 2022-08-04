<?php

namespace App\Library\Contracts;

interface Listing
{
    public function fetchAll();

    public function fetch($id);

    public function getListingViews($id);

    public function getViews();

}
