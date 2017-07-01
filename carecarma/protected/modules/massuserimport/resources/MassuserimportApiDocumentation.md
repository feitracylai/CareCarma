### Action /index ###

Get an overview of this Api.

- **Accepts:** POST/GET
- **Request:**  massuserimport/rest/index | massuserimport/rest
- **Request-data:** none  
- **Response:** Json listing all Api methods and dummy request/response data.

#### Example ####

**Request**

	GET: http://your_prefix/massuserimport/rest/index


**Response**
	{
	    "list": {
	        "required_fields": { },
	    },
	    "view": {
	        "required_fields": { },
	        "information": "(...)"
	    },
	    "create": {
	        "required_fields": { },
	        "all_editable_fields": { }, 
	        "information": "(...)"
	    },
	    "update": {
	        "required_fields": { },
	        "all_editable_fields": { }, 
	        "information": "(...)"
	    },
	    "delete": {
	        "required_fields": { },
	        "information": "(...)"
	    }
	}

### Action /list ###

Get a list of all users.

- **Accepts:** POST/GET
- **Request:**  massuserimport/rest/list
- **Request-data:** none
- **Response:** Json listing all user data.

The user data in the database is divided in two sections "user" and "profile". This structure is also represented by the database and can be seen in a user's json data response. 

#### Example ####

**Request**

	GET: http://your_prefix/massuserimport/rest/list


**Response**

	{
	    "users": [
	        {
	            "user": {
	                "id": 57,
	                "guid": "fc6439a6-1776-4121-87b6-55478eb57919",
	                "wall_id": 58,
	                "group_id": 1,
	                "status": 1,
	                "super_admin": 0,
	                "username": "m_mustermann",
	                "email": "max.mustermann@mail.com",
	                "auth_mode": "local",
	                "tags": null,
	                "language": null,
	                "last_activity_email": "2015-09-30 13:41:45",
	                "created_at": "2015-09-30 13:41:45",
	                "created_by": 1,
	                "updated_at": "2015-09-30 13:41:45",
	                "updated_by": null,
	                "last_login": null,
	                "visibility": 1,
	                "time_zone": "Europe/Berlin",
	                "imported": 0,
	                "contentcontainer_id": 5
	            },
	            "profile": {
	                "user_id": 57,
	                "firstname": "Max",
	                "lastname": "Mustermann",
	                "title": null,
	                "street": "Musterstr.",
	                "zip": "88888",
	                "city": "Musterhausen",
	                "country": "Musterland",
	                "state": null,
	                "birthday_hide_year": null,
	                "birthday": null,
	                "phone_private": null,
	                "phone_work": null,
	                "mobile": "0190888888888",
	                "fax": null,
	                "im_skype": null,
	                "im_msn": null,
	                "im_xmpp": null,
	                "url": null,
	                "url_facebook": null,
	                "url_linkedin": null,
	                "url_xing": null,
	                "url_youtube": null,
	                "url_vimeo": null,
	                "url_flickr": null,
	                "url_myspace": null,
	                "url_googleplus": null,
	                "url_twitter": null
	            }
	        },
	        {...}
	    ]
	}

### Action /view ###

Get a specified user's data.

- **Accepts:** POST/GET
- **Request:**  massuserimport/rest/view
- **Request-data:** email, id, guid, username
- **Response:** Json of this users data.

You can use one of the four unique user identifiers email, id, guid and username. You can also fill multiple ones, but the first one (order: id->guid->email->username) defined will always be taken.

#### Example ####

**Request**

	GET: http://your_prefix/massuserimport/rest/view?emai=max.mustermann@mail.com

	POST: http://your_prefix/massuserimport/rest/view
	POST-data:
	{
	    "username": "m_mustermann"
	}

