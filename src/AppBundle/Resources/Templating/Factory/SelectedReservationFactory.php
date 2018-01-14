<?php

namespace AppBundle\Resources\Templating\Factory;

use AppBundle\Resources\Templating\ViewObject\SelectedReservationView;

class SelectedReservationFactory
{
    public function create(string $surgery, string $date)
    {
        return new SelectedReservationView();
    }

}