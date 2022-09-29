<?php
/**
 * YTDownloader
 * 
 * @author    @mkcdr
 * @copyright 2022
 */

class YTDownloader
{
    /**
     * @var string Regualar expression to validate Youtube URLs
     */
    const YTURLPATTERNS = '#^https?:\/\/(?:www\.)?(?:youtu\.be\/|youtube.com\/watch\?v=)(?<vid>[A-Za-z0-9\-_]{11})(?:.*)$#';

    /**
     * @var string Regular expression to scrap video sources
     * @deprecated
     */
    const YTFORMATSREGEX = '#,"formats":(?P<formats>\[(?:.*?)\]),"adaptiveFormats":(?P<adaptiveFormats>\[(?:.*?)\])#';

    /**
     * @var string Regualar expression for ytInitialPlayerResponse object
     */
    const YTInitialPlayerResponseRegex = '#ytInitialPlayerResponse\s*=\s*(?P<ytInitialResponse>{.+?})\s*;#';

    /**
     * @var array Formats
     */
    private $formats;

    /**
     * @var array Adaptive formats
     */
    private $adaptiveFormats;

    /**
     * @var array YT initial player response, contains all the data needed
     */
    private $ytInitialPlayerResponse;

    /**
     * Get video formats
     * 
     * @return array
     */
    public function getFormats()
    {
        return $this->formats;
    }

    /**
     * Get adaptive formats
     * 
     * @return array
     */
    public function getAdaptiveFormats()
    {
        return $this->adaptiveFormats;
    }

    /**
     * Get intial player response data
     * 
     * @return array
     */
    public function getYtInitialPlayerResponse()
    {
        return $this->ytInitialPlayerResponse;
    }

    /**
     * Load video informations and formats
     * 
     * @return void
     */
    public function loadInfoFromUrl($url)
    {
        if (!preg_match(self::YTURLPATTERNS, $url))
        {
            throw new InvalidArgumentException('Invalid YouTube URL provided.');
        }

        $content = $this->getData($url);

        if (!preg_match(self::YTInitialPlayerResponseRegex, $content, $matches))
        {
            throw new Exception('Failed to retrieve initial player response.');
        }

        $this->ytInitialPlayerResponse = json_decode($matches['ytInitialResponse'], true);
        $this->formats = $this->ytInitialPlayerResponse['streamingData']['formats'];
        $this->adaptiveFormats = $this->ytInitialPlayerResponse['streamingData']['adaptiveFormats'];
        $this->decodeFormatsUrls($this->formats);
        $this->decodeFormatsUrls($this->adaptiveFormats);
    }

    /**
     * Decode encoded character in the formats URLs
     * 
     * @param array &$formats
     * @return void
     */
    private function decodeFormatsUrls(&$formats)
    {
        foreach ($formats as $i => $fmt) {
            $formats[$i]['url'] = utf8_decode($fmt['url']);
        }
    }

    /**
     * Get data from a URL
     * 
     * @param string $url
     * @return string
     */
    private function getData($url)
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_REFERER, 'https://www.youtube.com/');
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36');
            $data = curl_exec($ch);
            curl_close($ch);
        } else {
            $data = file_get_contents($url);
        }

        return $data;
    }

}
