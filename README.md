# Overview

CodeIgniter Chrome Logger is just a simple wrapper around the [ChromePHP](https://github.com/ccampbell/chromephp) which allows you to use ChromePHP and the [Chrome Logger extension](https://chrome.google.com/webstore/detail/chrome-logger/noaneddfkdjfnfdakjjmocngnfkfehhd?hl=en) following [CodeIgniter library style](http://ellislab.com/codeigniter/user-guide/general/creating_libraries.html) guidelines.

[Chrome Logger extension](https://chrome.google.com/webstore/detail/chrome-logger/noaneddfkdjfnfdakjjmocngnfkfehhd?hl=en) is a _client-side_ library or chrome extension that allows you to debug _server-side_ code for many languages, included PHP.

[ChromePHP](https://github.com/ccampbell/chromephp) is the _server-side_ library used to send the debug info to the _client-side_ Chrome Logger extension. Obviously for PHP.

[CodeIgniter Chrome Logger](https://chrome.google.com/webstore/detail/chrome-logger/noaneddfkdjfnfdakjjmocngnfkfehhd?hl=en) is just a wrapper that will allow you to easily use ChromePHP as a CodeIgniter library.


## Requirements
In order to succesfully use this library you'll need:

 1. PHP 5.x >
 2. CodeIgniter 2.x >
 3. ChromePHP extension 4.1.0 >
 4. Chrome Logger extension for Google Chrome
 5. Of course, google chrome

## Installation
As stated before, this CodeIgniter library relies upon ChromePHP, which is of course not included here. Download it and put it inside the libraries folder, so for example if you have the following:

`CI_root/application/libraries/`

You'll end up with a folder like this:

`CI_root/application/libraries/ChromePhp`

If you organize your libraries in subfolders, so for example you have the CodeIgniter Chrome Logger inside its own folder and not in the root, then make sure the ChromePhp library folder is INSIDE your library folder like so:

`CI_root/application/libraries/codeigniter-chrome-logger/ChromePhp`

Considering you named your library folder _codeigniter-chrome-logger_

And that's it, you are good to go.

## Loading the library

First you need to load the library. To do so, you'll just use the regular way to do it:

### Autoloading it
Make sure you add _chrome-logger_ to your ``autoload.php`` configuration file:

```php
$autoload['libraries'] = array('database', 'session', 'chrome_logger');
```

### Manual load
If you prefer not to have globally load the library over the entire application (which is recommended for performance by the way) just load it on your controllers or models or wherever you need it:

**If it is on the root library:**
```php
$this->library->load('Chrome_logger');
```

**If it is on a subfolder:**
```php
$this->library->load('subfolder/Chrome_logger');
```

## Basic usage

Once you have loaded the library you need to make sure that you have the `ENVIRONMENT` constant defined as _development_ on your main `index.php` application file of CodeIgniter, otherwise, for security reasons, a warning message and no debug information will be shown on the console.

**Send Info**
```php
//[$var can be anything ]:
$this->chromelogger->info($var = 'This is an INFO message');
```

**Send Warning**
```php
//[$var can be anything ]:
$this->chromelogger->warn($var = 'This is a WARN message'); 
```

**Send error**
```php
//[$var can be anything ]:
$this->chromelogger->error($var = 'This is an ERROR message');
```

**Send log**
```php
//[$var can be anything ]:
$this->chromelogger->log($var = 'This is just a LOG');
```

**Send grouped information**
```php
//[GROUP, Send information grouped, $var is the header for that group ]:
$this->chromelogger->group($var = 'Group I');
$this->chromelogger->info('Some info for group I');
$this->chromelogger->info('Some more info for group I');
$this->chromelogger->group_end();  

$this->chromelogger->group($var = 'Group II');
$this->chromelogger->info('Some info for group II');
$this->chromelogger->info('Some more info for group II');
$this->chromelogger->group_end();
```

**Send grouped information, but collapsed**
```php
//[GROUP COLLAPSED, Same as group() only it will send it so that it is collapsed on console, 
//$var is the header for that group ]:
$this->chromelogger->group_collapsed($var = 'Group II');
$this->chromelogger->info('Some info for group II');
$this->chromelogger->info('Some more info for group II');
$this->chromelogger->group_end();
```

**Send information and display it as a table in the console of chrome**
```php
//[TABLE, will display the info as an ordered table on console, 
//$var can be anything but preferably a list of objects so it actually makes sense ]:
$p1 = new Person('John', 'Wayne', 'M');
$p2 = new Person('Gena', 'Rowllands', 'F');
$p3 = new Person('Clark', 'Gable', 'M');

$this->chromelogger->table($p1, $p2, $p3);
```
