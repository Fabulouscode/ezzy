<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Captcha Verification</title>

    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"></script>
</head>

<body>
<div id="html_element"></div>

<script type="text/javascript">
    function onloadCallback() {
        grecaptcha.render('html_element', {
            'sitekey': findGetParameter("api_key"),
            'callback': verifyCallback
        });
    }

    function findGetParameter(parameterName) {
        let result = null
        location.search.slice(1).split("&").forEach(function (item) {
            const parts = item.split("=");
            if (parts[0] === parameterName) result = decodeURIComponent(parts[1]);
        });
        return result;
    }

    async function verifyCallback(token) {
        try {
            await ensurePlatformReady;
            window.flutter_inappwebview.callHandler("recaptcha_response", token)
        } catch (e) {
            console.log("Something wrong...");
        }
    }

    const ensurePlatformReady = new Promise((resolve, reject) => {
        window.addEventListener("flutterInAppWebViewPlatformReady", () => {
            resolve()
        })
    });

</script>
</body>

</html>
