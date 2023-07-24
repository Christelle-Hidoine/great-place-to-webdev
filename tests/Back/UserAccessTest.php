<?php

namespace App\Tests\Back;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserAccessTest extends WebTestCase
{
    /**
     * Get Routes for user not connected
     */
    public function testPageGetIsRedirect()
    {
        $client = self::createClient();
        $client->request('GET', '/back/main');
        
        $this->assertResponseRedirects();
    }

    /**
     * url test for GET method routes
     *
     * @dataProvider getUrlsGet
     * 
     * @param string $url
     */
    public function testAccessToBackRoutesGetMethod($url, $email, $codeStatus): void
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
    public function testAccessToBackRoutesPostMethod($url, $email, $codeStatus): void
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
    //for user    
        // backoffice homepage
        yield ['/back/main', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/city', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/country', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/user', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/image', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/review', 'user@user.com', Response::HTTP_FORBIDDEN];
        // backoffice create
        yield ['back/city/new', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/country/new', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/user/new', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/image/new', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/review/new', 'user@user.com', Response::HTTP_FORBIDDEN];
        // backoffice edit
        yield ['back/city/401/edit', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/country/101/edit', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/user/9/edit', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/image/1989/edit', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/review/1090/edit', 'user@user.com', Response::HTTP_FORBIDDEN];
    
    // for admin    
        // backoffice homepage
        yield ['back/city', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/country', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/user', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/image', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/review', 'admin@admin.com', Response::HTTP_OK];
        // backoffice create
        yield ['back/city/new', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/country/new', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/user/new', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/image/new', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/review/new', 'admin@admin.com', Response::HTTP_OK];
        // backoffice edit
        yield ['back/city/401/edit', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/country/101/edit', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/user/9/edit', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/image/1989/edit', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/review/1090/edit', 'admin@admin.com', Response::HTTP_OK];
        
    }

    /**
     * provide all parameters to check Post routes permissions (url, email (role), response)
     * 
     * @return array
     */
    public function getUrlsPost()
    {
    //for user    
        // backoffice create
        yield ['back/city/new', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/country/new', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/user/new', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/image/new', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/review/new', 'user@user.com', Response::HTTP_FORBIDDEN];
        // backoffice edit
        yield ['back/city/401/edit', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/country/101/edit', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/user/9/edit', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/image/1989/edit', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/review/1090/edit', 'user@user.com', Response::HTTP_FORBIDDEN];
        // backoffice delete
        yield ['back/city/401', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/country/101', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/user/9', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/image/1', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/review/1', 'user@user.com', Response::HTTP_FORBIDDEN];

    // for admin    
        // backoffice create
        yield ['back/city/new', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/country/new', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/user/new', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/image/new', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/review/new', 'admin@admin.com', Response::HTTP_OK];
        // backoffice edit
        yield ['back/city/401/edit', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/country/101/edit', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/user/9/edit', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/image/1989/edit', 'admin@admin.com', Response::HTTP_OK];
        yield ['back/review/1090/edit', 'admin@admin.com', Response::HTTP_OK];
        // backoffice delete => redirectToList = response 303
        yield ['back/city/401', 'admin@admin.com', Response::HTTP_SEE_OTHER];
        yield ['back/country/101', 'admin@admin.com', Response::HTTP_SEE_OTHER];
        yield ['back/user/9', 'admin@admin.com', Response::HTTP_SEE_OTHER];
        yield ['back/image/1989', 'admin@admin.com', Response::HTTP_SEE_OTHER];
        yield ['back/review/1090', 'admin@admin.com', Response::HTTP_SEE_OTHER];
        
    }

}
