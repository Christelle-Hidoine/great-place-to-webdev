<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class UserAccessTest extends WebTestCase
{
    /**
     * Get Routes for user not connected
     */
    public function testPageGetIsRedirect()
    {
        $client = self::createClient();
        $client->request('GET', '/favorites');
        
        $this->assertResponseRedirects();
    }

    /**
     * url test for GET method routes
     *
     * @dataProvider getUrlsGet
     * 
     * @param string $url
     */
    public function testAccessToFrontRoutesGetMethod($url, $email, $codeStatus): void
    {
        $client = self::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail($email);
        $client->loginUser($testUser);

        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame($codeStatus);
    }

    /**
     * url test for POST method routes
     *
     * @dataProvider getUrlsPost
     * 
     * @param string $url
     */
    public function testAccessToFrontRoutesPostMethod($url, $email, $codeStatus): void
    {
        $client = self::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail($email);
        $client->loginUser($testUser);

        $client->request('POST', $url);

        $this->assertResponseStatusCodeSame($codeStatus);
    }

    /**
     * provide all parameters to check GET routes permissions (url, email (role), response)
     * 
     * @return array
     */
    public function getUrlsGet()
    {
    // for user    
        // favorites
        yield ['/favorites', 'user1@user1.com', Response::HTTP_OK];
        // reviews
        yield ['/reviews/403/review/add', 'user1@user1.com', Response::HTTP_OK];
        
    }

    /**
     * provide all parameters to check Post routes permissions (url, email (role), response)
     * 
     * @return array
     */
    public function getUrlsPost()
    {  
    // for user    
        // favorites
        yield ['/favorites/clear', 'user1@user1.com', Response::HTTP_FOUND];
        // reviews
        yield ['/reviews/403/review/add', 'user1@user1.com', Response::HTTP_OK];
    }

}

