<?php
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'index.php') !== false) {
    // The request was triggered from the index.php page
    
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

    // Get AKS Clusters
    $aks_clusters_url = "https://management.azure.com/subscriptions/$subscriptionId/providers/Microsoft.ContainerService/managedClusters?api-version=2021-07-01-preview";

    $headers = array(
        "Authorization: Bearer $access_token"
    );

    $aks_clusters_request = curl_init($aks_clusters_url);
    curl_setopt($aks_clusters_request, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($aks_clusters_request, CURLOPT_RETURNTRANSFER, true);

    $aks_clusters_response = curl_exec($aks_clusters_request);
    $aks_clusters_info = json_decode($aks_clusters_response);

    echo "<h2>AKS Clusters:</h2>";
    foreach ($aks_clusters_info->value as $aks_cluster) {
        echo "AKS Cluster Name: " . $aks_cluster->name . "<br>";
    }
} else {
    // The request was not triggered from the index.php page
    echo "Unauthorized access!";
}
?>
