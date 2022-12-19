<?php

namespace Hos3ein\NovelAuth\Classes;

use DateTimeImmutable;
use Hos3ein\NovelAuth\Features\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\RegisteredClaims;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

/**
 * Class TM (Token Manager)
 * @package Hos3ein\NovelAuth\Classes
 */
class TM
{
    public static function createAuthProcessToken(Request $request): Plain
    {
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText(config(Constants::$configSecretKey)));
        return $config->builder()
            ->identifiedBy(Str::random()) // jwtID for add token to blacklist
            ->withClaim('email_phone', $request->emailPhone)
            ->withClaim('input_type', $request->inputType)
            ->withClaim('remember', $request->filled('remember'))
            //->expiresAt($now->modify('+5 minute')->getTimestamp())
            ->issuedAt(new DateTimeImmutable())
            ->getToken($config->signer(), $config->signingKey());

    }

    public static function appendToClaims(Plain $claims, $name, $value): Plain
    {
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText(config(Constants::$configSecretKey)));
        $builder = $config->builder();
        foreach ($claims->claims()->all() as $claimKey => $claimValue)
            if ($claimKey != $name)
                $builder = self::setRegisteredClaims($builder, $claimKey, $claimValue);
        $builder = self::setRegisteredClaims($builder, $name, $value);
        return $builder->getToken($config->signer(), $config->signingKey());
    }

    public static function removeFromClaims(Plain $claims, $name): Plain
    {
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText(config(Constants::$configSecretKey)));
        $builder = $config->builder();
        foreach ($claims->claims()->all() as $claimKey => $claimValue)
            if ($claimKey != $name)
                $builder = self::setRegisteredClaims($builder, $claimKey, $claimValue);
        return $builder->getToken($config->signer(), $config->signingKey());
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

    private static function setRegisteredClaims(Builder $builder, $name, $value): Builder
    {
        if ($name == RegisteredClaims::ID) {
            return $builder->identifiedBy($value);
        } elseif ($name == RegisteredClaims::AUDIENCE) {
            return $builder->permittedFor($value);
        } elseif ($name == RegisteredClaims::NOT_BEFORE) {
            if (is_int($value)) $value = (new DateTimeImmutable())->setTimestamp($value);
            return $builder->canOnlyBeUsedAfter($value);
        } elseif ($name == RegisteredClaims::EXPIRATION_TIME) {
            if (is_int($value)) $value = (new DateTimeImmutable())->setTimestamp($value);
            return $builder->expiresAt($value);
        } elseif ($name == RegisteredClaims::ISSUED_AT) {
            if (is_int($value)) $value = (new DateTimeImmutable())->setTimestamp($value);
            return $builder->issuedAt($value);
        } elseif ($name == RegisteredClaims::ISSUER) {
            return $builder->issuedBy($value);
        } elseif ($name == RegisteredClaims::SUBJECT) {
            return $builder->relatedTo($value);
        } else {
            return $builder->withClaim($name, $value);
        }
    }
}
