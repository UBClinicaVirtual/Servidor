# UB Clinica Virtual


> Description of the project in one line

- [API Live DEMO](https://www.ubclinicavirtual.tk/api/v1) - An operating DEMO of the api

## Table of contents

- [Features](#features)
- [Getting started](#getting-started)
- [Methods](#methods)

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
	
### List of current methods
- User
	- [Register with gmail](#register-with-gmail)
	- [Login with gmail](#login-with-gmail)
	- [Get the general user information based in the api_token](#get-the-general-user-information-based-in-the-api_token)
	- [Logout](#logout-with-an-api_token)
	- [Deactivate a user account with an api_token](#deactivate-a-user-account-with-an-api_token)
	
- Clinic Profile
	- [Add the clinic profile to the user account with an api_token](#add-the-clinic-profile-to-the-user-account-with-an-api_token)
	- [Get the clinic profile user information based in the api_token](#get-the-clinic-profile-user-information-based-in-the-api_token)
	- [Search a clinic](#search-a-clinic)
	
- HCP Profile	
	- [Add the HCP profile to the user account with an api_token](#add-the-hcp-profile-to-the-user-account-with-an-api_token)
	- [Get the HCP profile information of the user account with an api_token](#get-the-hcp-profile-information-of-the-user-account-with-an-api_token)
	- [Search a HCP](#search-a-hcp)
	
- Patient Profile	  
	- [Add the patient profile to the user account with an api_token](#add-the-patient-profile-to-the-user-account-with-an-api_token)
	- [Get the patient profile information of the user account with an api_token](#get-the-patient-profile-information-of-the-user-account-with-an-api_token)
	- [Search a patient](#search-a-patient)
	- [Get the medical records of the current user](#get-the-medical-records-of-the-current-user)
	- [Get the medical records of a patient](#get-the-medical-records-of-a-patient)

- Specialities
	- [Create a new Speciality](#create-a-new-speciality)
	- [Get a Speciality by id](#get-a-speciality-by-id)
	- [Update a Speciality by id](#update-a-speciality-by-id)
	- [Search Specialities](#search-specialities)
	
- Clinic administration
	- [Adds a HCPs with theirs specialities to the clinic profile](#adds-a-hcps-with-theirs-specialities-to-the-clinic-profile)
	- [Searchs the HCPs with theirs specialities to the clinic profile with a criteria](#searchs-the-hcps-with-theirs-specialities-to-the-clinic-profile-with-a-criteria)
	- [Adds a HCP and Speciality to the clinic schedule](#adds-a-hcp-and-speciality-to-the-clinic-schedule)
	- [Gets the HCP and Speciality of the clinic schedule](#gets-the-hcp-and-speciality-of-the-clinic-schedule)
	
- Appointments
	- [Schedule an appointment with the current user as patient](#schedule-an-appointment-with-the-current-user-as-patient)
	- [Get the appointments for the Clinic profile of the current user](#get-the-appointments-for-the-clinic-profile-of-the-current-user)
	- [Get the appointments for the HCP profile of the current user](#get-the-appointments-for-the-hcp-profile-of-the-current-user)
	- [Get the appointments for the patient profile of the current user](#get-the-appointments-for-the-patient-profile-of-the-current-user)
	- [Find available appointments with a criteria](#find-available-appointments-with-a-criteria)
	- [Cancel an appointment of the current user](#cancel-an-appointment-of-the-current-user)
	- [Add a medical record to an completed appointment](#add-a-medical-record-to-an-completed-appointment)

- Miscellaneous
	- [Get all genders](#get-all-genders)
	- [Get all appointment statuses](#get-all-appointment-statuses)	
	
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
    "user": {
        "id": 6,
        "first_name": "walter",
        "last_name": "ub",
        "email": "ubelarga@gmail.com",
        "created_at": "2018-09-20 17:30:52",
        "updated_at": "2018-09-27 15:06:20",
        "api_token": "HoFcCOSgtjyfD5GELBuyQ3xHZasPiBSyqGsFeDsYLXj4BewYcIEOvyqAn0iZ",
		"user_type_id": 0
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
    "user": {
        "id": 6,
        "email": "ubelarga@gmail.com",
        "created_at": "2018-09-20 17:30:52",
        "updated_at": "2018-09-27 15:06:20",
        "api_token": "HoFcCOSgtjyfD5GELBuyQ3xHZasPiBSyqGsFeDsYLXj4BewYcIEOvyqAn0iZ",
        "active": 1,
		"user_type_id": X
    },
	"patient":{
		"id": 6,
		"first_name": "walter",
		"last_name": "ub",        
		"created_at": "2018-09-20 17:30:52",
		"updated_at": "2018-09-27 15:06:20",
		"identification_number": "0303456",
		"birth_date": "1987-01-01",
		"gender_id": 0,
		"address": "Street 1234",
		"phone": "1234-4758"
	},
	"hcp":{
		"id": 6,
		"first_name": "MD. walter",
		"last_name": "ub",        
		"created_at": "2018-09-20 17:30:52",
		"updated_at": "2018-09-27 15:06:20",
		"identification_number": "0303456",
		"register_number": "RG3685",
		"birth_date": "1987-01-01",
		"gender_id": 0,
		"address": "Street 1234",
		"phone": "1234-4758",
		"specialities": [ {"id": 1, "name": "Guardia"} ]
	},
	"clinic":{
		"id": 6,
		"created_at": "2018-09-20 17:30:52",
		"updated_at": "2018-09-27 15:06:20",		
		"business_name": "Clinica San Martin",
		"business_number": "2003034567",		
		"address": "Street 1234",
		"phone": "1234-4758",
		"hcp_specialities": [ {"id": 44, "hcp_id": 33, "speciality_id": 12}],
		"hcps": [ {"id": 33, "first_name": "Dr. Juan", "last_name": "Perez"} ],
		"specialities": [ {"id": 12, "name": "Guardia"} ]
	}
	
}
```  

Where user_type_id can be:
- 0 if its a new user
- 1 if its a patient
- 2 if its a hcp
- 3 if its a clinic

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
    "user": {
        "id": 6,
        "email": "ubelarga@gmail.com",
        "created_at": "2018-09-20 17:30:52",
        "updated_at": "2018-09-27 15:06:20",
        "api_token": "HoFcCOSgtjyfD5GELBuyQ3xHZasPiBSyqGsFeDsYLXj4BewYcIEOvyqAn0iZ",
        "active": 1,
		"user_type_id": X
    },
	"patient":{
		"id": 6,
		"first_name": "walter",
		"last_name": "ub",        
		"created_at": "2018-09-20 17:30:52",
		"updated_at": "2018-09-27 15:06:20",
		"identification_number": "0303456",
		"birth_date": "1987-01-01",
		"gender_id": 0,
		"address": "Street 1234",
		"phone": "1234-4758"
	},
	"hcp":{
		"id": 6,
		"first_name": "MD. walter",
		"last_name": "ub",        
		"created_at": "2018-09-20 17:30:52",
		"updated_at": "2018-09-27 15:06:20",
		"identification_number": "0303456",
		"register_number": "RG3685",
		"birth_date": "1987-01-01",
		"gender_id": 0,
		"address": "Street 1234",
		"phone": "1234-4758",
		"specialities": [ {"speciality_id": 1, "name": "Guardia"} ]
	},
	"clinic":{
		"id": 6,
		"created_at": "2018-09-20 17:30:52",
		"updated_at": "2018-09-27 15:06:20",		
		"business_name": "Clinica San Martin",
		"business_number": "2003034567",		
		"address": "Street 1234",
		"phone": "1234-4758",
		"hcp_specialities": [ {"id": 44, "hcp_id": 33, "speciality_id": 12}],
		"hcps": [ {"id": 33, "first_name": "Dr. Juan", "last_name": "Perez"} ],
		"specialities": [ {"id": 12, "name": "Guardia"} ]
	}
}
```

Where user_type_id can be:
- 0 if its a new user
- 1 if its a patient
- 2 if its a hcp
- 3 if its a clinic

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
    "msg": "User logged out."
}
```

### Deactivate a user account with an api_token
> This method deactivates the full user account, so you cant operate as patient, hpc or clinic anymore.

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
    "msg": "User deactivated."
}
```

### Add the clinic profile to the user account with an api_token

- uri: /user/clinic
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"clinic":{
		"business_name": "Clinica San Martin",
		"business_number": "2003034567",		
		"address": "Street 1234",
		"phone": "1234-4758",
		"hcp_specialities": [ 44 ]
	}
}
```

- response:
```json
{
	"clinic":{
		"id": 6,
		"created_at": "2018-09-20 17:30:52",
		"updated_at": "2018-09-27 15:06:20",		
		"business_name": "Clinica San Martin",
		"business_number": "2003034567",		
		"address": "Street 1234",
		"phone": "1234-4758",
		"hcp_specialities": [ {"id": 44, "hcp_id": 33, "speciality_id": 12}],
		"hcps": [ {"id": 33, "first_name": "Dr. Juan", "last_name": "Perez"} ],
		"specialities": [ {"id": 12, "name": "Guardia"} ]
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
	"clinic":{
		"id": 6,
		"created_at": "2018-09-20 17:30:52",
		"updated_at": "2018-09-27 15:06:20",		
		"business_name": "Clinica San Martin",
		"business_number": "2003034567",		
		"address": "Street 1234",
		"phone": "1234-4758",
		"hcp_specialities": [ {"id": 44, "hcp_id": 33, "speciality_id": 12}],
		"hcps": [ {"id": 33, "first_name": "Dr. Juan", "last_name": "Perez"} ],
		"specialities": [ {"id": 12, "name": "Guardia"} ]
	}
}
```

### Search a clinic

- uri: /clinic/search
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"business_name": "Martin"
}
```

- response:
```json
{
    "clinics": [
		{
			"id": 6,
			"created_at": "2018-09-20 17:30:52",
			"updated_at": "2018-09-27 15:06:20",		
			"business_name": "Clinica San Martin",
			"business_number": "2003034567",		
			"address": "Street 1234",
			"phone": "1234-4758"
		}
    ]
}
```

### Add the HCP profile to the user account with an api_token

- uri: /user/hcp
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"hcp":{
		"first_name": "MD. walter",
		"last_name": "ub",        
		"identification_number": "0303456",
		"register_number": "RG3685",
		"birth_date": "1987-01-01",
		"gender_id": 0,
		"address": "Street 1234",
		"phone": "1234-4758",
		"specialities": [ 1 ]
	}  
}
```

- response:
```json
{
	"hcp":{
		"id": 6,
		"first_name": "MD. walter",
		"last_name": "ub",        
		"created_at": "2018-09-20 17:30:52",
		"updated_at": "2018-09-27 15:06:20",
		"identification_number": "0303456",
		"register_number": "RG3685",
		"birth_date": "1987-01-01",
		"gender_id": 0,
		"address": "Street 1234",
		"phone": "1234-4758",
		"specialities": [ {"speciality_id": 1, "name": "Guardia"} ]
	}
}
```

### Get the HCP profile information of the user account with an api_token

- uri: /user/hcp
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
	"hcp":{
		"id": 6,
		"first_name": "MD. walter",
		"last_name": "ub",        
		"created_at": "2018-09-20 17:30:52",
		"updated_at": "2018-09-27 15:06:20",
		"identification_number": "0303456",
		"register_number": "RG3685",
		"birth_date": "1987-01-01",
		"gender_id": 0,
		"address": "Street 1234",
		"phone": "1234-4758",
		"specialities": [ {"speciality_id": 1, "name": "Guardia"} ]
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
	"clinic_id": 1,
	"speciality_id": 1
}
```

- response:
```json
{
    "hcps": [
        {
            "id": 1,
            "created_at": "2018-10-26 23:13:29",
            "updated_at": "2018-10-26 23:13:29",
            "first_name": "Walter",
            "last_name": "Dua",
            "identification_number": "33000123",
            "birth_date": "1980-01-01",
            "gender_id": 0,
            "user_id": 2,
            "register_number": "447578",
            "address": "Suipacha 2030",
            "phone": "4787-7755"
        },
        {
            "id": 2,
            "created_at": "2018-10-26 23:13:29",
            "updated_at": "2018-10-26 23:13:29",
            "first_name": "Juan",
            "last_name": "Medics",
            "identification_number": "99050214",
            "birth_date": "1995-02-02",
            "gender_id": 0,
            "user_id": 4,
            "register_number": "RG142588",
            "address": "Montes de Oca 1425",
            "phone": "1111-2222"
        }
    ]
}
```

### Add the patient profile to the user account with an api_token

- uri: /user/patient
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"patient":{
		"first_name": "walter",
		"last_name": "ub",        
		"identification_number": "0303456",
		"birth_date": "1987-01-01",
		"gender_id": 0,
		"address": "Street 1234",
		"phone": "1234-4758"
	}
}
```

- response:
```json
{
	"patient":{
		"id": 6,
		"first_name": "walter",
		"last_name": "ub",        
		"created_at": "2018-09-20 17:30:52",
		"updated_at": "2018-09-27 15:06:20",
		"identification_number": "0303456",
		"birth_date": "1987-01-01",
		"gender_id": 0,
		"address": "Street 1234",
		"phone": "1234-4758"
	}
}
```

### Get the patient profile information of the user account with an api_token

- uri: /user/patient
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
	"patient":{
		"id": 6,
		"first_name": "walter",
		"last_name": "ub",        
		"created_at": "2018-09-20 17:30:52",
		"updated_at": "2018-09-27 15:06:20",
		"identification_number": "0303456",
		"birth_date": "1987-01-01",
		"gender_id": 0,
		"address": "Street 1234",
		"phone": "1234-4758"
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
	"last_name": "ub"
}
```

- response:
```json
{
	"patients": 
	[
		{
			"id": 6,
			"first_name": "walter",
			"last_name": "ub",
			"created_at": "2018-09-20 17:30:52",
			"updated_at": "2018-09-27 15:06:20",
			"identification_number": "0303456",
			"birth_date": "1987-01-01",
			"gender_id": 0,
			"address": "Street 1234",
			"phone": "1234-4758"
		}
	]
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
	"created_at": "2018-09-28 13:17:08",
	"updated_at": "2018-09-28 13:17:08"
}
```

### Get a Speciality by id

- uri: /speciality/{speciality_id}
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
    "created_at": "2018-09-28 13:17:08",
    "updated_at": "2018-09-28 13:17:08"
}
```

### Update a Speciality by id

- uri: /speciality/{speciality_id}
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
}
```

- response:
```json
{
    "id": 1,
    "name": "Guardia 2",
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
            "created_at": "2018-09-28 13:17:08",
            "updated_at": "2018-09-28 13:17:08"
        }
    ]
}
```

### Get the appointments for the patient profile of the current user

- uri: /user/patient/appointments
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
    "appointments": [
        {
            "id": 753,
            "clinic_id": 123,
            "clinic_name": "Clinica de la trinidad",
            "speciality_id": 789,
            "speciality_name": "Guardia de ginecologia",
            "hcp_id": 8560,
            "hcp_first_name": "Juan Jose",
            "hcp_last_name": "Ingenieros",
            "appointment_date": "2018/01/02 12:57",
            "appointment_status_id": 1,
            "appointment_status_name": "Pending"
        },
        {
            "id": 8820,
            "clinic_id": 123,
            "clinic_name": "Clinica de la trinidad",
            "speciality_id": 124,
            "speciality_name": "Traumatologo",
            "hcp_id": 9988,
            "hcp_first_name": "Bernabe",
            "hcp_last_name": "Marquez",
            "appointment_date": "2018/04/01 16:90",
            "appointment_status_id": 1,
            "appointment_status_name": "Pending"
        }
    ]
}
```

### Get the appointments for the hcp profile of the current user

- uri: /user/hcp/appointments
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
    "appointments": [
        {
            "id": 753,
            "clinic_id": 123,
            "clinic_name": "Clinica de la trinidad",
            "speciality_id": 789,
            "speciality_name": "Guardia de ginecologia",
            "patient_id": 1425,
            "patient_first_name": "Jesus",
            "patient_last_name": "de Nazaret",
            "appointment_date": "2018/01/02 12:57",
            "appointment_status_id": 1,
            "appointment_status_name": "Pending"
        },
        {
            "id": 8820,
            "clinic_id": 123,
            "clinic_name": "Clinica de la trinidad",
            "speciality_id": 124,
            "speciality_name": "Traumatologo",
            "patient_id": 1425,
            "patient_first_name": "Jesus",
            "patient_last_name": "de Nazaret",
            "appointment_date": "2018/04/01 16:90",
            "appointment_status_id": 1,
            "appointment_status_name": "Pending"
        }
    ]
}
```

### Get the appointments for the clinic profile of the current user

- uri: /user/clinic/appointments
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
    "appointments": [
        {
            "id": 753,
            "speciality_id": 789,
            "speciality_name": "Guardia de ginecologia",
            "hcp_id": 963,
            "hcp_first_name": "Medico",
            "hcp_last_name": "de guardia",
            "patient_id": 1425,
            "patient_first_name": "Jesus",
            "patient_last_name": "de Nazaret",
            "appointment_date": "2018/01/02 12:57",
            "appointment_status_id": 1,
            "appointment_status_name": "Pending"
        },
        {
            "id": 8820,
            "speciality_id": 124,
            "speciality_name": "Traumatologo",
            "hcp_id": 2458,
            "hcp_first_name": "Otro Medico",
            "hcp_last_name": "de otra guardia",
            "patient_id": 1425,
            "patient_first_name": "Jesus",
            "patient_last_name": "de Nazaret",
            "appointment_date": "2018/04/01 16:90",
            "appointment_status_id": 1,
            "appointment_status_name": "Pending"
        }
    ]
}
```

### Adds a HCPs with theirs specialities to the clinic profile
- uri: /user/clinic/hcpspecialities
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"hcp_specialities": [ 44 ]
}
```

- response:
```json
{
	"hcp_specialities": [ {"id": 44, "hcp_id": 33, "speciality_id": 12}]
}
```

### Searchs the HCPs with theirs specialities to the clinic profile with a criteria
- uri: /user/clinic/hcpspecialities/search
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"speciality_id": 12
}
```

- response:
```json
{
	"clinic": {
		"hcp_specialities": [ {"id": 44, "hcp_id": 33, "speciality_id": 12}],
		"hcps": [ {"id": 33, "first_name": "Dr. Juan", "last_name": "Perez"} ],
		"specialities": [ {"id": 12, "name": "Guardia"} ]
	}
}
```

### Adds a HCP and Speciality to the clinic schedule
- uri: /user/clinic/schedule
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"hcps":
	[
		{
			"hcp_speciality_id": 44,
			"day_of_the_week": 0,
			"appointment_hour": "18:00"
		},
		{
			"hcp_speciality_id": 44,
			"day_of_the_week": 0,
			"appointment_hour": "19:00"
		}
	]
}
```

- response:
```json
{
	"msg": "schedule updated"
}
```

### Gets the HCP and Speciality of the clinic schedule
- uri: /user/clinic/schedule/search
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"day_of_the_week": 0
}
```

- response:
```json
{
	"schedule":
	[
		{
			"id": 456,
			"hcp_speciality_id": 44,
			"hcp_id": 1,
			"hcp_first_name": "walter",
			"hcp_last_name": "ub",
			"speciality_id": 13,
			"speciality_name": "Guardia",
			"day_of_the_week": 0,
			"appointment_hour": "18:00"
		},
		{
			"id": 157,
			"hcp_speciality_id": 44,
			"hcp_id": 1,
			"hcp_first_name": "walter",
			"hcp_last_name": "ub",			
			"speciality_id": 13,
			"speciality_name": "Guardia",
			"day_of_the_week": 0,
			"appointment_hour": "19:00"
		}
	]
}
```

### Schedule an appointment with the current user as patient

- uri: /user/patient/appointment/schedule
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"clinic_appointment_schedule_id" : 456,
	"appointment_date": "2018/01/02"
}
```

- response:
```json
{
    "appointment": {
            "id": 753,
            "clinic_id": 123,
            "clinic_name": "Clinica de la trinidad",
            "speciality_id": 789,
            "speciality_name": "Guardia de ginecologia",
            "hcp_id": 8560,
            "hcp_first_name": "Juan Jose",
            "hcp_last_name": "Ingenieros",
            "appointment_date": "2018/01/02 18:00",
            "appointment_status_id": 1,
            "appointment_status_name": "Pending"
    }    
}
```

### Get the medical records of the current user

- uri: /user/patient/mrecords
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
    "medical_records": 
	[
		{
			"id": 999,
			"description": "Presenta cuadro febril. Se receta ibuprofeno",
			"clinic_id": 2,
			"clinic_name": "Clinica de la trinidad",
			"speciality_id": 789,
			"speciality_name": "Guardia de ginecologia",
			"hcp_id": 8560,
			"hcp_first_name": "Juan Jose",
			"hcp_last_name": "Ingenieros",
			"appointment_id": 1475,
			"appointment_date": "2018/01/02 18:00",
			"record_date": "2018/01/02 18:14"
		}   
	]
}
```

### Get the medical records of a patient

- uri: /user/patient/mrecords
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"patient_id": 142
}
```

- response:
```json
{
    "medical_records": 
	[
		{
			"id": 999,
			"description": "Presenta cuadro febril. Se receta ibuprofeno",
			"clinic_id": 2,
			"clinic_name": "Clinica de la trinidad",
			"speciality_id": 789,
			"speciality_name": "Guardia de ginecologia",
			"hcp_id": 8560,
			"hcp_first_name": "Juan Jose",
			"hcp_last_name": "Ingenieros",
			"appointment_id": 1475,
			"appointment_date": "2018/01/02 18:00",
			"record_date": "2018/01/02 18:14"
		}   
	]
}
```

### Get all genders
- uri: /genders
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
    "genders": 
	[
		{
			"id": 1,
			"name": "Male"
		},
		{
			"id": 2,
			"name": "Female"
		}   
	]
}
```

### Get all appointment statuses
- uri: /appointment_statuses
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
    "appointment_statuses": 
	[
		{
			"id": 1,
			"name": "Pending"
		},
		{
			"id": 2,
			"name": "Canceled"
		},
		{
			"id": 3,
			"name": "Completed"
		}   
	]
}
```

### Find available appointments with a criteria
- uri: /appointment/available
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"clinic_id": 1,
	"speciality_id": 1,
	"hcp_id": 1,
	"date_from": "2018-09-20", 
	"date_to": "2018-09-28"	
}
```

- response:
```json
{
    "appointments_available": 
	[
		{
			"id": 78,
			"hcp_id": 1,
			"hcp_first_name": "Juan Jose",
			"hcp_last_name": "Ingenieros",			
			"clinic_id": 1,
			"clinic_name": "Clinica de la trinidad",
			"speciality_id": 1,
			"speciality_name": "Guardia de ginecologia",
			"appointment_hour": "18:30",
			"day_of_the_week": 4,
			"appointment_date": "2018-09-20"            
		},
	]
}
```

### Cancel an appointment of the current user
- uri: /appointment/cancel
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"appointment_id": 78
}
```

- response:
```json
{
    "appointment": 
	[
		{
			"id": 78,
			"hcp_id": 1,
			"hcp_first_name": "Juan Jose",
			"hcp_last_name": "Ingenieros",			
			"clinic_id": 1,
			"clinic_name": "Clinica de la trinidad",
			"speciality_id": 1,
			"speciality_name": "Guardia de ginecologia",
			"appointment_hour": "18:30",
			"day_of_the_week": 4,
			"appointment_date": "2018-09-20",
			 "appointment_status_id": 2,
            "appointment_status_name": "Cancelled"
		},
	]
}
```

### Add a medical record to an completed appointment
- uri: /appointment/record
- method: `'POST'`
- headers:
  - `Accept`: `application/json`
  - `Content-Type`: `application/json`
  - `Authorization`: `Bearer AN_API_TOKEN`
- body:
```json
{
	"appointment_id": 1475,
	"description": "Presenta cuadro febril. Se receta ibuprofeno"
}
```

- response:
```json
{
    "medical_record": 
	[
		{
			"id": 999,
			"description": "Presenta cuadro febril. Se receta ibuprofeno",
			"clinic_id": 2,
			"clinic_name": "Clinica de la trinidad",
			"speciality_id": 789,
			"speciality_name": "Guardia de ginecologia",
			"hcp_id": 8560,
			"hcp_first_name": "Juan Jose",
			"hcp_last_name": "Ingenieros",
			"appointment_id": 1475,
			"appointment_date": "2018/01/02 18:00",
			"record_date": "2018/01/02 18:14"
		}
	]	
}
```

[? back to top](#table-of-contents)
