<?php

namespace nbcx\oauth\server\grantType;

use nbcx\oauth\server\responseType\AccessTokenInterface;
use nb\request\Driver as RequestInterface;
use nb\response\Driver as ResponseInterface;

/**
 * Interface for all OAuth2 Grant Types
 */
interface GrantTypeInterface {

    public function getQuerystringIdentifier();

    public function validateRequest(RequestInterface $request, ResponseInterface $response);

    public function getClientId();

    public function getUserId();

    public function getScope();

    public function createAccessToken(AccessTokenInterface $accessToken, $client_id, $user_id, $scope);
}
