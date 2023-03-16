<?php


namespace App\Subscriber\Route;

use App\Helper\Util;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\DTO\AuthenticationDTO;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    public function onRequestReach(GenericEvent $event)
    {
        /* @var $dto AuthenticationDTO */
        $dto = $event->getSubject();
        $arguments = $event->getArguments();
        /* @var Request */
        $request = $arguments['request'];

        $route = $request->get('_route');
        $groups = []; //->
        switch ($route){
            case 'signup':
                $dto->setFirstName($request->get("firstName"));
                $dto->setLastName($request->get("lastName"));
                $dto->setEmail($request->get("email"));
                $dto->setPassword($request->get("password"));
                $groups[] = 'signup';
                break;
            default:
                return;
        }

        $violation = Util::formatViolationMessage($this->validator->validate($dto, null, $groups));
        if ($violation) {
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, $violation);
        }
    }
    public static function getSubscribedEvents(): array
    {
        return [
            'route.authentication' => 'onRequestReach',
        ];
    }


}