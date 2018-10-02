# UB Clinica Virtual


> Description of the project in one line

- [API Live DEMO](https://www.ubclinicavirtual.tk/api/v1) - An operating DEMO of the api

## Table of contents

- [Features](#features)
- [Getting started](#getting-started)
- [Methods](#methods)
- [Contributing](#contributing)
- [Versioning](#versioning)
- [License](#license)

## Features

- Register through gmail api

## Getting started

### Installation

#### Installation on windows ( mysql + php + IIS)
- Get IIS
- Get PHP 7
- Get MySQL
- Get Composer
- Configure IIS
- Download from github
- Configure .env file
- Install google api via Composer(to rework)


### Usage

## Methods

### URL Base for all methods:
	- local: http://laravel.win/api/v1
	- http: http://www.ubclinicavirtual.tk/api/v1
	- https: https://ubclinicavirtual.000webhostapp.com/api/v1
	

### Register with gmail

- uri: /register
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
- body:
```json
{
	"access_token":"VALID_GMAIL_ACCESS_TOKEN"
}
```

- response:
```json
{
    "data": {
        "id": 6,
        "name": "walter ub",
        "email": "ubelarga@gmail.com",
        "email_verified_at": null,
        "created_at": "2018-09-20 17:30:52",
        "updated_at": "2018-09-27 15:06:20",
        "api_token": "HoFcCOSgtjyfD5GELBuyQ3xHZasPiBSyqGsFeDsYLXj4BewYcIEOvyqAn0iZ",
        "active": 1
    },
    "gmail": {
        "name": "walter ub",
        "email": "ubelarga@gmail.com"
    }
}
```
  
### Login with gmail

- uri: /login
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
- body:
```json
{
	"access_token":"VALID_GMAIL_ACCESS_TOKEN"
}
```

- response:
```json
{
    "data": {
        "id": 6,
        "name": "walter ub",
        "email": "ubelarga@gmail.com",
        "email_verified_at": null,
        "created_at": "2018-09-20 17:30:52",
        "updated_at": "2018-09-27 15:06:20",
        "api_token": "HoFcCOSgtjyfD5GELBuyQ3xHZasPiBSyqGsFeDsYLXj4BewYcIEOvyqAn0iZ",
        "active": 1
    },
    "gmail": {
        "name": "walter ub",
        "email": "ubelarga@gmail.com"
    }
}
```  

### Get the general user information based in the api_token

- uri: /user
- method: `'GET'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{}
```

- response:
```json
{
    "id": 6,
    "name": "walter ub",
    "email": "ubelarga@gmail.com",
    "email_verified_at": null,
    "created_at": "2018-09-20 17:30:52",
    "updated_at": "2018-09-27 15:06:20",
    "api_token": "HoFcCOSgtjyfD5GELBuyQ3xHZasPiBSyqGsFeDsYLXj4BewYcIEOvyqAn0iZ",
    "active": 1
}
```

### Logout with an api_token

- uri: /logout
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{}
```

- response:
```json
{
    "data": "User logged out."
}
```

### Deactivate a user's account with an api_token
> This method deactivates the full user's account, so you cant operate as patient, hpc or clinic anymore.

- uri: /deactivate
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{}
```

- response:
```json
{
    "data": "User deactivated."
}
```

### Add the clinic profile to the user's account with an api_token

- uri: /user/clinic
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"business_name": "clinica san martin"
}
```

- response:
```json
{
    "clinic": {
        "id": 6,
        "business_name": "clinica san martin",
        "created_at": "2018-09-26 13:04:57",
        "updated_at": "2018-09-27 15:11:09"
    }
}
```


### Get the clinic profile user information based in the api_token

- uri: /user/clinic
- method: `'GET'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{}
```

- response:
```json
{
    "clinic": {
        "id": 6,
        "business_name": "Clinica de trinidad",
        "created_at": "2018-09-26 13:04:57",
        "updated_at": "2018-09-27 17:26:58",
        "business_number": "20123456789"
    }
}
```

### Search a clinic by likely business_name

- uri: /clinic/search
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"business_name": "martin"
}
```

- response:
```json
{
    "clinics": [
        {
            "id": 6,
            "business_name": "clinica san martin",
            "created_at": "2018-09-26 13:04:57",
            "updated_at": "2018-09-26 13:04:57"
        }
    ]
}
```

### Add the HCP profile to the user's account with an api_token

- uri: /user/hcp
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
  "name": "Un medico clinico",
  "registration_number": "123456",
  "identification_number": "321456",
  "specialities": [ 1 ]
}
```

- response:
```json
{
    "hcp": {
        "hcp": {
            "id": 6,
            "created_at": "2018-10-02 12:46:23",
            "updated_at": "2018-10-02 12:46:23",
            "name": "Un medico clinico",
            "registration_number": "123456",
            "identification_number": "321456"
        },
        "specialities": [
            {
                "id": 1,
                "name": "Guardia 2",
                "active": 1,
                "created_at": "2018-09-28 13:10:01",
                "updated_at": "2018-09-28 13:12:45"
            }
        ]
    }
}
```

### Search a HCP

- uri: /hcp/search
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
}
```

- response:
```json
{
    "msg": "unimplemented method"
}
```

### Add the patient profile to the user's account with an api_token

- uri: /user/patient
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"name": "Walter",
	"identification_number": "33000123"
}
```

- response:
```json
{
    "patient": {
        "id": 6,
        "created_at": "2018-09-27 17:44:38",
        "updated_at": "2018-09-27 17:44:38",
        "name": "Walter",
        "identification_number": "33000123"
    }
}
```

### Search a patient

- uri: /patient/search
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
}
```

- response:
```json
{
    "msg": "unimplemented method"
}
```

### Create a new Speciality

- uri: /speciality
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"name": "Guardia"
}
```

- response:
```json
{
    "id": 1,
    "name": "Guardia",
    "active": 1,
    "created_at": "2018-09-28 13:17:08",
    "updated_at": "2018-09-28 13:17:08"
}
```

### Get a Speciality by id

- uri: /speciality/{id_speciality}
- uri example: /speciality/1
- method: `'GET'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
}
```

- response:
```json
{
    "id": 1,
    "name": "Guardia",
    "active": 1,
    "created_at": "2018-09-28 13:17:08",
    "updated_at": "2018-09-28 13:17:08"
}
```

### Update a Speciality by id

- uri: /speciality/{id_speciality}
- uri example: /speciality/1
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"name": "Guardia 2",
	"active": 1
}
```

- response:
```json
{
    "id": 1,
    "name": "Guardia 2",
    "active": 1,
    "created_at": "2018-09-28 13:17:08",
    "updated_at": "2018-09-28 13:17:08"
}
```

### Search Specialities

- uri: /specialities
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"name": "Guardia 2"
}
```

- response:
```json
{
    "specialities": [
        {
            "id": 1,
            "name": "Guardia 2",
            "active": 1,
            "created_at": "2018-09-28 13:17:08",
            "updated_at": "2018-09-28 13:17:08"
        }
    ]
}
```

[? back to top](#table-of-contents)

## Contributing

Please read through our [contributing guidelines](.github/CONTRIBUTING.md).

## Versioning

Maintained under the [Semantic Versioning guidelines](https://semver.org/).

## License

[MIT](https://opensource.org/licenses/MIT) © ???

[? back to top](#table-of-contents)