@extends('rrg.layout')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <h1>
                User Profile
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="#">
                        <i class="fa fa-dashboard"></i>
                        Home
                    </a>
                </li>
                <li class="active">
                    User profile
                </li>
            </ol>
            <hr>
        </div>
        <div class="content">
            <div class="row">
                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img id="dp" class="profile-user-img img-responsive img-circle" src="<?= $dp; ?>"
                                style="height:10rem;width:10rem" alt="User profile picture">
                            <h3 class="profile-username text-center"><?= $username; ?></h3>
                            <form id="update-dp">
                                <div class="form-group">
                                    <label for="pic" class="btn btn-primary btn-block">
                                        <b>Select Photo</b>
                                    </label>
                                    <input type="file" id="pic" style="display: none;" accept="image/*">
                                    <button id="smt-btn" type="submit" class="btn btn-primary btn-block"
                                        style="margin-top:10px;">
                                        <b>Save</b>
                                    </button>
                                    <button id="upload-success" type="button" data-toggle="modal"
                                        data-target="#modal-success" style="display:none;"></button>
                                </div>
                                <button id="invalid" type="button" data-toggle="modal" data-target="#modal-danger"
                                    style="display:none;">
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Profile information</h3>
                        </div>
                        <div class="box-body">
                            <strong>Name: </strong>
                            <span class="text-muted">
                                <?= $username; ?>
                            </span>
                            <br>

                            <strong>Email: </strong>
                            <span class="text-muted">
                                reymarkbalaod@gmail.com
                            </span>
                            <br>

                            <strong>Project: </strong>
                            <span class="label label-info">RRG</span>
                            <span class="label label-info">TDCX</span>
                            <span class="label label-info">MANDAI</span>
                            <span class="label label-info">USAID</span>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#settings" data-toggle="tab">Settings</a>
                            </li>
                            @if ($username === 'supperadmin')
                                <li class="tab">
                                    <a href="#emailSetup" data-toggle="tab">Email setup</a>
                                </li>
                                <li class="tab">
                                    <a href="#carbonCalculation" data-toggle="tab">Carbon</a>
                                </li>
                            @endif
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="settings">
                                <form style="padding: 0 20px" autocomplete="off" onsubmit="return updateUserInfo(event)"
                                    id="updateUserInfo">
                                    <div class="form-group">
                                        <label for="setting-username">Username</label>
                                        <input type="text" name="settingUsername" class="form-control" id="setting-username"
                                            placeholder="Username">
                                    </div>
                                    <div class="form-group">
                                        <label for="setting-email">Email</label>
                                        <input type="email" name="settingEmail" class="form-control" id="setting-email"
                                            placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <label for="setting-new-pass">New Password</label>
                                        <input type="password" class="form-control" name="settingNewPass"
                                            id="setting-new-pass" placeholder="New Password" oninput="checkPass()">
                                        <span class="help-block text-danger" id="password-error" style="display: none;">
                                            Must be at least 8 characters with uppercase, lowercase, number, and special
                                            character.
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label for="setting-confirm-pass">Confirm Password</label>
                                        <input type="password" class="form-control" name="settingConfirmPass"
                                            id="setting-confirm-pass" placeholder="Re-type New Password"
                                            oninput="confirmPass()">
                                        <span class="help-block text-danger" id="password-check" style="display: none;">
                                            Password do not match!
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label for="setting-current-pass">Current Password</label>
                                        <input type="password" class="form-control" name="settingCurrentPass"
                                            id="setting-current-pass" placeholder="Current Password" minlength="6" required>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger">Save</button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="emailSetup">
                                <form id="email-setup" style="padding: 0 20px;" onsubmit="return updateEmailConfig(event)"
                                    autocomplete="off">
                                    <div class="form-group">
                                        <label for="email-host">Host</label>
                                        <input type="text" name="emailHost" id="email-host" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="email-username">Username</label>
                                        <input type="text" name="emailUsername" id="email-username" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="email-password">Password</label>
                                        <div class="input-group">
                                            <input type="password" name="emailPassword" id="email-password"
                                                class="form-control">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id="toggle-password">
                                                    <i class="glyphicon glyphicon-eye-open"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email-security">Security</label>
                                        <select name="emailSecurity" id="email-security" class="form-control">
                                            <option value="tls">TLS</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="email-port">Port</label>
                                        <input type="text" name="emailPort" id="email-port" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="glyphicon glyphicon-refresh glyphicon-spin"
                                                style="display: none;"></span>
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="carbonCalculation">
                                <form style="padding: 0 20px;" autocomplete="off"
                                    onsubmit="return updateCarbonSetting(event)">
                                    <div class="form-group">
                                        <label for="agb">Above Ground Biomass</label>
                                        <select class="form-control" name="agb" id="agb">
                                            <option value="chave2014">Chave et., al. (2014)</option>
                                            <option value="chave2005">Chave et., al. (2005)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="bgb">Below Ground Biomass</label>
                                        <select class="form-control" name="bgb" id="bgb">
                                            <option value="mokany2006"> Mokany 2006 Below Ground Biomass</option>
                                            <option value="pearson2005">Person 2005 Below Ground Biomass</option>
                                            <option value="luo2012">Luo 2012 Below Ground Biomass</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-success fade" id="modal-success">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-danger fade" id="modal-danger">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
