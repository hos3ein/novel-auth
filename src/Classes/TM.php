<?php

namespace Hos3ein\NovelAuth\Classes;

use DateTimeImmutable;
use Hos3ein\NovelAuth\Features\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

/**
 * Class TM (Token Manager)
 * @package Hos3ein\NovelAuth\Classes
 */
class TM
{
    public static function createAuthProcessToken(Request $request): Plain
    {
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText('1'));
        return $config->builder()
            ->withClaim('jti', Str::random()) // jwtID for add token to blacklist
            ->withClaim('email_phone', $request->emailPhone)
            ->withClaim('input_type', $request->inputType)
            //->expiresAt($now->modify('+5 minute')->getTimestamp())
            ->issuedAt(new DateTimeImmutable())
            ->getToken($config->signer(), $config->signingKey());

    }

    public static function appendToClaims(Plain $claims, $name, $value): Plain
    {
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText(config(Constants::$configSecretKey)));
        foreach ($claims->claims()->all() as $claimKey => $claimValue)
            if ($claimKey != $name)
                $config->builder()->withClaim($claimKey, $claimValue);
        $config->builder()->withClaim($name, $value);
        return $config->builder()->getToken($config->signer(), $config->signingKey());
    }

    public static function removeFromClaims(Plain $claims, $name): Plain
    {
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText(config(Constants::$configSecretKey)));
        foreach ($claims->claims()->all() as $claimKey => $claimValue)
            if ($claimKey != $name)
                $config->builder()->withClaim($claimKey, $claimValue);
        return $config->builder()->getToken($config->signer(), $config->signingKey());
    }

    public static function validToken(Plain $token): bool
    {
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText(config(Constants::$configSecretKey)));
        return $config->validator()->validate($token, new SignedWith($config->signer(), $config->signingKey()));
    }

    public static function ParseToken(string $token_rc): Plain
    {
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText(config(Constants::$configSecretKey)));
        return $config->parser()->parse($token_rc);
    }
}
