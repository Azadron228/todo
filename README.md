# Todo

A basic todo app built for learning Laravel framework. Project covers essential features including user registration, CRUD operations, file upload, and filtering capabilities. The project is thoroughly tested with PHPUnit.

## Features

- **User Registration:** Users can register for an account to manage their tasks.
- **CRUD Operations:** Users can Create, Read, Update, and Delete tasks.
- **File Upload:** Users can attach files to their tasks.
- **Filtering:** Tasks can be filtered by priority, status, and date.
- **PHPUnit Tests:** The entire project is covered with PHPUnit tests to ensure reliability and stability.


## Technologies:

* Programming Language: Php
* Framework: Laravel
* Databases: Sqlite
* Testing: PhpUnit


## Installing

```sh
git clone https://github.com/Azadron228/todo.git
cd todo
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

## Api Reference

<details>
<summary>Auth</summary>

### POST: /login
```json
{
  "email": "example@gmail.com",
  "password": "123456789",
  "password_confirmation": "123456789"
}
```

### Response:

```json
"successfull"
```

### POST: /register
```json
{
  "username": "Jotaro",
  "email": "success",
  "password":"123456789",
  "password_confirmation": "123456789"
}
```

### Response:
```json
[
    "User Registered successfully"
]
```
### POST: /user
```json
{
	"id": 1,
	"name": "Jotaro",
	"email": "jojo@gmail.com",
	"email_verified_at": null,
	"created_at": "2024-02-07T12:57:23.000000Z",
	"updated_at": "2024-02-07T12:57:23.000000Z"
}
```
</details>

<details>
<summary>Task CRUD</summary>

### GET: /task
```json
{
	"data": [
		{
			"id": 1,
			"text": "task1",
			"status": "in_progress",
			"attachment": "public\/nE1l15SS924BPGmmzedYPkiB6Fb771QXNu0oq4Wz.png",
			"created_at": "2024-02-07T13:03:36.000000Z",
			"updated_at": "2024-02-07T13:03:36.000000Z"
		},
		{
			"id": 2,
			"text": "task2",
			"status": "in_progress",
			"attachment": "",
			"created_at": "2024-02-07T13:03:48.000000Z",
			"updated_at": "2024-02-07T13:03:48.000000Z"
		}
	],
	"links": {
		"first": "http:\/\/localhost:8000\/task?page=1",
		"last": "http:\/\/localhost:8000\/task?page=1",
		"prev": null,
		"next": null
	},
	"meta": {
		"current_page": 1,
		"from": 1,
		"last_page": 1,
		"links": [
			{
				"url": null,
				"label": "&laquo; Previous",
				"active": false
			},
			{
				"url": "http:\/\/localhost:8000\/task?page=1",
				"label": "1",
				"active": true
			},
			{
				"url": null,
				"label": "Next &raquo;",
				"active": false
			}
		],
		"path": "http:\/\/localhost:8000\/task",
		"per_page": 15,
		"to": 2,
		"total": 2
	}
}
```
### POST /task

```json
{
	"text": "task1"
}
```

### Response:

```json
{
	"data": {
		"id": 2,
		"text": "task2",
		"status": "in_progress",
		"attachment": "",
		"created_at": "2024-02-07T13:03:48.000000Z",
		"updated_at": "2024-02-07T13:03:48.000000Z"
	}
}
```
### 

### GET /task/{id}
```json
{
	"data": {
		"id": 1,
		"text": "task1",
		"status": "in_progress",
		"attachment": "public\/nE1l15SS924BPGmmzedYPkiB6Fb771QXNu0oq4Wz.png",
		"created_at": "2024-02-07T13:03:36.000000Z",
		"updated_at": "2024-02-07T13:03:36.000000Z"
	}
}
```

### PUT /task
```json
{
	"text": "Updated task",
	"status": "done"
}
```
### Response:
```json
{
	"data": {
		"id": 1,
		"text": "Updated task",
		"status": "done",
		"attachment": "public\/nE1l15SS924BPGmmzedYPkiB6Fb771QXNu0oq4Wz.png",
		"created_at": "2024-02-07T13:03:36.000000Z",
		"updated_at": "2024-02-07T13:13:17.000000Z"
	}
}

```
### DELETE /task

```json
"task deleted"
```
</details>

<details>
<summary>Attachments</summary>
    
### POST /task/{id}/attachments
```json
{
	"message": "Attachment uploaded successfully."
}
```
</details>
