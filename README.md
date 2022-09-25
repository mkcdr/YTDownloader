# YTDownloader

Single pure PHP class for downloading Youtube videos.

## :warning: Legal Disclaimer

Ofcourse it's illegal to download copyrighted things from Youtube since it's against their policy and terms of service, 
so this script is intended for personal use only and by using this script you are responsible for any copyright violations. 
and the author of this script is not responsible for anyone who use this script in any way to break [Youtube's Terms of service](https://www.youtube.com/static?template=terms).

^^ Why I wrote this, coz I don't want to get in troubles and you too should use this program to learn and not to break rules.

## Usage

```php
try 
{
    $ytd = new YTDownloader();
    $ytd->loadInfoFromUrl($url);
    
    $formats = $ytd->getFormats();
    
    echo $formats[0]->url;
} 
catch (Exception $e)
{
    echo $e->getMessage();
}
```
