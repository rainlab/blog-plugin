<?php

/**
 * LangFilesTest validates language JSON files are well-formed
 */
class LangFilesTest extends PluginTestCase
{
    /**
     * @dataProvider langFileProvider
     */
    public function testLangFileIsValidJson($filePath)
    {
        $content = file_get_contents($filePath);
        $decoded = json_decode($content);

        $this->assertNotNull(
            $decoded,
            basename($filePath) . ' contains invalid JSON: ' . json_last_error_msg()
        );
    }

    /**
     * @dataProvider langFileProvider
     */
    public function testLangFileIsJsonObject($filePath)
    {
        $content = file_get_contents($filePath);
        $decoded = json_decode($content);

        $this->assertIsObject(
            $decoded,
            basename($filePath) . ' should decode to a JSON object, not an array or scalar'
        );
    }

    /**
     * @dataProvider langFileProvider
     */
    public function testLangFileHasNoEmptyKeys($filePath)
    {
        $content = file_get_contents($filePath);
        $decoded = json_decode($content, true);

        foreach ($decoded as $key => $value) {
            $this->assertNotEmpty(
                trim($key),
                basename($filePath) . ' contains an empty translation key'
            );
        }
    }

    /**
     * @dataProvider langFileProvider
     */
    public function testLangFileValuesAreStrings($filePath)
    {
        $content = file_get_contents($filePath);
        $decoded = json_decode($content, true);

        foreach ($decoded as $key => $value) {
            $this->assertIsString(
                $value,
                basename($filePath) . " key '{$key}' has a non-string value"
            );
        }
    }

    /**
     * langFileProvider returns all JSON files in the lang directory
     */
    public static function langFileProvider(): array
    {
        $langPath = __DIR__ . '/../lang';
        $files = glob($langPath . '/*.json');

        $data = [];
        foreach ($files as $file) {
            $data[basename($file)] = [$file];
        }

        return $data;
    }
}
