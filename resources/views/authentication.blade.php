<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        let token = document.getElementsByTagName('meta')['csrf-token'].content;

        function post(url, data, onSuccess = (data) => {
        }, onError = () => {
        }, onDone = () => {
        }) {
            let xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                // console.log('readyState:', this.readyState, 'status:', this.status, this.responseText);
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    console.log(this.responseText);
                    onSuccess(JSON.parse(this.responseText));
                }
                if (this.readyState === XMLHttpRequest.DONE && this.status !== 200) {
                    console.log(this.responseText);
                    onError(JSON.parse(this.responseText));
                }
                if (this.readyState === XMLHttpRequest.DONE) {
                    onDone();
                }
            };
            xhttp.open("POST", url, true);
            xhttp.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
            xhttp.setRequestHeader('Accept', 'application/json');
            xhttp.setRequestHeader('X-CSRF-Token', token);
            xhttp.send(JSON.stringify(data));
        }
    </script>
</head>
<body>
<center>
    <h3>Authentication</h3>
    <h5>Input Email/Phone to login or register</h5>
</center>
<table border="1" align="center">
    <tr>
        <td width="300">1</td>
        <td width="300">
            <button id="#btn_back2" onclick="onBack2()">Back</button>
            2
        </td>
        <td width="300">
            <button id="#btn_back3" onclick="onBack3()">Back</button>
            3
        </td>
    </tr>
    <tr height="30">
        <td colspan="3" align="center"><span id="#progress">Please wait ...</span></td>
    </tr>
    <tr valign="top">
        <td>
            <input type="text" name="email_phone" placeholder="Email/Phone"><br>
            <button id="#btn_register" onclick="onRegister()">Login/Register</button>
            <div id="msg_1"></div>
        </td>
        <td>
            <input type="text" name="code" placeholder="Code"><br>
            <button id="#btn_verify" onclick="onVerify()">Verify</button>
            <button id="#btn_change2pass" onclick="onChange2pass()" disabled>Change 2 pass</button>
            <div id="msg_2"></div>
        </td>
        <td>
            <input type="password" name="password" placeholder="Pass"><br>
            <input type="password" name="password_confirm" placeholder="Pass Conf"><br>
            <button id="#btn_send" onclick="onSend()">Send</button>
            <div id="msg_3"></div>
        </td>
    </tr>
    <tr height="30">
        <td colspan="3"></td>
    </tr>
    <tr valign="top">
        <td></td>
        <td>
            <button id="#btn_back4" onclick="onBack4()">Back</button>
            4
            (otp options)
            <button id="#btn_change2pass4" onclick="onChange2pass()" disabled>Change 2 pass</button>
        </td>
        <td>
            <button id="#btn_back5" onclick="onBack5()">Back</button>
            5
        </td>
    </tr>
    <tr valign="top">
        <td></td>
        <td>
            <ul id="#otp_options"></ul>
            <div id="msg_4"></div>
        </td>
        <td>
            <input type="password" name="pass" placeholder="Pass"><br>
            <button id="#btn_sendPass" onclick="onSendPass()">Send Pass</button>
            <button id="#btn_change2otp" onclick="onChange2otp()" disabled>Change 2 Otp</button>
            <div id="msg_5"></div>
        </td>
    </tr>
</table>
{{--<form action="{{ route('register.attempt') }}" method="post">--}}
{{--    <input type="text" name="name" value="{{ old('name') }}" placeholder="Name">--}}
{{--    @error('name') {{ $message }} @enderror--}}
{{--</form>--}}

