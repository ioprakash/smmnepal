# SMMNepal — SMM Script Analysis

This README summarizes the SMM-related script found in the repository and provides usage, dependencies, and recommended fixes.

## Files analyzed
- `app/classes/smm.php` — implements two main classes:
  - `SMMApi` — simple cURL POST wrapper (`action($data, $api)`) that POSTs URL-encoded data to a remote API and returns the JSON-decoded response.
  - `socialsmedia_api` — wrapper that sends a POST request with a single `jsonapi` field containing a JSON-encoded payload (merges internal `$this->data` with the provided data).

- `app/classes/mail.php` — defines `sendMail($arr)` which uses a global `$mail` (PHPMailer instance) and global `$settings` to send email(s).

## Quick contract / expectations
- SMMApi->action
  - Inputs: `$data` (array), `$api` (string URL)
  - Output: parsed JSON (object) on success, `null` on parse error, or `false` when curl fails
  - Error modes: if curl error and empty result, returns `false`; otherwise returns `json_decode($result)` which may be `null` for invalid JSON

- socialsmedia_api->query
  - Inputs: associative array containing at least `apiurl` along with other fields
  - Output: associative array (decoded JSON) on success, `false` on failure

- sendMail($arr)
  - Inputs: `$arr['mail']` (string or array), `$arr['subject']`, `$arr['body']`
  - Output: returns `1` on success, `0` on failure
  - Relies on global variables: `$mail`, `$settings`, `$conn`

## Dependencies
- PHP with cURL extension enabled (used heavily in both classes)
- PHPMailer (a `$mail` object is expected to exist as a global in `sendMail`)
- A settings/configuration array called `$settings` providing at least `smtp_port`, `smtp_user`, and `site_title` in the current code
- The codebase expects a `BASEPATH` constant to be defined (direct access guard at top of files)

## Notable code observations & issues
1. SMMApi->action
   - Uses `CURLOPT_SSL_VERIFYPEER = true` which is good, but there is no `CURLOPT_CAINFO` set; ensure the PHP/cURL environment has a valid CA bundle.
   - On curl errors it does `if (curl_errno($ch) != 0 && empty($result)) { $result = false; }` and returns `json_decode($result)`. If `$result` is `false`, `json_decode(false)` returns `null` — the code previously returned `false` but then `json_decode` converts it to `null`. Consider returning `false` directly on curl errors.
   - No logging of curl errors — hard to debug remote failures.

2. socialsmedia_api->query
   - Sends a single field `jsonapi` containing the JSON-encoded payload. Ensure remote API expects that.
   - Uses `@json_decode($cr, true)` — the silence operator suppresses potential errors. Better to check `curl_errno` and handle JSON `json_last_error()`.

3. sendMail
   - Uses global `$mail` and `$settings` — global state makes unit testing and re-use harder. Prefer injecting the PHPMailer instance and configuration.
   - The code sets `Host`, `Username`, `Password` to empty strings; presumably these are meant to be populated from `$settings`.
   - `SMTPSecure` is set to `"HTTP/1.1"` which is incorrect (should be `'ssl'` or `'tls'` or left empty for no encryption). This is likely a bug and will prevent proper SMTP connection.
   - Exceptions are swallowed; function returns `0` on any exception without logging or returning the exception message.
   - `SetLanguage('tr', 'phpmailer/language')` and `CharSet = utf-8` are fine if expected.

## Recommended fixes (high priority)
- For `SMMApi->action`:
  - Return a consistent error type on curl failure (e.g. `false`) and don't `json_decode(false)`.
  - Log curl errno and `curl_error()` to a log file or debug channel.
  - Optionally allow passing extra cURL options (timeout, headers, CA file).

- For `socialsmedia_api->query`:
  - Validate `apiurl` exists and is a valid URL before calling.
  - After `json_decode`, check `json_last_error()` and return a meaningful error or log it.

- For `sendMail`:
  - Fix `SMTPSecure` value — use `$settings['smtp_secure']` or valid constant (`'ssl'`/`'tls'`).
  - Read `Host`, `Username`, `Password` from `$settings` instead of empty strings.
  - Avoid clearing addresses inside the mailing loop incorrectly (use `ClearAddresses` before adding addresses for each recipient if sending separate mails is required).
  - Log exceptions rather than just return `0`.
  - Accept and use an injected mailer object rather than relying on `global $mail`.

## Usage examples
- Using `SMMApi`:

```php
// Ensure BASEPATH is defined by the application bootstrap
$api = new SMMApi();
$data = ['service' => 'followers', 'quantity' => 100, 'link' => 'https://instagram.com/example'];
$response = $api->action($data, 'https://api.smmprovider.example/order');

if ($response === false) {
    // network/curl error
} elseif ($response === null) {
    // invalid JSON
} else {
    // $response is an object parsed from JSON
}
```

- Using `socialsmedia_api`:

```php
$api = new socialsmedia_api();
$payload = ['apiurl' => 'https://api.example.com/endpoint', 'key' => 'API_KEY', 'action' => 'status'];
$result = $api->query($payload);
if ($result === false) {
    // network or decode error
} else {
    // $result is associative array
}
```

- Using `sendMail` (current code expects globals):

```php
// $mail and $settings must be set up by the application bootstrap
sendMail([
  'mail' => 'user@example.com',
  'subject' => 'Test',
  'body' => '<p>Hello</p>'
]);
```

## Edge cases to test
- Remote API returns non-JSON response (HTML error page)
- Remote API times out or DNS errors
- Empty or malformed `$data` arrays
- Mail sending fails due to bad SMTP config (bad host, wrong port, wrong credentials)
- Multiple recipients (array) vs single recipient string handling

## Next steps (suggestions)
- Add minimal unit tests for `SMMApi->action` and `socialsmedia_api->query` using a local mock server or using a testable wrapper for curl.
- Replace globals in `sendMail` with dependency injection.
- Add logging for curl errors and mail exceptions.
- Fix `SMTPSecure` and ensure SMTP settings are read from config.

---

If you want, I can:
- implement the recommended quick fixes (e.g., return consistency for curl errors, add logging, fix `SMTPSecure`),
- add a small test script to demonstrate calling the SMM API and sending a test mail (using environment config), or
- run static checks to find any other files referencing these classes.

Tell me which follow-up you'd like and I'll proceed.  
# smmnepal
