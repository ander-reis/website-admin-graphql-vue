<?php


namespace WebsiteAdmin\Exceptions;

use GraphQL\Error\ClientAware;

class ResourceNotFoundException extends \Exception implements ClientAware {

    protected $message = "Resource not found";

    public function isClientSafe() {
        return true;
    }

    public function getCategory() {
        return 'resource_not_found';
    }
}
