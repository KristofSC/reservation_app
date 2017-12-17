<?php


class SelectedReservationFactory
{
    public function create(string $surgery, string $date)
    {
        return new SelectedReservationView();
    }

}