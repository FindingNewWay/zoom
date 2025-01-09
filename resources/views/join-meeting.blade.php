<!DOCTYPE html>
<html>
<head>
    <title>Join Zoom Meeting</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.9.5/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.9.5/css/react-select.css" />
</head>
<body class="ReactModal__Body--open">
    <div id="zmmtg-root"></div>
    <div id="aria-notify-area"></div>
    <div class="ReactModalPortal"></div>
    <div class="ReactModalPortal"></div>
    <div class="ReactModalPortal"></div>
    <div class="ReactModalPortal"></div>
    <div class="global-pop-up-box"></div>
    <div class="sharer-controlbar-container sharer-controlbar-container--hidden"></div>

    <script src="https://source.zoom.us/2.9.5/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/2.9.5/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/2.9.5/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/2.9.5/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/2.9.5/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-2.9.5.min.js"></script>
    <script src="https://source.zoom.us/2.9.5/zoom-meeting-embedded-2.9.5.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ensure ZoomMtg is defined
            if (typeof ZoomMtg !== 'undefined') {
                ZoomMtg.setZoomJSLib('https://source.zoom.us/2.9.5/lib', '/av');
                ZoomMtg.preLoadWasm();
                ZoomMtg.prepareWebSDK();
                ZoomMtg.i18n.load('en-US');
                ZoomMtg.i18n.reload('en-US');

                const meetConfig = {
                    sdkKey: '{{ env('ZOOM_CLIENT_ID') }}',
                    signature: '{{ $signature }}',
                    meetingNumber: '{{ $meetingNumber }}',
                    userName: 'Guest',
                    passWord: '{{ $passWord }}',
                    leaveUrl: 'http://localhost:8000',
                    role: 0
                };

                ZoomMtg.init({
                    leaveUrl: meetConfig.leaveUrl,
                    success: function () {
                        ZoomMtg.join({
                            sdkKey: meetConfig.sdkKey,
                            signature: meetConfig.signature,
                            meetingNumber: meetConfig.meetingNumber,
                            passWord: meetConfig.passWord,
                            userName: meetConfig.userName,
                            success: function (res) {
                                console.log('join meeting success');
                            },
                            error: function (res) {
                                console.log(res);
                            }
                        });
                    },
                    error: function (res) {
                        console.log(res);
                    }
                });
            } else {
                console.error('ZoomMtg is not defined');
            }
        });
    </script>
</body>
</html>
