# ICHI PHP TEMPLATE

ICHI PHP TEMPLATE is the fast and secure Pure PHP Template Library

## License

This package is Open Source According to [MIT license](LICENSE.md)

## Table Of Contents

* [Acknowledgement](#acknowledgement)
* [Installion](#installion)
* [Set Up Template Base Directory Path](#set-up-template-base-directory-path)
* [Showing Views](#showing-views)
* [Showing Error Messages](#showing-error-messages)
* [Showing Success Messages](#showing-success-messages)
* [Getting File Name](#getting-file-name)
* [Section And Content](#section-and-content)
* [Sharing Data In All Views](#sharing-data-in-all-views)
* [Preventing XSS Attack](#preventing-xss-attack)
* [Components](#components)

### Acknowledgement

I really thanks to my mother for everything. She is the best for me.

### Installation

```php

composer require jijihohococo/ichi-template:dev-master

```

### Set Up Template Base Directory Path

You can set up the base directory path for your all template view files.

<b>It is highly recommend to set up template base directory path before using functions of ICHI TEMPLATE</b>

<b>The base directory path will be used in calling all views</b>

```php

use JiJiHoHoCoCo\IchiTemplate\Template\View;

// example //

View::setPath(__DIR__.'/../views/');

```

It doesn't matter if you want to use another base directory path name.

After setting template base path for views, you can directly call the files under this base path directly.

For example,you have 'show_data.php' under your template base path.

You can directly call this file in showing views directly without including directory path if you had set template base path.


### Showing Views

You can show the views in your function like that

<b>You must include full directory path if you don't set up the template base path</b>

<b>Using 'view' function can only apply section and content template style in that called view php file.</b>


<i>Without Data</i>

```php

public function showData(){
	//
	return view('show_data.php');
}

```
<i>With Data</i>

```php

public function showData(){
	return view('show_data.php',[
		'data' => 'Hello World'
	]);
}

```

You can use called data in your view file which is calling from "view" function

<i>In your_view_php_file_path</i>
```php

echo $data; // Hello World

```
If you don't want to use template system but want to show only the views. You can do as shown as below

You can also use following functions within your view php file which is called from 'view' function

<i>Without Data</i>

```php

includeView('include_file.php');

```

<i>With Data</i>

```php

includeView('include_file.php',[
	'data' => 'Hello World'
]);

```

'includeView' function will show the file by using 'include' function
'includeOnceView' function will show the by using 'include_once' function 
'requireView' function will show the file by using 'require' function
'requireOnceView' function will show the file by using 'require_once' function

### Showing Error Messages

You can add error messages

```php

setErrors([
	'name_error' => 'name is required',
	'email_error' => 'email is required'
]);

```

You can get error messages with 'errors' array variable in your view php files

```php
echo $errors['name_error']. '<br>';

echo $errors['email_error'];
```

### Showing Success Messages

You can add success messages

```php

setSuccess([
	'message' => 'registeration is completed'
]);
```
You can get sucess messages with 'success' array variable in your view php views

```php

echo $success['message'];

```
### Getting File Name

If you want to get the file names (to connect javascript,css and image files) under your template base path

```php

echo s('script.css');

```

### Section And Content

You can apply template system as shown as below

<i>In your template main php file</i>
```html
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<?php y('content'); ?>

<?php includeView('admin/layouts/footer.php'); ?>
</body>
</html>
```

<i>In your view php file</i>
```html
<?php

extend('main.php'); 
section('content');
?>

<p>Content</p>

<?php endSection(); ?>
```

In your view php file, You must call your template main php file with 'extend' function firstly.
You must add 'content' name in your 'section' function.
After using 'section' function and write your frontend stuffs you must use 'endSection' function

In your template main php file, you must call 'y' function with the content name that you want to show.

Both view php file and main layout file will be shown.

If you want to change page title dynamicatlly according to view php file

<i>In your view php file</i>

```php

section('content','Content Page');

```

<i>In your template main php file</i>

```html
<head>
	<title><?php echo title(); ?></title>
</head>
```

### Sharing Data In All Views

You can share the data (variables) in all your views

```php

use JiJiHoHoCoCo\IchiTemplate\Template\View;

View::share([
'writer' => 'John',
'book' => 'New Book' 
]);

```

And calling 'view' function

```php

return view('show_data.php');

```

You can use the share data as variables in your view php file

```php

echo $writer . '<br>';
echo $book;


```
### Preventing XSS Attack

You can prevent your string data output from xss attack

```php

echo e($data);

```

### Components

You can use class as your component to show view php files

<i>In your component class</i>

```php

namespace App\Views\Components;
use JiJiHoHoCoCo\IchiTemplate\Component\Component;


class TestComponent extends Component{

	public function render(){
		return view('componet_view.php');
	}
}

```

<i>In your view php file</i>

```php

component('App\Views\Components\TestComponent');

```

You can use constructor in your component class to pass the data

<i>In your component class</i>

```php

namespace App\Views\Components;
use JiJiHoHoCoCo\IchiTemplate\Component\Component;


class TestComponent extends Component{

	private $name;

	public function __construct(string $name){
		$this->name=$name;
	}

	public function render(){
		return view('componet_view.php',[
			'name' => $this->name
		]);
	}
}

```
<i>In your view php file</i>

```php

component('App\Views\Components\TestComponent',[
	'name' => 'Test Data'
]);

```

You can set the base directory path for your component classes.

```php

use JiJiHoHoCoCo\IchiTemplate\Component\ComponentSetting;

ComponentSetting::setPath('App\Views\Components');

```

So you can call only component class name when you use 'component' function

```php

component('TestComponent',[
	'name' => 'Test Data'
]);

```
