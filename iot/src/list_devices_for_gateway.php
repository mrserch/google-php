<?php

/**
 * Copyright 2018 Google Inc.
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
namespace Google\Cloud\Samples\Iot;

# [START iot_list_devices_for_gateway]
use Google\Cloud\Iot\V1\DeviceManagerClient;
use Google\Cloud\Iot\V1\GatewayListOptions;

/**
 * List all devices associated to the given gateway.
 *
 * @param string $registryId IOT Device Registry ID
 * @param string $gatewayId The identifier for the gateway
 * @param string $projectId Google Cloud project ID
 * @param string $location (Optional) Google Cloud region
 */
function list_devices_for_gateway(
    $registryId,
    $gatewayId,
    $projectId,
    $location = 'us-central1'
) {
    print('Listing devices for gateway' . PHP_EOL);

    // Instantiate a client.
    $deviceManager = new DeviceManagerClient();

    // Format the full registry path
    $registryName = $deviceManager->registryName($projectId, $location, $registryId);

    // Configure the list options for the gateway
    $gatewayListOptions = (new GatewayListOptions())->setAssociationsGatewayId($gatewayId);

    // Call the API
    $devices = $deviceManager->listDevices($registryName,
        ['gatewayListOptions' => $gatewayListOptions]
    );

    // Print the result
    foreach ($devices->iterateAllElements() as $device) {
        printf('Bound Device: %s' . PHP_EOL, $device->getId());
    }
}
# [END iot_list_devices_for_gateway]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
