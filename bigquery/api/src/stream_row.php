<?php
/**
 * Copyright 2018 Google LLC.
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

/**
 * For instructions on how to run the full sample:
 *
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/master/bigquery/api/README.md
 */

namespace Google\Cloud\Samples\BigQuery;

// Include Google Cloud dependendencies using Composer
require_once __DIR__ . '/../vendor/autoload.php';

if (count($argv) < 4 || count($argv) > 5) {
    return printf("Usage: php %s PROJECT_ID DATASET_ID TABLE_ID [DATA]\n", __FILE__);
}
list($_, $projectId, $datasetId, $tableId) = $argv;
$data = isset($argv[4]) ? json_decode($argv[4], true) : ['field1' => 'value1'];

# [START bigquery_table_insert_rows]
use Google\Cloud\BigQuery\BigQueryClient;

/** Uncomment and populate these variables in your code */
// $projectId = 'The Google project ID';
// $datasetId = 'The BigQuery dataset ID';
// $tableId   = 'The BigQuery table ID';
// $data = [
//     "field1" => "value1",
//     "field2" => "value2",
// ];

// instantiate the bigquery table service
$bigQuery = new BigQueryClient([
    'projectId' => $projectId,
]);
$dataset = $bigQuery->dataset($datasetId);
$table = $dataset->table($tableId);

$insertResponse = $table->insertRows([
    ['data' => $data],
    // additional rows can go here
]);
if ($insertResponse->isSuccessful()) {
    print('Data streamed into BigQuery successfully' . PHP_EOL);
} else {
    foreach ($insertResponse->failedRows() as $row) {
        foreach ($row['errors'] as $error) {
            printf('%s: %s' . PHP_EOL, $error['reason'], $error['message']);
        }
    }
}
# [END bigquery_table_insert_rows]
