<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use DateTime;

class Controller extends BaseController
{
    /**
     * @param DateTime | string $updated
     * @return bool
     */
    protected function isUpdatedLastDay($updated)
    {
      if ($updated instanceof DateTime) {
        $lastUpdatedDate = $updated;
      } else {
        $lastUpdatedDate = new DateTime($updated);
      }
      $now = new DateTime("now");
      $days = $lastUpdatedDate->diff($now)->days;
      
      if ($days <= 1) {
      	return true;
      }
      return false;
    }

    /**
     * @param DateTime | string $updated
     * @return bool
     */
    protected function isCreatedLastDay($created)
    {
      if ($created instanceof DateTime) {
        $lastCreatedDate = $created;
      } else {
        $lastCreatedDate = new DateTime($created);
      }
      $now = new DateTime("now");
      $days = $lastCreatedDate->diff($now)->days;
      
      if ($days <= 24) {
      	return true;
      }
      return false;
    }
}
