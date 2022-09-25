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
     */
    const YTFORMATSREGEX = '#,"formats":(?P<formats>\[(?:.*?)\]),"adaptiveFormats":(?P<adaptiveFormats>\[(?:.*?)\])#';

    /**
     * @var array Formats
     */
    private $formats;

    /**
     * @var array Adaptive formats
     */
    private $adaptiveFormats;

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

        $content = file_get_contents($url);

        if (!preg_match(self::YTFORMATSREGEX, $content, $matches))
        {
            throw new Exception('Failed to retrieve video formats.');
        }

        $this->formats = json_decode($matches['formats']);
        $this->adaptiveFormats = json_decode($matches['adaptiveFormats']);
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
            $formats[$i]->url = utf8_decode($fmt->url);
        }
    }

}