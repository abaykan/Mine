Escaping
----------------
```
"   >	&#34
#   >	&#35
&   >	&#38
'   >	&#39
(   >	&#40
)   >	&#41
/   >	&#47
;   >	&#59
<   >	&#60
>   >	&#62
```



URL Escaping
----------------

Jika mau menaruh inputan user yang menjadi value dari sebuah parameter, lalu direfleksikan pada halaman di dalam tag HTML, contoh:
`<a href="http://www.website.com/?test=...ESCAPE INPUTAN PENGGUNA DISINI...">link</a>`

Sebelumnya saya ingatkan terlebih dahulu agar tidak meng-encode semua isi URL, karena yang dibutuhkan hanya meng-encode inputan pengguna sehingga tidak terbaca sebagai script.
```javascript
String userURL = request.getParameter( "userURL" );
boolean isValidURL = Validator.IsValidURL(userURL, 255); 
if (isValidURL) {  
    <a href="<%=encoder.encodeForHTMLAttribute(userURL)%>">link</a>
}
```



Sanitize HTML Markup
----------------

HTML Sanitizer
var sanitizer = new HtmlSanitizer();
sanitizer.AllowedAttributes.Add("class");
var sanitized = sanitizer.Sanitize(html);

Atau bisa juga menggunakan HTML Sanitizer seperti https://github.com/mganss/HtmlSanitizer

Beberapa HTML Sanitizer lain:
- PHP HTML Purifier - http://htmlpurifier.org/
- JavaScript/Node.js Bleach - https://github.com/ecto/bleach
- Python Bleach - https://pypi.python.org/pypi/bleach



Content Security Policy
----------------

`Content-Security-Policy: default-src: 'self'; script-src: 'self' static.domain.tld`



Menggunakan X-XSS-Protection Response Header
----------------

HTTP response header ini memungkinkan filter XSS ke beberapa browser web. Peran header ini adalah untuk mengaktifkan kembali filter untuk situs web tertentu ini jika dinonaktifkan oleh pengguna.



Menggunakan Filter PHP
----------------
```php
function checkstring($content) {
	code
}

$checkstring = checkstring($content);
if ($checkstring == true){
	banned
} else {
	kamu lolos.
}
```
