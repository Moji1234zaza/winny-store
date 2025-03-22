<?php 

$use = new Bannawat;

$checkauth1 = $use->checkauth1();

?>

<div class="page-banner-section-lg bg-image" data-bg="./assets/bg-sub.jpg" style="background-image: url(./assets/bg-sub.jpg);">

	<!--<div class="page-banner-section-lg" style="background-color: #009cde;background-image: radial-gradient(circle farthest-side at center bottom,#009cde,#003087 125%);">-->

		<div class="container">

			<div class="row">

				<div class="col">

					<div class="page-banner text-center">

						<h2 class="white"><a href="#">เข้าสู่ระบบ</a></h2>

						<ul class="page-breadcrumb">

							<li><a href="?page=home">Home</a></li>

							<li>login</li>

						</ul>

					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="container">

		<div class="login-register-section section pb-20">

			<div class="container">

				<div class="row">

					<!--Login Form Start-->

					<div class="col-lg-8 offset-sm-2">

						<div class="customer-login-register">



							<div class="bg_contentwhite">

								<h3 class="">เข้าสู่ระบบ</h3>

								<hr>







								<div onclick="">

									<div id="error">                                                                                                       

									</div>

								</div>









								<form method="POST">

									<div class="row">

										<div class="col-lg-12">

											<div class="form-fild">

												<input type="hidden" id="affiliate" name="affiliate" value="">

												<p><label>Username <span class="required">*</span></label></p>

												<input type="text" id="username" name="username" placeholder="ชื่อบัญชีผู้ใช้งาน" required="">

											</div>

										</div>

										<div class="col-lg-12">

											<div class="form-fild">

												<p><label>Password <span class="required">*</span></label></p>

												<input type="password" id="password" name="password" placeholder="รหัสผ่าน" required="">

											</div>

										</div>

										<div class="col-lg-6">

											<div class="login-submit mt-15">

												<a type="submit" name="btn_login"  class="btn btn-success submit_login col-12">เข้าสู่ระบบ</a>

											</div>

										</div>

										<div class="col-lg-6">

											<div class="login-submit mt-15">

												<button type="reset" class="btn btn-danger btn-block col-12 clearinput">ยกเลิก</button>

											</div>

										</div>



                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>







</div>

<style type="text/css">

	.btn-success{

		background-color:#28a745;

	}

</style>

<input type="hidden" id="ip_address" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">

<script type="text/javascript">

	$(".clearinput").click(function(){

		$("#username").val('');

		$("#password").val('');

		$("#ip_address").val('');

	})

	$(".submit_login").click(function(){

		var username = $("#username").val();

		var password = $("#password").val();

		var ip_address = $("#ip_address").val();

		$.ajax({

			type:"POST",

			url:"./system/url/login.php",

			dataType:"json",

			data:{username,password,ip_address},

			success:function(data){

				if (data.status == "success") {

					swal("เข้าสู่ระบบ", "เข้าสู่ระบบ สำเร็จ!", "success")

					.then(function(){

						window.location.href = '/home';

					})	

				}else{

					swal({

						icon: 'error',

						title: 'ระบบ',

						text: data.info,

					})

				}

			}

		})

	})

</script>