<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
    <title>Prezzi</title>
    <link rel="shortcut icon" href="images/logo.svg" type="image/x-icon">
    <link rel="manifest" href="manifest.webmanifest">
    <!-- <link rel="apple-touch-icon" href="images/cool-logo.svg" /> -->
    <link rel="apple-touch-icon" sizes="192x192" href="images/logo192.png" />
    <link rel="apple-touch-icon" sizes="512x512" href="images/logo512.png" />
    <link rel="stylesheet" href="css/defaults.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <script src="https://leaflet.github.io/Leaflet.markercluster/dist/leaflet.markercluster-src.js"></script>
    <script src="https://leaflet.github.io/Leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="dist/maptiler-sdk.css">
    <script src="https://cdn.maptiler.com/maptiler-sdk-js/v2.0.3/maptiler-sdk.umd.js"></script>
    <link href="https://cdn.maptiler.com/maptiler-sdk-js/v2.0.3/maptiler-sdk.css" rel="stylesheet" />
    <script src="https://cdn.maptiler.com/leaflet-maptilersdk/v2.0.0/leaflet-maptilersdk.js"></script>
    
    <!-- <link rel="preload" as="script" href="https://cdn.iubenda.com/cs/iubenda_cs.js"/>
    <link rel="preload" as="script" href="https://cdn.iubenda.com/cs/tcf/stub-v2.js"/>
    <script src="https://cdn.iubenda.com/cs/tcf/stub-v2.js"></script>
    <script>
    (_iub=self._iub||[]).csConfiguration={
        cookiePolicyId: 75203500,
        siteId: 3626080,
        localConsentDomain: 'prezzi.altervista.org',
        timeoutLoadConfiguration: 30000,
        lang: 'it',
        enableTcf: true,
        tcfVersion: 2,
        tcfPurposes: {
             "2": "consent_only",
             "3": "consent_only",
             "4": "consent_only",
             "5": "consent_only",
             "6": "consent_only",
             "7": "consent_only",
             "8": "consent_only",
             "9": "consent_only",
            "10": "consent_only"
        },
        invalidateConsentWithoutLog: true,
        googleAdditionalConsentMode: true,
        consentOnContinuedBrowsing: false,
        banner: {
            position: "top",
            acceptButtonDisplay: true,
            customizeButtonDisplay: true,
            closeButtonDisplay: true,
            closeButtonRejects: true,
            fontSizeBody: "14px",
        },
    }
    </script>
    <script async src="https://cdn.iubenda.com/cs/iubenda_cs.js"></script> -->
</head>