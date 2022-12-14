<?php
/**
 * Copyright 2016 Google Inc.
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

// [START vision_image_property_detection]
namespace Google\Cloud\Samples\Vision;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

// $path = 'path/to/your/image.jpg'

function detect_image_property($path)
{
    $imageAnnotator = new ImageAnnotatorClient();

    # annotate the image
    $image = file_get_contents($path);
    $response = $imageAnnotator->imagePropertiesDetection($image);
    $props = $response->getImagePropertiesAnnotation();

    print('Properties:' . PHP_EOL);
    foreach ($props->getDominantColors()->getColors() as $colorInfo) {
        printf('Fraction: %s' . PHP_EOL, $colorInfo->getPixelFraction());
        $color = $colorInfo->getColor();
        printf('Red: %s' . PHP_EOL, $color->getRed());
        printf('Green: %s' . PHP_EOL, $color->getGreen());
        printf('Blue: %s' . PHP_EOL, $color->getBlue());
        print(PHP_EOL);
    }

    $imageAnnotator->close();
}
// [END vision_image_property_detection]
