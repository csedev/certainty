<?php

namespace App\Service;

use App\Entity\Worklog;

class WorklogTransformer
{
    public function transform($data)
    {
        $data = array_map("utf8_encode", $data);

        $product = new Worklog();
        $product->setTimespent($data['timespent']);
        $product->setTimestamp($data['timestamp']);        

        return $product;
    }
}