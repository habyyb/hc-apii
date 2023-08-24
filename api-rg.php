<?php
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'index.php') !== false) {
$tenantId = 'YOUR_TENANT_ID';
$client_id = 'YOUR_CLIENT_ID';
$client_secret = 'YOUR_CLIENT_SECRET';
$subscriptionId = 'YOUR_SUBSCRIPTION_ID';

// Get access token
$token_url = "https://login.microsoftonline.com/$tenantId/oauth2/token";
$data = array(
    'grant_type' => 'client_credentials',
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'resource' => 'https://management.azure.com/'
);

$token_request = curl_init($token_url);
curl_setopt($token_request, CURLOPT_POST, true);
curl_setopt($token_request, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($token_request, CURLOPT_RETURNTRANSFER, true);

$token_response = curl_exec($token_request);
$token_info = json_decode($token_response);

$access_token = $token_info->access_token;

// Get Resource Groups
$resource_groups_url = "https://management.azure.com/subscriptions/$subscriptionId/resourceGroups?api-version=2021-04-01";

$headers = array(
    "Authorization: Bearer $access_token"
);

$resource_groups_request = curl_init($resource_groups_url);
curl_setopt($resource_groups_request, CURLOPT_HTTPHEADER, $headers);
curl_setopt($resource_groups_request, CURLOPT_RETURNTRANSFER, true);

$resource_groups_response = curl_exec($resource_groups_request);
$resource_groups_info = json_decode($resource_groups_response);

foreach ($resource_groups_info->value as $resource_group) {
    echo "Resource Group Name: " . $resource_group->name . PHP_EOL;
}
} else {
    // The request was not triggered from the index.php page
    echo "Unauthorized access!";
}

?>