**Response**
	{
	    {
	    "user": {
	        "id": 57,
	        "guid": "fc6439a6-1776-4121-87b6-55478eb57919",
	        "wall_id": 58,
	        "group_id": 1,
	        "status": 1,
	        "super_admin": 0,
	        "username": "m_mustermann",
	        "email": "max.mustermann@mail.com",
	        "auth_mode": "local",
	        "tags": null,
	        "language": null,
	        "last_activity_email": "2015-09-30 13:41:45",
	        "created_at": "2015-09-30 13:41:45",
	        "created_by": 1,
	        "updated_at": "2015-09-30 13:41:45",
	        "updated_by": null,
	        "last_login": null,
	        "visibility": 1,
	        "time_zone": "Europe/Berlin",
	        "imported": 0,
	        "contentcontainer_id": 5
	    },
	    "profile": {
	        "user_id": 57,
	        "firstname": "Max",
	        "lastname": "Mustermann",
	        "title": null,
	        "street": "Musterstr.",
	        "zip": "88888",
	        "city": "Musterhausen",
	        "country": "Musterland",
	        "state": null,
	        "birthday_hide_year": null,
	        "birthday": null,
	        "phone_private": null,
	        "phone_work": null,
	        "mobile": "0190888888888",
	        "fax": null,
	        "im_skype": null,
	        "im_msn": null,
	        "im_xmpp": null,
	        "url": null,
	        "url_facebook": null,
	        "url_linkedin": null,
	        "url_xing": null,
	        "url_youtube": null,
	        "url_vimeo": null,
	        "url_flickr": null,
	        "url_myspace": null,
	        "url_googleplus": null,
	        "url_twitter": null
	    }
	}

### Action /create ###

Creates a new user from the given data.

- **Accepts:** POST
- **Request:**  massuserimport/rest/create
- **Request-data:** see request json below.
- **Response:** Json showing that the call was successful.

Please note:

- If you provide no password a safe one will be generated. 
- If you provide no username, it will be generated from the firstname and lastname. 
- If the given username is not unique, it will be slightly changed to a unique one. 
- The only required parameters are user.email | profile.firstname | profile.lastname. 
- The created user will be informed via email about his new account and gets his user credentials.
- You need the correct Api password to get access to this method.
- You can configure the Api password in the modules configuration section.

#### Example ####

**Request**

	POST: http://your_prefix/massuserimport/rest/create
	POST-data:
	{
	    "apipassword": "SuperSafeApiPasswordxx!##",
	    "user": {
	        "group_id": 1,
	        "status": 1,
	        "super_admin": 0,
	        "username": "m_mustermaus",
	        "email": "max.mustermaus@mail.com",
	        "auth_mode": "local",
	        "tags": "three,example,tags",
	        "language": "en_gb",
	        "visibility": 1,
	        "time_zone": "Europe/Berlin"
	    },
	    "profile": {
	        "firstname": "Max",
	        "lastname": "Mustermaus",
	        "title": "Dr.",
	        "street": "Musterstr.",
	        "zip": "88888",
	        "city": "Musterhausen",
	        "country": "Musterland",
	        "state": "Germany",
	        "birthday_hide_year": "0",
	        "birthday": "2000-12-24",
	        "phone_private": "123456789",
	        "phone_work": "123456789",
	        "mobile": "0190888888888",
	        "fax": "0190888888888",
	        "im_skype": "SMM15",
	        "im_msn": "MMM15",
	        "im_xmpp": "XMM15@example.com",
	        "url": "https://www.mm15.de",
	        "url_facebook": "https://www.mm15.de",
	        "url_linkedin": "https://www.mm15.de",
	        "url_xing": "https://www.mm15.de",
	        "url_youtube": "https://www.mm15.de",
	        "url_vimeo": "https://www.mm15.de",
	        "url_flickr": "https://www.mm15.de",
	        "url_myspace": "https://www.mm15.de",
	        "url_googleplus": "https://www.mm15.de",
	        "url_twitter": "https://www.mm15.de"
	    },
	    "password": {
	        "newPassword": "mySuperSafeNewPasswordXx!",
	        "newPasswordConfirm": "mySuperSafeNewPasswordXx!"
	    }
	}

**Response**

	{
	    "success": true,
	    "message": "The user was successfully created."
	}

### Action /update ###

Updates a specified user according to the given data.

- **Accepts:** POST
- **Request:**  massuserimport/rest/update
- **Request-data:** see request json below
- **Response:** Json showing that the call was successful.

Please note:

- The user is identified by his id or guid.
- If you want to change the password, you need to specify the current one. 
- You may change the username and the email adress, but only if it does not already occur in the database. 
- The only required parameters are either id ord guid. 
- The updated user will NOT be informed about the changes.
- You need the correct Api password to get access to this method.
- You can configure the Api password in the modules configuration section.
- Pay attention, some fields have to be in a special format. (birthday, email, phone numbers, time_zone, language, ...)

#### Example ####

**Request**
	POST: http://your_prefix/massuserimport/rest/update
	POST-data:
	{
	    "apipassword": "SuperSafeApiPasswordxx!##",
	    "user": {
	        "id": "70"
	        "group_id": 1,
	        "status": 1,
	        "super_admin": 0,
	        "username": "m_mustermaus",
	        "email": "max.mustermaus@mail.com",
	        "auth_mode": "local",
	        "tags": "three,example,tags",
	        "language": "en_gb",
	        "visibility": 1,
	        "time_zone": "Europe/Berlin"
	    },
	    "profile": {
	        "firstname": "Max",
	        "lastname": "Mustermaus",
	        "title": "Dr.",
	        "street": "Musterstr.",
	        "zip": "88888",
	        "city": "Musterhausen",
	        "country": "Musterland",
	        "state": "Germany",
	        "birthday_hide_year": "0",
	        "birthday": "2000-12-24",
	        "phone_private": "123456789",
	        "phone_work": "123456789",
	        "mobile": "0190888888888",
	        "fax": "0190888888888",
	        "im_skype": "SMM15",
	        "im_msn": "MMM15",
	        "im_xmpp": "XMM15@example.com",
	        "url": "https://www.mm15.de",
	        "url_facebook": "https://www.mm15.de",
	        "url_linkedin": "https://www.mm15.de",
	        "url_xing": "https://www.mm15.de",
	        "url_youtube": "https://www.mm15.de",
	        "url_vimeo": "https://www.mm15.de",
	        "url_flickr": "https://www.mm15.de",
	        "url_myspace": "https://www.mm15.de",
	        "url_googleplus": "https://www.mm15.de",
	        "url_twitter": "https://www.mm15.de"
	    },
	    "password": {
	        "currentPassword": "mySuperSafePasswordXx!"
	        "newPassword": "mySuperSafeNewPasswordXx!",
	        "newPasswordConfirm": "mySuperSafeNewPasswordXx!"
	    }
	}

**Response**
	{
	    "success": true,
	    "message": "The user was successfully updated."
	}

### Action /delete ###

Deletes a specified user.

- **Accepts:** POST
- **Request:**  massuserimport/rest/delete
- **Request-data:** see request json below.
- **Response:** Json showing that the call was successful.

Please note:

- The user is identified by his id or guid. 
- As an user may be owner of multiple spaces and those must not be without an owner, you have to provide the email adress of a valid new owner of all these spaces.
- You need the correct Api password to get access to this method.
- You can configure the Api password in the modules configuration section.
- Pay attention, the user is deleted instantly.

#### Example ####

**Request**

	POST: http://your_prefix/massuserimport/rest/update
	POST-data:
	{
	    "apipassword": "SuperSafeApiPasswordxx!##",
	    "newspaceowneremail": "owner@owner.com",
	    "user": {
	        "id": "70"
	    }
	}

**Response**

	{
	    "success": true,
	    "message": "The user was successfully deleted. owner@owner.de overtakes the ownership of the user's spaces."
	}

### Error responses ###

If any kind of error occured you can expect a json response like the following.

	{
	    "name": "Not Found",
	    "message": "User not found!",
	    "code": 0,
	    "status": 404,
	    "type": "yii\web\HttpException"
	}
