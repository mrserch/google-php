<?php
/**
 * Copyright 2020 Google LLC.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Samples\Storage\Tests;

use Google\Cloud\TestUtils\TestTrait;
use Google\Cloud\Storage\StorageClient;
use PHPUnit\Framework\TestCase;

/**
 * Unit Tests for GenerateV4PostPolicy.
 */
class GenerateV4PostPolicy extends TestCase
{
    use TestTrait;

    private static $storage;
    private static $bucketName;
    private static $objectName;

    /** @beforeClass */
    public static function setUpObject()
    {
        self::$storage = new StorageClient();
        self::$bucketName = self::requireEnv('GOOGLE_STORAGE_BUCKET');
        self::$objectName = sprintf('test-object-%s', time());
    }

    public function testGenerateSignedPostPolicy()
    {
        $bucketName = self::$bucketName;
        $objectName = self::$objectName;
        $output = self::runFunctionSnippet('generate_v4_post_policy', [
            $bucketName,
            $objectName,
        ]);

        $this->assertStringContainsString("<form action='https://storage.googleapis.com/$bucketName/", $output);
        $this->assertStringContainsString("<input name='key' value='$objectName'", $output);
        $this->assertStringContainsString("<input name='x-goog-signature'", $output);
        $this->assertStringContainsString("<input name='x-goog-date'", $output);
        $this->assertStringContainsString("<input name='x-goog-credential'", $output);
        $this->assertStringContainsString("<input name='x-goog-algorithm' value='GOOG4-RSA-SHA256'", $output);
        $this->assertStringContainsString("<input name='policy'", $output);
        $this->assertStringContainsString("<input name='x-goog-meta-test' value='data'", $output);
        $this->assertStringContainsString("<input type='file' name='file'/>", $output);
    }
}
