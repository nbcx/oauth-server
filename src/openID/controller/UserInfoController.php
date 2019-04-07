<?php
namespace nAuth\openID\Controller;

use nAuth\Scope;
use nAuth\tokenType\TokenTypeInterface;
use nAuth\storage\AccessTokenInterface;
use nAuth\openID\Storage\UserClaimsInterface;
use nAuth\controller\ResourceController;
use nAuth\scopeInterface;
use nAuth\RequestInterface;
use nAuth\ResponseInterface;

/**
 * @see OAuth2\Controller\UserInfoControllerInterface
 */
class UserInfoController extends ResourceController implements UserInfoControllerInterface {
    private $token;

    protected $tokenType;
    protected $tokenStorage;
    protected $userClaimsStorage;
    protected $config;
    protected $scopeUtil;

    public function __construct(TokenTypeInterface $tokenType, AccessTokenInterface $tokenStorage, UserClaimsInterface $userClaimsStorage, $config = [], ScopeInterface $scopeUtil = null) {
        $this->tokenType = $tokenType;
        $this->tokenStorage = $tokenStorage;
        $this->userClaimsStorage = $userClaimsStorage;

        $this->config = array_merge([
            'www_realm' => 'Service',
        ], $config);

        if (is_null($scopeUtil)) {
            $scopeUtil = new Scope();
        }
        $this->scopeUtil = $scopeUtil;
    }

    public function handleUserInfoRequest(RequestInterface $request, ResponseInterface $response) {
        if (!$this->verifyResourceRequest($request, $response, 'openid')) {
            return;
        }

        $token = $this->getToken();
        $claims = $this->userClaimsStorage->getUserClaims($token['user_id'], $token['scope']);
        // The sub Claim MUST always be returned in the UserInfo Response.
        // http://openid.net/specs/openid-connect-core-1_0.html#UserInfoResponse
        $claims += [
            'sub' => $token['user_id'],
        ];
        $response->addParameters($claims);
    }
}
