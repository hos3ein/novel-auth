<?php

namespace Hos3ein\NovelAuth\Classes;

use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

/**
 * Class TM (Token Manager)
 * @package Hos3ein\NovelAuth\Classes
 */
class TM
{
    public static function createAuthProcessToken(Request $request): Token
    {
        return (new Builder())
            ->withClaim('jti', Str::random()) // jwtID for add token to blacklist
            ->withClaim('email_phone', $request->emailPhone)
            ->withClaim('input_type', $request->inputType)
            //->expiresAt($now->modify('+5 minute')->getTimestamp())
            ->issuedAt((new DateTimeImmutable())->getTimestamp())
            ->getToken(new Sha256(), new Key(config('app.key')));
    }

    public static function appendToClaims(Token $claims, $name, $value): Token
    {
        $b = new Builder();
        foreach ($claims->getClaims() as $claimKey => $claimValue)
            if ($claimKey != $name)
                $b->withClaim($claimKey, $claimValue);
        $b->withClaim($name, $value);
        return $b->getToken(new Sha256(), new Key(config('app.key')));
    }

    public static function removeFromClaims(Token $claims, $name): Token
    {
        $b = new Builder();
        foreach ($claims->getClaims() as $claimKey => $claimValue)
            if ($claimKey != $name)
                $b->withClaim($claimKey, $claimValue);
        return $b->getToken(new Sha256(), new Key(config('app.key')));
    }

    public static function validToken(Token $token): bool
    {
        return $token->verify(new Sha256(), new Key(config('app.key')));
    }

    public static function ParseToken(string $token_rc): Token
    {
        return (new Parser())->parse($token_rc);
    }
}
