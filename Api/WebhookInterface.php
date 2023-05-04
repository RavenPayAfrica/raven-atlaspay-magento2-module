<?php

namespace Raven\AtlasPay\Api;

interface WebhookInterface
{
    /**
     * @return string
     */
    public function execute();
}
