<?php
/*=========================================================================
  Program:   CDash - Cross-Platform Dashboard System
  Module:    $Id$
  Language:  PHP
  Date:      $Date$
  Version:   $Revision$

  Copyright (c) Kitware, Inc. All rights reserved.
  See LICENSE or http://www.cdash.org/licensing/ for details.

  This software is distributed WITHOUT ANY WARRANTY; without even
  the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
  PURPOSE. See the above copyright notices for more information.
=========================================================================*/
namespace CDash\Middleware\OAuth2;

use CDash\Middleware\OAuth2;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use TheNetworg\OAuth2\Client\Provider\Azure;

/**
 * Class AzureAD
 * @package CDash\Middleware\OAuth2
 */
class AzureAD extends OAuth2
{
    /**
     * @return Collection
     * @throws IdentityProviderException
     */
    public function getEmail()
    {
        if (!$this->Email) {
            $token = $this->getAccessToken();
            $provider = $this->getProvider();
            $resourceOwner = $provider->getResourceOwner($token);
            $this->Email = collect([(object)['email' => $resourceOwner->getUpn()]]);
        }
        return $this->Email;
    }

    /**
     * @return String
     * @throws IdentityProviderException
     */
    public function getOwnerName()
    {
        $token = $this->getAccessToken();
        $provider = $this->getProvider();
        $resourceOwner = $provider->getResourceOwner($token);
        return "{$resourceOwner->getFirstName()} {$resourceOwner->getLastName()}";
    }


    /**
     * @return AzureProvider
     */
    public function getProvider()
    {
        if (!$this->Provider) {
            $uri = $this->getRedirectUri();
            $settings = array_merge(
                ['redirectUri' => $uri],
                config('oauth2.azuread')
            );
            $this->Provider = new Azure($settings);
        }
        return $this->Provider;
    }
}
