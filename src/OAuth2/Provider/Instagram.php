<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace SocialConnect\OAuth2\Provider;

use SocialConnect\Provider\AccessTokenInterface;
use SocialConnect\Provider\Exception\InvalidAccessToken;
use SocialConnect\Provider\Exception\InvalidResponse;
use SocialConnect\Common\Entity\User;
use SocialConnect\Common\Hydrator\ObjectMap;
use SocialConnect\OAuth2\AccessToken;

class Instagram extends \SocialConnect\OAuth2\AbstractProvider
{
    const NAME = 'instagram';

    /**
     * {@inheritdoc}
     */
    public function getBaseUri()
    {
        return 'https://api.instagram.com/v1/';
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizeUri()
    {
        return 'https://api.instagram.com/oauth/authorize';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTokenUri()
    {
        return 'https://api.instagram.com/oauth/access_token';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity(AccessTokenInterface $accessToken)
    {
        $response = $this->request('users/self', [], $accessToken);

        $hydrator = new ObjectMap(
            [
                'id' => 'id',
                'username' => 'username',
                'bio' => 'bio',
                'website' => 'website',
                'profile_picture' => 'pictureURL',
                'full_name' => 'fullname'
            ]
        );

        return $hydrator->hydrate(new User(), $response->data);
    }
}
