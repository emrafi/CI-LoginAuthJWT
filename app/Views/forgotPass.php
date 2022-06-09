<?= $this->include('layout/header'); ?>
</head>

<body>
    <section class="h-100 w-100" style="box-sizing: border-box; background-color: #f5f5f5">
        <div class="content-3-5 d-flex flex-column align-items-center h-100 flex-lg-row" style="font-family: 'Poppins', sans-serif">
            <div class="position-relative d-none d-lg-block h-100 width-left">
                <img class="position-absolute img-fluid centered" width="400" height="400" src="assets/img/forgotpass.png" alt="forgotpass_picture" />
            </div>
            <div class="d-flex mx-auto align-items-left justify-content-left width-right mx-lg-0">
                <div class="right mx-lg-0 mx-auto">
                    <div class="align-items-center justify-content-center d-lg-none d-flex">
                        <img class="img-fluid" src="assets/img/forgotpass.png" alt="forgotpass_picture" />
                    </div>
                    <?php
                    if (session()->getFlashdata('item')) {
                        $message = session()->getFlashdata('item') ?>
                        <div class="alert alert-<?php echo $message['class'] ?>" role="alert" id="alert-forgotPass">
                            <?php echo $message['message'] ?>
                        </div>
                    <?php
                    }
                    ?>
                    <h3 class="title-text" style="margin-top: 5.5rem">Forgot your password?</h3>
                    <p class="caption-text">
                        Please fill with email has<br />
                        registered on the website.
                    </p>
                    <form style="margin-top: 2rem" action="<?php base_url() ?>\resetPass" method="post">
                        <div style="margin-bottom: 2rem">
                            <label for="" class="d-block input-label">Email Address</label>
                            <div class="d-flex w-100 div-input">
                                <svg class="icon" style="margin-right: 1rem" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5 5C3.34315 5 2 6.34315 2 8V16C2 17.6569 3.34315 19 5 19H19C20.6569 19 22 17.6569 22 16V8C22 6.34315 20.6569 5 19 5H5ZM5.49607 7.13174C5.01655 6.85773 4.40569 7.02433 4.13168 7.50385C3.85767 7.98337 4.02427 8.59422 4.50379 8.86823L11.5038 12.8682C11.8112 13.0439 12.1886 13.0439 12.4961 12.8682L19.4961 8.86823C19.9756 8.59422 20.1422 7.98337 19.8682 7.50385C19.5942 7.02433 18.9833 6.85773 18.5038 7.13174L11.9999 10.8482L5.49607 7.13174Z" fill="#CACBCE" />
                                </svg>
                                <input class="input-field border-0" type="email" name="email" id="" placeholder="Your Email Address" autocomplete="on" required />
                            </div>
                        </div>
                        <button class="btn btn-fill text-white d-block w-100" type="submit" style="margin-bottom: 9rem">
                            Send
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <!-- Password toggle -->
    <script>
        $(document).ready(function() {
            $("#alert-forgotPass").fadeTo(4000, 500).slideUp(500, function() {
                $("#alert-forgotPass").slideUp(500);
            });
        });
    </script>

</body>

</html>