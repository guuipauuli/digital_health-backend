<?php

namespace App\Security;

use App\Entity\Company;
use App\Entity\User;
use App\Helper\ResponseHelper;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class ApiAuthenticator extends AbstractAuthenticator
{
    static $key = 'Authorization';

    private UserRepository $repository;

    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function supports(Request $request): ?bool
    {
        //TODO UNDO
        if(is_null($request->headers->get(self::$key))) {
            $request->headers->add([self::$key => 'guipauli@hotmail.com']);
        }

        return $request->headers->has(self::$key);
    }

    public function authenticate(Request $request): Passport
    {
        $apiToken = $request->headers->get(self::$key);
        if (null === $apiToken) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        return new SelfValidatingPassport(
            new UserBadge(str_replace(
                'Bearer ',
                '',
                Request::createFromGlobals()->headers->get(self::$key)
            ), function (string $userIdentifier) {
                return $this->getUser($userIdentifier);
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return (new ResponseHelper())->prepareResponse(Response::HTTP_UNAUTHORIZED, $data['message'])->toJson();
    }

    private function getUser($credentials, UserProviderInterface $userProvider = null): ?User
    {
        try {
//            $decodedToken = Password::decode($credentials);
//            if(isset($decodedToken->token) && isset($decodedToken->email)){
//                return $this->repository->findOneBy([
//                    'apiToken' => $decodedToken->token,
//                    'email' => $decodedToken->email
//                ]);
//            }

            $companyRepository = $this->entityManager->getRepository(Company::class);

            $users = $this->repository->findAll();
            if(!count($users)) {
                $user = new User();
                $user->setEmail('guipauli@hotmail.com');
                $user->setPassword('');
                $user->setRoles(['ROLE_MASTER']);
//                $user->setCompany($companyRepository->find(1));
                $this->repository->add($user);
                $this->entityManager->flush();

                $user = new User();
                $user->setEmail('guipauli@hotmail.com');
                $user->setPassword('');
                $user->setRoles(['ROLE_USER']);
                $user->setCompany($companyRepository->find(1));
                $this->repository->add($user);
                $this->entityManager->flush();
            }

            return $this->repository->findOneBy(['email' => 'guipauli@hotmail.com', 'company' => 1]);
        }
        catch (\Exception $e){}
        return null;
    }
}
