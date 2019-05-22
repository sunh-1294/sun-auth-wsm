
## Documentation

To get started with **SunAuth**, use Composer to add the package to your project's dependencies:

```bash
    composer require sunh-1294/sun-auth-wsm
```

### Configuration

After installing the **SunAuth** library, register the `Sun\Auth\SunAuthServiceProvider` in your `config/app.php` configuration file:

```php
'providers' => [
    // Other service providers...

    Sun\Auth\SunAuthServiceProvider::class,
],
```

Also, add the `SAuth` facade to the `aliases` array in your `app` configuration file:

```php
'aliases' => [
    // Other aliases

    'SAuth' => Sun\Auth\Facades\SunAuth::class,
],
```

You will also need to add credentials for the OAuth services your application utilizes. These credentials should be placed in your `config/services.php` configuration file, and use the key `sun`. For example:
```php
'sun' => [
    'client_id' => 'your-sun-auth-app-id',
    'client_secret' => 'your-sun-auth-app-secret',
    'redirect' => 'http://your-callback-url',
    'base_url' => 'http://base-url-wsm'
],
```
### Basic Usage

Next, you are ready to authenticate users! You will need two routes: one for redirecting the user to the OAuth provider, and another for receiving the callback from the provider after authentication. We will access **Sun Auth** using the `SAuth` facade:

```php
<?php

namespace App\Http\Controllers\Auth;

use SAuth;

class LoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return SAuth::redirect();
    }

    /**
     * Obtain the user information from WSM.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = SAuth::driver($provider)->user();
        $createdUser = User::firstOrCreate([
            'provider' => $provider,
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'provider_id' => $user->getId(),
        ]);
        
        // Login với user vừa tạo.
        Auth::login($createdUser);

        return redirect('/home');
    }
}
```

The `redirect` method takes care of sending the user to the **Sun Auth** provider, while the `user` method will read the incoming request and retrieve the user's information from the provider.

Of course, you will need to define routes to your controller methods:

```php
Route::get('login/sun', 'Auth\LoginController@redirectToProvider');
Route::get('login/sun/callback', 'Auth\LoginController@handleProviderCallback');
```

#### Retrieving User Details

Once you have a user instance, you can grab a few more details about the user:

```php
$user = SAuth::user();

$token = $user->token;
$refreshToken = $user->refreshToken; // not always provided
$expiresIn = $user->expiresIn;

// Example infomation:
$user->getId(); // Or maybe $user->id
$user->getName(); // Or maybe $user->name
$user->getEmail(); // Or maybe $user->email
$user->getAvatar(); // Or maybe $user->avatar
$user->getGender(); // Or maybe $user->gender
$user->getBirthday(); // Or maybe $user->birthday
$user->getPhoneNumber(); // Or maybe $user->phoneNumber

// All infomation about user will be stored here:
$user->getRaw(); // Or maybe $user->user
```

#### Retrieving User Details From Token

If you already have a valid access token for a user, you can retrieve their details using the `userFromToken` method:

```php
$user = SAuth::userFromToken($token);
```
