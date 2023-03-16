<?php


namespace App\Helper;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class Util
{
    public static function formatViolationMessage(ConstraintViolationListInterface $list)
    {
        if (count($list) > 0) {
            $violation = $list->get(0);

            return ucfirst($violation->getPropertyPath()).' : '.$violation->getMessage();
        }
        return false;
    }
    public static function render(string $message, $body = null): array
    {
        return ['message' => $message, 'body' => isset($body) ? $body : new \ArrayObject()];
    }

}