function openModal(id, message) {
    const msg = $('.modal-title');
    msg.text("");
    msg.text(message);

    $(document).ready(function() {
        $(`#${id}`).modal("show");
    });
}

async function carbonSetting() {
    const agbInput = document.getElementById("agb");
    const bgbInput = document.getElementById("bgb");
    try {
        const data = await fetch("/0/api/v1/carbonConfig?action=read", {
            method: "POST"
        });
        if (!data.ok) throw new Error("Network is not ok!");
        const res = await data.json();
        const agb = res.agb;
        const bgb = res.bgb;
        if (agb.chave2005) agbInput.value = "chave2005";
        if (agb.chave2014) agbInput.value = "chave2014";
        if (bgb.mokany2006) bgbInput.value = "mokany2006";
        if (bgb.pearson2005) bgbInput.value = "pearson2005";
        if (bgb.luo2012) bgbInput.value = "luo2012";
        //console.log(agb);
    } catch (err) {
        console.error(err)
    }
}

async function updateCarbonSetting(event) {
    event.preventDefault();

    const confirmEdit = confirm("Confirm save changes!");
    if (!confirmEdit) return;

    const param = {
        agb: {
            chave2014: true,
            chave2005: false
        },
        bgb: {
            mokany2006: false,
            pearson2005: false,
            luo2012: true
        }
    }

    const form = event.target;
    const formData = new FormData(form);

    let userData = {};

    formData.forEach((value, key) => {
        userData[key] = value;
    });

    Object.keys(param.agb).forEach(key => param.agb[key] = false);
    Object.keys(param.bgb).forEach(key => param.bgb[key] = false);

    // Update selected values to true
    if (param.agb.hasOwnProperty(userData.agb)) {
        param.agb[userData.agb] = true;
    }
    if (param.bgb.hasOwnProperty(userData.bgb)) {
        param.bgb[userData.bgb] = true;
    }

    try {
        const data = await fetch("/0/api/v1/carbonConfig?action=update", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(param)
        });
        if (!data.ok) throw new Error("Network is not ok!");
        const res = await data.json();
        const state = res.state;

        if (!state) return openModal("modal-danger", res.msg);

        openModal("modal-success", res.msg);
        carbonSetting();
    } catch (err) {
        console.error(err)
    }

    return false;
}

document.getElementById('toggle-password').addEventListener('click', function() {
    var passwordInput = document.getElementById('email-password');
    var icon = this.querySelector('i');

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.remove("glyphicon-eye-open");
        icon.classList.add("glyphicon-eye-close");
    } else {
        passwordInput.type = "password";
        icon.classList.remove("glyphicon-eye-close");
        icon.classList.add("glyphicon-eye-open");
    }
});

