<?php

namespace App\Controller;

use App\DTO\AuthenticationDTO;
use App\Entity\User;
use App\Helper\Util;
use App\Service\AuthenticationService;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api")
 */
class AuthenticationController extends AbstractController
{
    private $authService;
    private $dispatcher;
    private $serializer;

    public  function __construct(AuthenticationService $authService, EventDispatcherInterface $dispatcher, SerializerInterface $serializer){
        $this->authService =$authService;
        $this->dispatcher = $dispatcher;
        $this->serializer = $serializer;
    }
    /**
     * @Route("/auth/signup", methods={"POST"}, name="signup")
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="multipart/form-data",
     *         @OA\Schema(
     *             type="object",
     *             required={"firstName", "lastName", "email", "password", "photos[]"},
     *             @OA\Property(
     *                 property="firstName",
     *                 description="John Doe",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="lastName",
     *                 description="John Doe",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 description="email@email.com",
     *                 type="string",
     *                 pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
     *             ),
     *            @OA\Property(
     *                    property="password",
     *                    type="string",
     *                    description="password",
     *                    example="192443"
     *                ),
     *             @OA\Property(
     *                 property="photos[]",
     *                 description="photos",
     *                 type="file"
     *             ),
     *         )
     *     )
     * ),
     * @OA\Response(
     *     response=201,
     *     description="created succefully"
     * ),
     * @OA\Response(
     *     response=200,
     *     description="operation with success"
     * ),
     * @OA\Response(
     *     response=422,
     *     description="Missing parameter"
     * ),
     * @OA\Response(
     *     response=409,
     *     description="violation/conflict"
     * ),
     * @OA\Response(
     *     response=403,
     *     description="forbiden"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="internal error"
     * )
     * )
     * @OA\Tag(name="Authentication")
     * @Security(name="ApiSecret")
     * @Security(name="ApiLocale")
     *
     * @return JsonResponse
     */
    public function signUp(Request $request): JsonResponse
    {
        $dto = new AuthenticationDTO();
        $this->dispatcher->dispatch(new GenericEvent($dto, ['request' => $request]), 'route.authentication');
        $user = $this->authService->signUp($dto);
        $context = SerializationContext::create()->setGroups(["user"])->setSerializeNull(true);
        return new JsonResponse($this->serializer->serialize(Util::render('signup.user_created', $user), 'json', $context), Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/auth/login",methods={"POST"}, name="login_user")
     * @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                @OA\Property(
     *                    property="email",
     *                    type="string",
     *                    description="email",
     *                    example="email@email.com"
     *                ),
     *                @OA\Property(
     *                    property="password",
     *                    type="string",
     *                    description="password",
     *                    example="192443"
     *                ),
     *             )
     *         )
     *     )
     * @OA\Response(
     *       response=200,
     *       description="success"
     *  ),
     * @OA\Response(
     *       response=400,
     *       description="failed"
     *  ),
     * @OA\Response(
     *       response=422,
     *       description="Missing parameter"
     *  ),
     * @OA\Response(
     *       response=409,
     *       description="violation/conflict"
     *  ),
     * @OA\Response(
     *       response=403,
     *       description="forbiden"
     *  ),
     * @OA\Response(
     *       response=500,
     *       description="internal error"
     *  )
     * )
     * @OA\Tag(name="Authentication")
     * @Security(name="ApiSecret")
     * @Security(name="ApiLocale")
     * @param Request $request
     * @return JsonResponse
     */
    public function loginAction(Request $request)
    {
        $dto = new AuthenticationDTO();
        $this->dispatcher->dispatch(new GenericEvent($dto, ["request" => $request]), 'route.authentication');
        $user = $this->authService->login($dto);
        $context = SerializationContext::create()->setGroups(["user", "with_time", "Auth"])->setSerializeNull(true);
        return new JsonResponse($this->serializer->serialize(Util::render("LOGIN_OK", $user), "json", $context), Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/me",methods={"GET"}, name="fetch_user")
     * @OA\Response(
     *       response=200,
     *       description="Returns new session"
     *  ),
     * @OA\Response(
     *       response=401,
     *       description="unauthorized to refresh"
     *  ),
     * @OA\Response(
     *       response=500,
     *       description="internal error"
     *  )
     * @OA\Tag(name="Authentication")
     * @Security(name="ApiSecret")
     * @Security(name="ApiLocale")
     * @Security(name="Bearer")
     * @return JsonResponse
     */
    public function fetchUserAction(){
        $user = $this->getUser();
        $context = SerializationContext::create()->setGroups(["user", "Auth", "photo"])->setSerializeNull(true);
        return new JsonResponse($this->serializer->serialize(Util::render("FETCH_OK", $user), "json", $context), Response::HTTP_OK, [], true);
    }

}
