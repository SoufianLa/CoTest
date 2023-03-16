<?php


namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

class Jwt
{
    const TYPE_ACCESS = "access";
    const TYPE_REFRESH = "refresh";
    const PREFIX_REFRESH = "Ref";
    const PREFIX_ACCESS = "Bearer";
    private $encoder;

    public function __construct(JWTEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    /**
     * @param array $infos
     * @param string $type
     * @return null|string
     * @throws JWTEncodeFailureException
     */
    public function generateToken($infos = [], $type = self::TYPE_ACCESS)
    {
        if (self::TYPE_ACCESS == $type) {
            // Set the token to 2 days to be expired
            $infos["exp"]= time() + 172800;
            $token = $this->encoder->encode($infos);
            return $token ? self::PREFIX_ACCESS." $token" : null;
        } elseif (self::TYPE_REFRESH == $type) {
            // Set the token to 4 days to be expired
            $infos["exp"]= time() + 345600;
            $token = $this->encoder->encode($infos);
            return $token ? self::PREFIX_REFRESH." $token" : null;
        } else {
            return null;
        }
    }

    /**
     * @param $request
     * @param string $type
     * @return string
     */
    public function extractToken($request, $type = self::TYPE_ACCESS)
    {
        try {
            if (self::TYPE_ACCESS == $type) {
                $extractor = new AuthorizationHeaderTokenExtractor(self::PREFIX_ACCESS, 'X-AUTH-TOKEN');
            } elseif (self::TYPE_REFRESH == $type) {
                $extractor = new AuthorizationHeaderTokenExtractor(self::PREFIX_REFRESH, 'X-REF-TOKEN');
            } else {
                $extractor = null;
            }
            return $extractor ? $extractor->extract($request) : null;
        } catch (\Exception $e) {
            return null;
        }
    }
    /**
     * @param $token
     * @return mixed
     */
    public function decodeToken($token)
    {
        try {
            return $this->encoder->decode($token);
        } catch (JWTDecodeFailureException $ex) {
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
