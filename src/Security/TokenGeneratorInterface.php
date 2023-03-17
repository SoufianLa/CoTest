<?php

namespace App\Security;

interface TokenGeneratorInterface{
    const TYPE_ACCESS = "access";
    const TYPE_REFRESH = "refresh";
    const PREFIX_REFRESH = "Ref";
    const PREFIX_ACCESS = "Bearer";
    public function generateToken(array $infos, string $type);

    public function extractToken(\Symfony\Component\HttpFoundation\Request $request, string $type);

    public function decodeToken(string $token);

}
