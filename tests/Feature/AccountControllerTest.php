<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use App\Services\GeoCoordinateService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected array $invalidRegisterData = [
        'name' => null,
        'address1' => null,
        'address2' => null,
        'city' => null,
        'state' => null,
        'country' => null,
        'zipCode' => null,
        'phoneNo1' => null,
        'phoneNo2' => null,
        'user' => [
            'firstName' => null,
            'lastName' => null,
            'email' => 'test',
            'password' => 'Secret@123',
            'passwordConfirmation' => 'Secret@123234',
            'phone' => null,
        ],
    ];

    protected array $validRegisterData = [
        'name' => 'Direct Ad Network',
        'address1' => 'Rock Heven Way 23244',
        'address2' => '#125',
        'city' => 'Sterling',
        'state' => 'VA',
        'country' => 'USA',
        'zipCode' => 20166,
        'phoneNo1' => '555-666-7777',
        'phoneNo2' => '',
        'user' => [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'joh.doe@example.com',
            'password' => 'Secret@123',
            'passwordConfirmation' => 'Secret@123',
            'phone' => '123-456-7890',
        ],
    ];

    public function testRegisterSuccessfully(): void
    {
        $this->mockGeoCoordinate();

        $this->post('/register', $this->validRegisterData)->assertStatus(201);

        $this->assertEquals(1, Client::count());
        $this->assertEquals(1, User::count());
    }

    public function testRegisterValidationFailed(): void
    {
        $this->post('/register', $this->invalidRegisterData)->assertStatus(422)->assertJsonValidationErrors(
            [
                'name',
                'address1',
                'address2',
                'city',
                'state',
                'country',
                'zipCode',
                'phoneNo1',
                'user.firstName',
                'user.lastName',
                'user.email',
                'user.password',
                'user.phone',
            ]
        );

        $this->assertEquals(0, Client::count());
        $this->assertEquals(0, User::count());
    }

    public function testRegisterByOneClient(): void
    {
        $this->mockGeoCoordinate();

        $this->post('/register', $this->validRegisterData)->assertStatus(201);

        $clientCount = Client::count();
        $userCount = User::count();

        $this->assertEquals(1, $clientCount);
        $this->assertEquals(1, $userCount);

        $data = $this->validRegisterData;
        Arr::set($data, 'user.email', 'test@example.com');

        $this->post('/register', $data)->assertStatus(201);

        $this->assertEquals($clientCount, Client::count());
        $this->assertEquals($userCount + 1, User::count());
    }

    public function testIndex(): void
    {
        $this->mockGeoCoordinate();
        $this->post('/register', $this->validRegisterData);

        $this->get('/account')->assertStatus(200)->assertJsonStructure(
            [
                'data' => [
                    0 => [
                        'id',
                        'name',
                        'address1',
                        'address2',
                        'city',
                        'state',
                        'country',
                        'zipCode',
                        'latitude',
                        'longitude',
                        'phoneNo1',
                        'phoneNo2',
                        'totalUser' => [
                            'all',
                            'active',
                            'inactive',
                        ],
                        'startValidity',
                        'endValidity',
                        'status',
                        'createdAt',
                        'updateAt',
                    ],
                ],
                'links' => [
                    'path',
                    'firstPageUrl',
                    'lastPageUrl',
                    'nextPageUrl',
                    'prevPageUrl',
                ],
                'meta' => [
                    'currentPage',
                    'from',
                    'lastPage',
                    'perPage',
                    'to',
                    'total',
                    'count',
                ],
            ]
        );
    }

    protected function mockGeoCoordinate()
    {
        $this->app->bind(GeoCoordinateService::class, function () {
            $mock = $this->getMockBuilder(GeoCoordinateService::class)
                ->disableOriginalConstructor()
                ->setMethods(['getCoordinates',])
                ->getMock();
            $mock
                ->expects($this->any())
                ->method('getCoordinates')
                ->willReturn(
                    [
                        'lat' => 38.968052,
                        'lng' => -77.4903178,
                    ]
                );

            return $mock;
        });
    }
}