<script>
    let baseUrl = '{{ route('auth.attempt') }}';

    function progressStart() {
        document.getElementById('#progress').style.display = 'block';
    }

    function progressDone() {
        document.getElementById('#progress').style.display = 'none';
    }

    let ulOtpOptions = document.getElementById('#otp_options');
    let btnBack4 = document.getElementById('#btn_back4');
    let msg4 = document.getElementById('msg_4');
    let fromOtpOptionPage = false;

    let btnRegister = document.getElementById('#btn_register');
    let emailPhone = document.getElementsByName('email_phone')[0];
    let msg1 = document.getElementById('msg_1');

    let btnBack2 = document.getElementById('#btn_back2');
    let btnVerify = document.getElementById('#btn_verify');
    let btnChange2pass = document.getElementById('#btn_change2pass');
    let btnChange2pass4 = document.getElementById('#btn_change2pass4');
    let code = document.getElementsByName('code')[0];
    let msg2 = document.getElementById('msg_2');

    let btnBack3 = document.getElementById('#btn_back3');
    let btnSend = document.getElementById('#btn_send');
    let pass = document.getElementsByName('password')[0];
    let passConf = document.getElementsByName('password_confirm')[0];
    let msg3 = document.getElementById('msg_3');

    let btnBack5 = document.getElementById('#btn_back5');
    let btnSendPass = document.getElementById('#btn_sendPass');
    let btnChange2otp = document.getElementById('#btn_change2otp');
    let otpOptions = [];
    let password = document.getElementsByName('pass')[0];
    let msg5 = document.getElementById('msg_5');

    let registerCodePassToken = '';
    let loginCodePassToken = '';

    function toggleBlock(enable, nodes) {
        if (enable) {
            nodes.map((v) => v.removeAttribute('disabled'));
        } else {
            nodes.map((v) => v.setAttribute('disabled', enable));
        }
    }

    function toggleBlock1(enable) {
        toggleBlock(enable, [emailPhone, btnRegister]);
    }

    function toggleBlock2(enable) {
        toggleBlock(enable, [code, btnVerify, btnBack2]);
    }

    function toggleBlock3(enable) {
        toggleBlock(enable, [pass, passConf, btnSend, btnBack3]);
    }

    function toggleBlock4(enable) {
        toggleBlock(enable, [btnBack4].concat(...ulOtpOptions.getElementsByTagName('button')));
    }

    function toggleBlock5(enable) {
        toggleBlock(enable, [password, btnSendPass, btnBack5]);
    }

    function onBack2() {
        if (fromOtpOptionPage) {
            fromOtpOptionPage = false;
            toggleBlock4(true);
        } else {
            toggleBlock1(true);
        }
        toggleBlock2(false);
        toggleBlock(false, [btnChange2pass]);
    }

    function onBack3() {
        toggleBlock1(true);
        toggleBlock3(false);
    }

    function onBack4() {
        toggleBlock1(true);
        toggleBlock4(false);
        ulOtpOptions.innerHTML = '';
        msg4.innerHTML = '';
        toggleBlock(false, [btnChange2pass4]);
    }

    function onBack5() {
        toggleBlock1(true);
        toggleBlock5(false);
        toggleBlock(false, [btnChange2otp]);
    }

    toggleBlock1(true);
    toggleBlock2(false);
    toggleBlock3(false);
    toggleBlock4(false);
    toggleBlock5(false);
    progressDone();

    function onRegister() {
        progressStart();
        toggleBlock1(false);
        registerCodePassToken = '';
        msg1.innerText = '';
        post(baseUrl, {'email_phone': emailPhone.value}, (data) => {
            msg1.innerText = JSON.stringify(data);
            toggleBlock1(true);

            if (data.data.next_page == 'password') {
                toggleBlock1(false);
                toggleBlock5(true);
                showChange2Code(data.data.otp_options);
            } else if (data.data.next_page == 'passwords') {
                toggleBlock1(false);
                toggleBlock3(true);
            } else if (data.data.next_page == 'code') {
                otpOptions = data.data.otp_options;
                toggleBlock1(false);
                toggleBlock2(true);
                if (data.data.can_password === true)
                    toggleBlock(true, [btnChange2pass]);
            } else if (data.data.next_page == 'otp_options') {
                otpOptions = data.data.otp_options;
                toggleBlock1(false);
                showOtpOptions(data.data.otp_options);
                if (data.data.can_password === true)
                    toggleBlock(true, [btnChange2pass4]);
            }
        }, (data) => {
            msg1.innerText = JSON.stringify(data);
            toggleBlock1(true);
        }, progressDone);
    }

    function onVerify() {
        if (code.value === '') {
            msg2.innerText = 'insert code';
            return;
        }
        progressStart();
        toggleBlock2(false);
        msg2.innerText = '';
        let reg_data = {
            'email_phone': emailPhone.value,
            'code': code.value,
        };
        if (loginCodePassToken !== '') {
            reg_data['token'] = loginCodePassToken;
        }
        post(baseUrl, reg_data, (data) => {
            msg2.innerText = JSON.stringify(data);
            toggleBlock2(true);
            if (data.data.next_page == 'passwords') {
                toggleBlock2(false);
                toggleBlock3(true);
                registerCodePassToken = data.data.token;
            } else if (data.data.next_page == 'password') {
                toggleBlock2(false);
                toggleBlock5(true);
                loginCodePassToken = data.data.token;
            }
        }, (data) => {
            msg2.innerText = JSON.stringify(data);
            toggleBlock2(true);

        }, progressDone);
    }

    function onSend() {
        if (pass.value === '') {
            msg3.innerText = 'insert pass and its conf';
            return;
        }
        progressStart();
        toggleBlock3(false);
        msg3.innerText = '';
        let reg_data = {
            'email_phone': emailPhone.value,
            'password': pass.value,
            'password_confirm': passConf.value,
        };
        if (registerCodePassToken !== '')
            reg_data['token'] = registerCodePassToken;
        post(baseUrl, reg_data, (data) => {
            msg3.innerText = JSON.stringify(data);
            toggleBlock3(true);
        }, (data) => {
            msg3.innerText = JSON.stringify(data);
            toggleBlock3(true);
        }, progressDone);
    }

    function showOtpOptions(otpOptions) {
        toggleBlock(true, [btnBack4])
        while (ulOtpOptions.firstChild) {
            ulOtpOptions.removeChild(ulOtpOptions.lastChild);
        }
        otpOptions.forEach((v, i) => {
            const li = document.createElement('li');
            const b = document.createElement('button');
            b.innerText = v;
            b.addEventListener('click', () => {
                console.log(v);
                onOtpSelected(v);
            });
            li.appendChild(b);
            ulOtpOptions.appendChild(li);
        });
    }

    function onOtpSelected(item) {
        progressStart();
        toggleBlock4(false);
        msg4.innerText = '';
        let reg_data = {
            'email_phone': emailPhone.value,
            'force_code_type': item,
        };
        if (loginCodePassToken !== '')
            reg_data['token'] = loginCodePassToken;
        // else
        //     reg_data['password'] = password.value;
        post(baseUrl, reg_data, (data) => {
            msg4.innerText = JSON.stringify(data);
            toggleBlock4(true);
            if (data.data.next_page == 'code') {
                toggleBlock4(false);
                toggleBlock2(true);
                fromOtpOptionPage = true;
                toggleBlock(false, [btnChange2pass4]);
                if (data.data.can_password === true)
                    toggleBlock(true, [btnChange2pass]);

            }
        }, (data) => {
            msg4.innerText = JSON.stringify(data);
            toggleBlock4(true);
        }, progressDone);
    }

    function onSendPass() {
        if (password.value === '') {
            msg5.innerText = 'insert pass';
            return;
        }
        progressStart();
        toggleBlock5(false);
        msg5.innerText = '';
        let reg_data = {
            'email_phone': emailPhone.value,
            'password': password.value,
        };
        if (loginCodePassToken !== '')
            reg_data['token'] = loginCodePassToken;
        post(baseUrl, reg_data, (data) => {
            msg5.innerText = JSON.stringify(data);
            toggleBlock5(true);
            if (data.data.next_page == 'home') {
                //
            } else if (data.data.next_page == 'otp_options') {
                loginCodePassToken = data.data.token;
                toggleBlock5(false);
                showOtpOptions(data.data.otp_options);
            } else if (data.data.next_page == 'code') {
                loginCodePassToken = data.data.token;
                toggleBlock5(false);
                toggleBlock2(false);
            }
        }, (data) => {
            msg5.innerText = JSON.stringify(data);
            toggleBlock5(true);
        }, progressDone);
    }

    function showChange2Code(items) {
        otpOptions = items;
        if (items.length === 0) {
            btnChange2otp.innerText = 'No otp';
            toggleBlock(false, [btnChange2otp]);
        } else if (items.length === 1) {
            btnChange2otp.innerText = 'code to ' + items[0];
            toggleBlock(true, [btnChange2otp]);
        } else {
            btnChange2otp.innerText = 'code to more';
            toggleBlock(true, [btnChange2otp]);
        }
    }

    function onChange2pass() {
        toggleBlock2(false);
        toggleBlock4(false);
        toggleBlock(false, [btnChange2pass, btnChange2pass4]);
        toggleBlock5(true);
        showChange2Code(otpOptions);
    }

    function onChange2otp() {
        if (otpOptions.length === 0) {
            console.log('No otp');
        } else if (otpOptions.length === 1) {
            onSingleOtp(otpOptions[0])
            toggleBlock5(false);
            toggleBlock(false, [btnChange2otp]);
        } else {
            showOtpOptions(otpOptions);
            toggleBlock5(false);
            toggleBlock(false, [btnChange2otp]);
            toggleBlock(true, [btnChange2pass4]);
        }
    }

    function onSingleOtp(item) {
        progressStart();
        toggleBlock5(false);
        msg4.innerText = '';
        let reg_data = {
            'email_phone': emailPhone.value,
            'force_code_type': item,
        };
        post(baseUrl, reg_data, (data) => {
            msg4.innerText = JSON.stringify(data);
            toggleBlock5(true);
            if (data.data.next_page == 'code') {
                toggleBlock5(false);
                toggleBlock2(true);
                fromOtpOptionPage = false;
                if (data.data.can_password === true)
                    toggleBlock(true, [btnChange2pass]);
            }
        }, (data) => {
            msg4.innerText = JSON.stringify(data);
            toggleBlock5(true);
        }, progressDone);
    }
</script>

</body>
</html>