async function emailConfig() {
    try {

        const data = await fetch("/0/phpMailer/config?action=read", {
            method: "POST"
        });
        if (!data.ok) throw new Error("Network is not ok!");
        const res = await data.json();
        initEmailSetting(res);
    } catch (err) {
        console.error(err)
    }
}

function initEmailSetting(data) {
    document.getElementById("email-host").value = data.host;
    document.getElementById("email-username").value = data.username;
    document.getElementById("email-password").value = data.password;
    document.getElementById("email-security").value = data.SMTPSecure;
    document.getElementById("email-port").value = data.port;
}

async function getUserInfo() {
    try {
        const data = await fetch("/0/api/userInfo?action=read");
        if (!data.ok) throw new Error("Network is not ok!");
        const res = await data.json();
        const state = res.state;
        const resData = res.data;
        if (!state) return alert(res.msg);
        document.getElementById("setting-username").value = resData.user_name;
        document.getElementById("setting-email").value = resData.user_email;
    } catch (err) {
        console.error(err)
    }
}

function confirmPass() {
    const newPass = document.getElementById("setting-new-pass");
    const confirmPass = document.getElementById("setting-confirm-pass");
    const errorMessage = document.getElementById("password-check");
    const newPassVal = newPass.value.trim();
    const confirmPassVal = confirmPass.value.trim();

    if (confirmPassVal === "") {
        errorMessage.style.display = "none"; // Hide error if valid
        confirmPass.closest('.form-group').classList.remove('has-error', 'has-success');
        return;
    }

    if (confirmPassVal === newPassVal) {
        errorMessage.style.display = "none";
        confirmPass.closest('.form-group').classList.remove('has-error');
        confirmPass.closest('.form-group').classList.add('has-success');
    } else {
        errorMessage.style.display = "block";
        confirmPass.closest('.form-group').classList.add('has-error');
        return;
    }

}

function checkPass() {
    const passwordInput = document.getElementById("setting-new-pass");
    const errorMessage = document.getElementById("password-error");
    const password = passwordInput.value.trim();
    const regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if (password === "") {
        errorMessage.style.display = "none"; // Hide error if valid
        passwordInput.closest('.form-group').classList.remove('has-error', 'has-success');
        return;
    }

    if (!regex.test(password)) {
        errorMessage.style.display = "block";
        passwordInput.closest('.form-group').classList.add('has-error');
        return;
    } else {
        errorMessage.style.display = "none";
        passwordInput.closest('.form-group').classList.remove('has-error');
        passwordInput.closest('.form-group').classList.add('has-success');
    }
}

async function updateUserInfo(event) {
    event.preventDefault();

    const confirmEdit = confirm("Confirm save changes!");
    if (!confirmEdit) return;

    const form = event.target;
    const formData = new FormData(form);

    let userData = {};

    formData.forEach((value, key) => {
        userData[key] = value;
    });

    const queryParams = new URLSearchParams(userData).toString();

    try {
        const data = await fetch(`/0/api/userInfo?action=update&${queryParams}`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            }
        });

        if (!data.ok) throw new Error("Network is not ok!");
        const res = await data.json();
        console.log(res);
        const state = res.state;
        if (!state) {
            openModal("modal-danger", res.msg)
            return;
        }
        openModal("modal-success", res.msg);
    } catch (err) {
        console.error(err)
    }

    return false;
}

async function updateEmailConfig(event) {
    event.preventDefault();

    const confirmEdit = confirm("Confirm save changes!");
    if (!confirmEdit) return;

    const form = event.target;
    const formData = new FormData(form);

    let userData = {};

    formData.forEach((value, key) => {
        userData[key] = value;
    });

    console.log(userData);

    try {
        const data = await fetch("/0/phpMailer/config?action=write", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(userData)
        });

        if (!data.ok) throw new Error("Network is not ok!");
        const res = await data.text();
        console.log(res);
        emailConfig();
        openModal("modal-success", res);
    } catch (err) {
        console.error(err)
    }

    return false;
}

emailConfig();
getUserInfo();
carbonSetting();
</script>