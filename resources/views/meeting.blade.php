<!DOCTYPE html>
<html>
<head>
    <title>Zoom Meeting</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://source.zoom.us/1.9.0/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/1.9.0/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/1.9.0/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/1.9.0/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/1.9.0/lib/vendor/lodash.min.js"></script> <!-- Added lodash library -->
    <script src="https://source.zoom.us/zoom-meeting-1.9.0.min.js"></script>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        #zmmtg-root {
            height: 100vh;
        }
    </style>
</head>
<body>
    <div id="zmmtg-root"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ensure ZoomMtg is defined
            if (typeof ZoomMtg !== 'undefined') {
                ZoomMtg.setZoomJSLib('https://source.zoom.us/1.9.0/lib', '/av');
                ZoomMtg.preLoadWasm();
                ZoomMtg.prepareJssdk();

                const urlParams = new URLSearchParams(window.location.search);
                const meetConfig = {
                    apiKey: '2A1tDNKQkuanonCBNYNOw',
                    apiSecret: 'MtcRcybnrxA7OkuQv7AcnDdqVGeNv4DR',
                    meetingNumber: urlParams.get('meetingNumber'),
                    userName: urlParams.get('userName'),
                    passWord: urlParams.get('passWord'),
                    leaveUrl: urlParams.get('leaveUrl'),
                    role: urlParams.get('role')
                };

                ZoomMtg.generateSDKSignature({
                    meetingNumber: meetConfig.meetingNumber,
                    apiKey: meetConfig.apiKey,
                    apiSecret: meetConfig.apiSecret,
                    role: meetConfig.role,
                    success: function (res) {
                        meetConfig.signature = res.result;
                        console.log(meetConfig);
                        ZoomMtg.init({
                            leaveUrl: meetConfig.leaveUrl,
                            isSupportAV: true,
                            success: function () {
                                ZoomMtg.join({
                                    signature: meetConfig.signature,
                                    meetingNumber: meetConfig.meetingNumber,
                                    userName: meetConfig.userName,
                                    apiKey: meetConfig.apiKey,
                                    userEmail: '', // optional
                                    passWord: meetConfig.passWord,
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
                    },
                    error: function (err) {
                        console.error(err);
                    }
                });
            } else {
                console.error('ZoomMtg is not defined');
            }
        });
    </script>
</body>
</html>