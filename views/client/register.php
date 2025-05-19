<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Đăng Kí</title>
    <link href="http://localhost/CentBeauty/assets/img/logo_cent_orage.png" rel="icon">
    <link href="assets/img/favicon.png" rel="apple-touch-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .error-message {
            color: red;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .invalid-input {
            border-color: red;
        }
    </style>
</head>
<body style="background-color: #D25B33; color: white">
<div id="loading-spinner"
     style="text-align: center;line-height:700px;position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1051; display: flex; align-items: center; justify-content: center;">
    <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<section class=" py-3 py-md-5 py-xl-8" style=" margin-top: 40px">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-12 col-md-6 col-xl-7">
                <div class="d-flex justify-content-center">
                    <div class="col-12 col-xl-9">
                        <a href="<?php echo HOME_CLIENT_URL ?>"
                           class="logo me-auto">
                            <img class="img-fluid rounded mb-4" loading="lazy" src="assets/img/logo_cent_white.png" width="345"
                                 alt="BootstrapBrain Logo">
                        </a>
                        <hr class="border-primary-subtle mb-4">
                        <h2 class="h2 mb-4">Chào mừng đến với Cent Beauty.</h2>
                        <p class="lead mb-5">Với đội ngũ Founder và nhân sự tới 99,99% thuộc thế hệ GenZ - Thế hệ vàng
                            phát triển của đất nước. Cent Beauty là nơi hội tụ những “con người trẻ” tạo ra giá trị mỗi
                            ngày cho “thế hệ trẻ”. Đó là lý do chúng mình ra đời với định vị: “Hệ thống Spa tiên phong
                            dành cho giới trẻ.”</p>
                        <div class="text-endx">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                                <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-5">
                <div class="card border-0 rounded-4">
                    <div class="card-body p-3 p-md-4 p-xl-4 mb-2">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div>
                                    <h3>Đăng Kí</h3>
                                    <p>Bạn đã có tài khoản? <a href="<?php echo LOGIN_CLIENT_URL ?>">Đăng nhập</a></p>
                                </div>
                            </div>
                        </div>
                        <form method="post"  onsubmit="return false;">
                            <div class="row gy-3 overflow-hidden">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input size="" type="text" class="form-control" name="name" id="name" maxlength="255" required>
                                        <label for="name" class="form-label">Họ và tên</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" name="phone" id="phone" maxlength="10" placeholder="xxxx xxx xxx" required>
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" name="password" maxlength="255" id="password" value="" placeholder="Mật khẩu" required>
                                        <label for="password" class="form-label">Mật khẩu</label>
                                        <i class="fas fa-eye password-toggle" id="togglePasswordRe"
                                           style="position: absolute; right: 10px; top: 50%;
                                           transform: translateY(-50%); cursor: pointer;"></i>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" maxlength="255" name="re-password" id="re-password" value="" placeholder="Xác nhận mật khẩu" required>
                                        <label for="password" class="form-label">Xác nhận mật khẩu</label>
                                        <i class="fas fa-eye password-toggle" id="togglePasswordReCon"
                                           style="position: absolute; right: 10px; top: 50%;
                                           transform: translateY(-50%); cursor: pointer;"></i>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="d-grid">
                                        <button class="btn btn-lg" style="background-color: #D25B33; color: white" type="submit" id="registerButton">Đăng kí</button>
                                        <div id="response"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="toast" class="toast">Thông báo ở đây!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="<?php echo BASE_URL ?>/assets/js/toast/use-bootstrap-toaster.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('loading-spinner').style.display = 'none';
        document.getElementById('togglePasswordRe').addEventListener('click', function (e) {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
        document.getElementById('togglePasswordReCon').addEventListener('click', function (e) {
            const passwordInput = document.getElementById('re-password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    });
    function validateAndSubmit() {
        var isValid = true;
        var formData = new FormData();
        var inputs = document.querySelectorAll('.form-control');

        // Xóa các thông báo lỗi trước đó
        document.querySelectorAll('.error-message').forEach(function(message) {
            message.remove();
        });

        inputs.forEach(function(input) {
            var error = null;
            if (!input.value) {
                error = 'Trường này không được để trống';
            } else if (input.name === 'phone' && !/^\d{10}$/.test(input.value)) {
                error = 'Số điện thoại phải là 10 chữ số';
            }
            else if (input.name === 'password') {
                if (input.value.length < 8) {
                    error = 'Mật khẩu phải có ít nhất 8 ký tự';
                } else if (!/[A-Z]/.test(input.value)) {
                    error = 'Mật khẩu phải có ít nhất một chữ cái in hoa';
                } else if (!/[a-z]/.test(input.value)) {
                    error = 'Mật khẩu phải có ít nhất một chữ cái thường';
                } else if (!/[0-9]/.test(input.value)) {
                    error = 'Mật khẩu phải có ít nhất một chữ số';
                }
            }
            else if (input.name === 're-password' && input.value !== document.getElementById('password').value) {
                error = 'Mật khẩu xác nhận không khớp';
            }

            if (error) {
                var errorMessage = document.createElement('div');
                errorMessage.classList.add('error-message');
                errorMessage.textContent = error;
                input.classList.add('invalid-input');
                input.parentElement.appendChild(errorMessage);
                isValid = false;
            } else {
                formData.append(input.name, input.value);
                input.classList.remove('invalid-input');
            }
        });

        if (isValid) {
            document.getElementById('loading-spinner').style.display = 'block';
            $.ajax({
                url: '<?php echo BASE_URL ?>/index.php?controller=auth&action=processRegister',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    if(response['success'] === true) {
                        success_toast('<?php echo LOGIN_CLIENT_URL ?>')
                        console.log('Thông tin session:', response['sessionData']);
                    } else {
                        if (response['message'] === 'Số điện thoại đã được đăng ký') {
                            failed_toast('Số điện thoại đã được đăng ký')
                            var phoneInput = document.getElementById('phone');
                            phoneInput.classList.add('invalid-input');
                            var errorMessage = document.createElement('div');
                            errorMessage.classList.add('error-message');
                            errorMessage.textContent = response['message'];
                            phoneInput.parentElement.appendChild(errorMessage);
                            document.getElementById('loading-spinner').style.display = 'none';
                        } else {
                            failed_toast('Đã có lỗi xảy ra, vui lòng thử lại sau')
                            document.getElementById('loading-spinner').style.display = 'none';
                        }
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                }
            });
        }
    }
    document.getElementById('registerButton').addEventListener('click', validateAndSubmit);
</script>
<script>
    function success_toast(redirectUrl) {
        toast({
            classes: `text-bg-success border-0`,
            body: `
          <div class="d-flex w-100" data-bs-theme="dark">
            <div class="flex-grow-1">
              Đăng kí thành công !
            </div>
            <button type="button" class="btn-close flex-shrink-0" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>`,
            autohide: true,
            delay: 1000
        });
        setTimeout(() => {
            var toastElement = document.querySelector('.toast.show');
            if (toastElement) {
                var bsToast = new bootstrap.Toast(toastElement);
                toastElement.addEventListener('hidden.bs.toast', function () {
                    window.location.href = redirectUrl;
                });
            }
        }, 100);
    }

    function failed_toast(message) {
        toast({
            classes: `text-bg-danger border-0`,
            body: `
              <div class="d-flex w-100" data-bs-theme="dark">
                <div class="flex-grow-1">
                  ${message}
                </div>
                <button type="button" class="btn-close flex-shrink-0" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>`,
        })
    }
</script>
</body>
</html>