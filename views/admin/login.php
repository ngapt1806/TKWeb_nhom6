<?php
if (isset($_SESSION['admin_name'])) {
    header('Location: '. HOME_ADMIN_URL);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Đăng nhập</title>
    <link href="http://localhost/CentBeauty/assets/img/logo_cent_orage.png" rel="icon">
    <!-- Favicons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-9/assets/css/login-9.css">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/all.css" rel="stylesheet">

    <style>
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

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
<body style="background-color: #652915; overflow-y: hidden ">
<div id="loading-spinner" style="text-align: center;line-height:700px;position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1050; display: flex; align-items: center; justify-content: center;">
    <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<!-- Login 9 - Bootstrap Brain Component -->
<section class="py-5 py-md-5 py-xl-8 mt-1" style="margin-top: 100px!important;">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-12 col-md-6 col-xl-7">
                <div class="d-flex justify-content-center" style="background-color: #652915FF; color: white">
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                 class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                                <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-5">
                <div class="card border-0 rounded-4">
                    <div class="card-body p-3 p-md-4 p-xl-4">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div>
                                    <h3>Đăng Nhập Trang Quản Trị</h3>
                                </div>
                            </div>
                        </div>
                        <form method="post" onsubmit="return false;">
                            <div class="row gy-3 overflow-hidden">
                                <div class="col-12">
                                    <div class="form-floating mb-2">
                                        <input type="tel" class="form-control" name="phone" id="phone"
                                               placeholder="name@example.com" required>
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-2">
                                        <input type="password" class="form-control" name="password" id="password"
                                               value="" placeholder="Password" required>
                                        <label for="password" class="form-label">Mật khẩu</label>
                                        <i class="fas fa-eye password-toggle" id="togglePassword"
                                           style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
                                           cursor: pointer;"></i>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <span id="login-false"></span>
                                </div>
                                <div class="col-12 mt-4">
                                    <div class="d-grid">
                                        <button id="loginButton"
                                                class="btn btn-lg" style="background-color: #652915FF; color: white"
                                                type="submit">Đăng nhập ngay
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end mt-3">
                                    <a href="#!" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    style="color: #652915FF">Quên mật khẩu</a>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <a href="<?php echo LOGIN_CLIENT_URL ?>"
                                       style="color: #652915FF">Đăng nhập người dùng
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        ="fill: #652915;transform: ;msFilter:;">
                                            <path d="m13.061 4.939-2.122 2.122L15.879 12l-4.94 4.939 2.122 2.122L20.121 12z"></path>
                                            <path d="M6.061 19.061 13.121 12l-7.06-7.061-2.122 2.122L8.879 12l-4.94 4.939z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div id="toast" class="toast">Thông báo ở đây!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Thông báo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Hãy liên hệ quản trị viên của bạn để được cấp lại mật khẩu
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="<?php echo BASE_URL ?>/assets/js/toast/use-bootstrap-toaster.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="<?php echo BASE_URL ?>/views/admin/assets/js/functions.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('loading-spinner').style.display = 'none';
        
        var inputs = document.querySelectorAll('.form-control');

        inputs.forEach(function (input) {
            input.addEventListener('input', function () {
                // Xóa thông báo lỗi nếu có
                var errorMessage = input.parentElement.querySelector('.error-message');
                if (errorMessage) {
                    errorMessage.remove();
                }
                // Xóa viền đỏ
                input.classList.remove('invalid-input');
            });
        });

        document.getElementById('loginButton').addEventListener('click', validateAndSubmit);

        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const passwordInput = document.getElementById('password');
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
        document.querySelectorAll('.error-message').forEach(function (message) {
            message.remove();
        });

        inputs.forEach(function (input) {
            var error = null;
            if (!input.value) {
                error = 'Trường này không được để trống';
            } else if (input.name === 'phone' && !/^\d{10}$/.test(input.value)) {
                error = 'Số điện thoại phải là 10 chữ số';
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
                url: '<?php echo BASE_URL ?>/index.php?controller=auth&action=processLoginAdmin',
                type: 'POST',
                data: formData,
                contentType: false, // Không set contentType
                processData: false, // Không xử lý dữ liệu
                success: function(response) {
                    console.log(response);
                    if(response['success'] === true) {
                        success_toast('<?php echo BASE_URL ?>/index.php?controller=home&action=home_admin');
                    } else {
                        inputs.forEach(function (input) {
                            input.classList.add('invalid-input');
                            isValid = false;
                        });
                        failed_toast(response['message'])
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                },
                complete: function() {
                    $("#loading-spinner").hide(); // Ẩn spinner khi yêu cầu hoàn thành
                }
            });
        }
    }

    function success_toast(redirectUrl) {
        toast({
            classes: `text-bg-success border-0`,
            body: `
          <div class="d-flex w-100" data-bs-theme="dark">
            <div class="flex-grow-1">
              Đăng nhập thành công !
            </div>
            <button type="button" class="btn-close flex-shrink-0" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>`,
            autohide: true,
            delay: 2000  // Thời gian hiển thị toast là 5000ms (5 giây)
        });

        // Đợi DOM cập nhật
        setTimeout(() => {
            // Lấy phần tử toast mới nhất
            var toastElement = document.querySelector('.toast.show');
            if (toastElement) {
                var bsToast = new bootstrap.Toast(toastElement); // Khởi tạo lại đối tượng Toast nếu cần
                toastElement.addEventListener('hidden.bs.toast', function () {
                    window.location.href = redirectUrl; // Sử dụng URL được truyền vào
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